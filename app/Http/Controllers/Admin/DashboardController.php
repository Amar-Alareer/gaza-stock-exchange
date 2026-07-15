<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $itemsCount = \App\Models\Item::count();
        $storesCount = \App\Models\Store::count();
        $activeComplaintsCount = \App\Models\Complaints::where('status', 'pending')->count();

        $comparisonsThisMonth = \App\Models\Price::whereMonth('created_at', \Carbon\Carbon::now()->month)
            ->whereYear('created_at', \Carbon\Carbon::now()->year)
            ->count();

        $articlesCount = \App\Models\Article::count();
        $latestProducts = \App\Models\Price::with(['item', 'store'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($price) {
                return [
                    'id'         => $price->id,
                    'item_name'  => $price->item->name ?? 'منتج غير معروف',
                    'category'   => $price->item->category ?? 'غير مصنف',
                    'store_name' => $price->store->name ?? 'متجر غير معروف',
                    'price'      => $price->price,
                    'date'       => $price->created_at->format('Y-m-d')
                ];
            });

        $latestStores = \App\Models\Store::with('region')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($store) {
                return [
                    'id'     => $store->id,
                    'name'   => $store->name,
                    'phone'  => $store->phone,
                    'region' => $store->region->name ?? $store->region->area_name ?? 'غير محدد'
                ];
            });

        return response()->json([
            'status'          => 'success',
            'stats'           => [
                'items_count'              => $itemsCount,
                'stores_count'             => $storesCount,
                'comparisons_this_month'   => $comparisonsThisMonth,
                'active_complaints_count'  => $activeComplaintsCount,
                'articles_count'           => $articlesCount,
            ],
            'latest_products' => $latestProducts,
            'latest_stores'   => $latestStores
        ], 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price'     => 'required|numeric|min:0',
        ]);

        $priceRecord = \App\Models\Price::with('item')->find($id);

        if (!$priceRecord) {
            return response()->json([
                'status'  => 'error',
                'message' => 'سجل السعر غير موجود'
            ], 404);
        }

        $priceRecord->price = $request->price;
        $priceRecord->save();

        if ($priceRecord->item) {
            $priceRecord->item->name = $request->item_name;
            $priceRecord->item->save();
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تحديث المنتج والسعر بنجاح'
        ], 200);
    }

    public function deleteProduct($id)
    {
        $priceRecord = \App\Models\Price::find($id);

        if (!$priceRecord) {
            return response()->json([
                'status'  => 'error',
                'message' => 'المنتج/السعر غير موجود'
            ], 404);
        }

        $priceRecord->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'تم حذف سجل السعر بنجاح'
        ], 200);
    }
    public function globalSearch(Request $request)
{
    $query = $request->get('query');

    if (empty($query) || strlen($query) < 2) {
        return response()->json([
            'status' => 'success',
            'results' => []
        ]);
    }

    // 1. البحث في المنتجات (Items)
    $items = \App\Models\Item::where('name', 'LIKE', "%{$query}%")
        ->orWhere('category', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get()
        ->map(function($item) {
            return [
                'title' => $item->name,
                'subtitle' => 'الفئة: ' . ($item->category ?? 'غير مصنف'),
                'type' => 'منتج',
                'link' => '/products' // يمكنك تعديله لمسار صفحة المنتجات في الـ Vue
            ];
        });

    // 2. البحث في المتاجر (Stores)
    $stores = \App\Models\Store::where('name', 'LIKE', "%{$query}%")
        ->orWhere('phone', 'LIKE', "%{$query}%")
        ->limit(5)
        ->get()
        ->map(function($store) {
            return [
                'title' => $store->name,
                'subtitle' => 'الهاتف: ' . ($store->phone ?? 'غير مدرج'),
                'type' => 'متجر',
                'link' => '/stores'
            ];
        });

    // 3. البحث في المقالات (Articles)
    // قمنا بوضع فحص للتأكد من وجود الموديل لكي لا يسبب خطأ إذا لم يكن جاهزاً بالكامل
    $articles = [];
    if (class_exists('\App\Models\Article')) {
        $articles = \App\Models\Article::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function($article) {
                return [
                    'title' => $article->title,
                    'subtitle' => 'مقالة منشورة',
                    'type' => 'مقالة',
                    'link' => '/articles'
                ];
            });
    }

    // دمج كل النتائج في مصفوفة واحدة مرتبة
    $mergedResults = array_merge(
        $items->toArray(),
        $stores->toArray(),
        is_array($articles) ? $articles : $articles->toArray()
    );

    return response()->json([
        'status' => 'success',
        'results' => $mergedResults
    ], 200);
}
}

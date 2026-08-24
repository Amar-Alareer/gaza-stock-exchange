<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Complaints;
use App\Models\Item;
use App\Models\Price;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $itemsCount = Item::count();
        $storesCount = Store::count();
        $activeComplaintsCount = Complaints::where('status', 'pending')->count();

        $comparisonsThisMonth = Price::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $articlesCount = Article::count();
        $latestProducts = Price::with(['item', 'store'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($price) {
                return [
                    'id' => $price->id,
                    'item_name' => $price->item->name ?? 'منتج غير معروف',
                    'category' => $price->item->category ?? 'غير مصنف',
                    'store_name' => $price->store->name ?? 'متجر غير معروف',
                    'price' => $price->price,
                    'date' => $price->created_at ? $price->created_at->format('Y-m-d') : null,
                ];
            });

        $latestStores = Store::with('region')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($store) {
                return [
                    'id' => $store->id,
                    'name' => $store->name,
                    'phone' => $store->phone,
                    'region' => $store->region->name ?? $store->region->area_name ?? 'غير محدد',
                ];
            });

        return response()->json([
            'status' => 'success',
            'stats' => [
                'items_count' => $itemsCount,
                'stores_count' => $storesCount,
                'comparisons_this_month' => $comparisonsThisMonth,
                'active_complaints_count' => $activeComplaintsCount,
                'articles_count' => $articlesCount,
            ],
            'latest_products' => $latestProducts,
            'latest_stores' => $latestStores,
        ], 200);
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $priceRecord = Price::with('item')->find($id);

        if (! $priceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'سجل السعر غير موجود',
            ], 404);
        }

        $priceRecord->price = $request->price;
        $priceRecord->save();

        if ($priceRecord->item) {
            $priceRecord->item->name = $request->item_name;
            if ($request->filled('category')) {
                $priceRecord->item->category = $request->category;
            }
            if ($request->has('image_url')) {
                $priceRecord->item->image_url = $request->image_url;
            }
            $priceRecord->item->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المنتج والسعر بنجاح',
        ], 200);
    }


    public function deleteProduct($id)
    {
        $priceRecord = Price::find($id);

        if (! $priceRecord) {
            return response()->json([
                'status' => 'error',
                'message' => 'المنتج/السعر غير موجود',
            ], 404);
        }

        $priceRecord->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف سجل السعر بنجاح',
        ], 200);
    }

    public function globalSearch(Request $request)
    {
        $query = $request->get('query');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'status' => 'success',
                'results' => [],
            ]);
        }

        // 1. البحث في المنتجات (Items)
        $items = Item::where('name', 'LIKE', "%{$query}%")
            ->orWhere('category', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->name,
                    'subtitle' => 'الفئة: '.($item->category ?? 'غير مصنف'),
                    'type' => 'منتج',
                    'link' => '/items',
                ];
            });

        // 2. البحث في المتاجر (Stores)
        $stores = Store::where('name', 'LIKE', "%{$query}%")
            ->orWhere('phone', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($store) {
                return [
                    'title' => $store->name,
                    'subtitle' => 'الهاتف: '.($store->phone ?? 'غير مدرج'),
                    'type' => 'متجر',
                    'link' => '/stores',
                ];
            });

        // 3. البحث في المقالات (Articles)
        $articles = Article::where('title', 'LIKE', "%{$query}%")
            ->orWhere('content', 'LIKE', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(function ($article) {
                return [
                    'title' => $article->title,
                    'subtitle' => 'مقالة منشورة',
                    'type' => 'مقالة',
                    'link' => '/articles',
                ];
            });

        // دمج كل النتائج في مصفوفة واحدة مرتبة
        $mergedResults = array_merge(
            $items->toArray(),
            $stores->toArray(),
            $articles->toArray()
        );

        return response()->json([
            'status' => 'success',
            'results' => $mergedResults,
        ], 200);
    }
}

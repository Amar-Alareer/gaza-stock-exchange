<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    // 1. عرض الجميع مع البحث والفلترة
    public function index(Request $request)
    {
        $query = Item::with(['prices.store', 'categoryRelation'])
            ->withCount('prices')
            ->withMin('prices', 'price');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        } elseif ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->get()->map(function ($item) {
            $item->category_name = $item->categoryRelation?->name ?? $item->category ?? null;
            $item->category_image = $item->categoryRelation?->image ?? null;
            return $item;
        });

        return response()->json($items, 200);
    }

    // 2. إضافة صنف جديد
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'category'    => 'nullable|string',
            'image_url'   => 'nullable|string',
            'min_price'   => 'nullable|numeric|min:0',
        ]);

        $categoryId = $request->category_id;
        $categoryName = $request->category;

        if (!$categoryId && $request->filled('category')) {
            $trimmed = trim($request->category);
            $categoryObj = Category::firstOrCreate(
                ['name' => $trimmed],
                ['slug' => Str::slug($trimmed) ?: 'category']
            );
            $categoryId = $categoryObj->id;
            $categoryName = $categoryObj->name;
        } elseif ($categoryId && !$categoryName) {
            $categoryName = Category::find($categoryId)?->name;
        }

        $item = Item::create([
            'name'        => $request->name,
            'category'    => $categoryName,
            'category_id' => $categoryId,
            'image_url'   => $request->image_url,
            'min_price'   => $request->min_price,
        ]);

        $item->load('categoryRelation');
        $item->category_name = $item->categoryRelation?->name ?? $item->category;

        return response()->json($item, 201);
    }

    // 3. تعديل صنف
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name'        => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'category'    => 'nullable|string',
            'image_url'   => 'nullable|string',
            'min_price'   => 'nullable|numeric|min:0',
        ]);

        $categoryId = $request->category_id ?: $item->category_id;
        $categoryName = $request->category;

        if ($request->filled('category') && $item->category !== trim($request->category)) {
            $trimmed = trim($request->category);
            $categoryObj = Category::firstOrCreate(
                ['name' => $trimmed],
                ['slug' => Str::slug($trimmed) ?: 'category']
            );
            $categoryId = $categoryObj->id;
            $categoryName = $categoryObj->name;
        } elseif ($categoryId && !$categoryName) {
            $categoryName = Category::find($categoryId)?->name;
        }

        $item->update([
            'name'        => $request->name,
            'category'    => $categoryName,
            'category_id' => $categoryId,
            'image_url'   => $request->image_url,
            'min_price'   => $request->min_price,
        ]);

        $item->load('categoryRelation');
        $item->category_name = $item->categoryRelation?->name ?? $item->category;

        return response()->json($item, 200);
    }

    // 4. حذف صنف
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        $item->delete();

        return response()->json(['message' => 'تم الحذف بنجاح'], 200);
    }

    // 5. حذف عدة أصناف
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:items,id',
        ]);

        Item::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'تم حذف الأصناف بنجاح'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 1. عرض الجميع (مع البحث والفلترة حسب الكاتيجوري + أرخص سعر)
    public function index(Request $request)
    {
        $query = Item::with(['category', 'cheapestPrice.store']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // فلترة حسب category_id (الطريقة الجديدة)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // توافق مع الاستدعاء القديم لو حدا لسا عم يبعت اسم الكاتيجوري
        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        if ($request->filled('sort') && $request->sort === 'latest') {
            $query->latest();
        }

        if ($request->filled('limit')) {
            $query->limit((int) $request->limit);
        }

        return $query->get();
    }

    public function show($id)
    {
        $item = Item::with(['category', 'prices.store'])->find($id);

        if (! $item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        return $item;
    }

    /**
     * مقارنة أسعار صنف معين بين كل المحلات (مرتبة من الأرخص للأغلى)
     */
    public function prices($id)
    {
        $item = Item::find($id);

        if (! $item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        $prices = $item->prices()
            ->with('store:id,name,governorate,sub_area,image')
            ->orderBy('price')
            ->get();

        return response()->json([
            'item' => $item->only('id', 'name'),
            'prices' => $prices,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $item = Item::create([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);

        return response()->json($item->load('category'), 201);
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (! $item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $item->update([
            'name'        => $request->name,
            'category_id' => $request->category_id,
        ]);

        return response()->json($item->load('category'));
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (! $item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}

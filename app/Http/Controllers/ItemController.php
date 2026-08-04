<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 1. عرض الجميع مع البحث والفلترة
    public function index(Request $request)
    {
        $query = Item::with(['prices.store'])
            ->withCount('prices')
            ->withMin('prices', 'price');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return response()->json($query->get(), 200);
    }

    // 2. إضافة صنف جديد
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'category' => 'required|string',
        ]);

        $item = Item::create([
            'name'      => $request->name,
            'category'  => $request->category,
            'image_url' => $request->image_url,
            'min_price' => $request->min_price,
        ]);

        return response()->json($item, 201);
    }

    // 3. تعديل صنف
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name'     => 'required|string',
            'category' => 'required|string',
        ]);

        $item->update([
            'name'      => $request->name,
            'category'  => $request->category,
            'image_url' => $request->image_url,
            'min_price' => $request->min_price,
        ]);

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

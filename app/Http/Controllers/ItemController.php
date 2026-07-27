<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 1. عرض الجميع (مع البحث والفلترة)
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->category) {
            $query->where('category', $request->category);
        }


        return $query->get();
    }


    public function store(Request $request)
    {
        $item = Item::create([
            'name'     => $request->name,
            'category' => $request->category,
        ]);

        return response()->json($item, 201);
    }



    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        $item->update([
            'name'     => $request->name,
            'category' => $request->category,
        ]);

        return response()->json($item);
    }


    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'الصنف غير موجود'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}

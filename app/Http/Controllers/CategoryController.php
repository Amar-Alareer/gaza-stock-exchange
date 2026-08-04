<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * عرض كل الكاتيجوريز (عام - يستخدمها الموقع الرئيسي بالفلاتر)
     */
    public function index()
    {
        return Category::withCount('items')->orderBy('name')->get();
    }

    public function show($id)
    {
        $category = Category::withCount('items')->find($id);

        if (! $category) {
            return response()->json(['message' => 'الكاتيجوري غير موجودة'], 404);
        }

        return $category;
    }

    /**
     * إضافة كاتيجوري جديدة (محمي - أدمن فقط)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'icon' => 'nullable|string|max:10',
        ]);

        $category = Category::create($request->only('name', 'icon'));

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json(['message' => 'الكاتيجوري غير موجودة'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string|max:10',
        ]);

        $category->update($request->only('name', 'icon'));

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        if (! $category) {
            return response()->json(['message' => 'الكاتيجوري غير موجودة'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'تم الحذف بنجاح']);
    }
}

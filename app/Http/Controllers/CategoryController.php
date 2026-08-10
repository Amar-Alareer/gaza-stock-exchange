<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('items')->get();
        return response()->json($categories, 200);
    }


    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($request->name);

        $category = Category::create($data);

        return response()->json([
            'message'  => 'تم إضافة التصنيف بنجاح',
            'category' => $category
        ], 201);
    }


    public function show($id)
    {
        $category = Category::with('items')->find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        return response()->json($category, 200);
    }


    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        $data = $request->validated();

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($request->name);
        }

        $category->update($data);

        return response()->json([
            'message'  => 'تم تعديل التصنيف بنجاح',
            'category' => $category
        ], 200);
    }

   
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'التصنيف غير موجود'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'تم حذف التصنيف بنجاح'], 200);
    }
}

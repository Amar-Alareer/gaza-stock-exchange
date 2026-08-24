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
        $categories = Category::withCount('items')->with('items')->orderBy('id', 'desc')->get();
        return response()->json($categories, 200);
    }


    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        
        $baseSlug = Str::slug($request->name);
        if (empty($baseSlug)) {
            $baseSlug = 'category';
        }
        $slug = $baseSlug;
        $counter = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        if (!isset($data['is_active'])) {
            $data['is_active'] = true;
        }

        $category = Category::create($data);
        $category->loadCount('items');

        return response()->json([
            'message'  => 'تم إضافة التصنيف بنجاح',
            'category' => $category
        ], 201);
    }


    public function show($id)
    {
        $category = Category::withCount('items')->with('items')->find($id);

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
            $baseSlug = Str::slug($request->name);
            if (empty($baseSlug)) {
                $baseSlug = 'category';
            }
            $slug = $baseSlug;
            $counter = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $data['slug'] = $slug;
        }

        $category->update($data);
        $category->loadCount('items');

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

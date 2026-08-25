<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\Price;
use App\Models\Region;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * جلب جميع المتاجر مع المنطقة والصورة
     */
    public function index(Request $request)
    {
        $query = Store::with('region');

        // فلترة حسب المنطقة
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // بحث نصي
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        $stores = $query->orderBy('created_at', 'desc')->get()->map(function ($store) {
            return $this->formatStore($store);
        });

        $regions = Region::orderBy('area_name')->get(['id', 'city_or_governorate', 'area_name']);

        return response()->json([
            'status' => 'success',
            'stores' => $stores,
            'regions' => $regions,
        ]);
    }

    /**
     * إضافة متجر جديد مع صورة اختيارية
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
            'address' => 'nullable|string|max:500',
            'governorate' => 'nullable|string|max:100',
            'sub_area' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:20',
            'working_hours' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'telegram_url' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'اسم المتجر مطلوب',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت',
            'cover_image.image' => 'ملف الغلاف يجب أن يكون صورة',
            'cover_image.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 5 ميجابايت',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stores', 'public');
        }

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('stores', 'public');
        }

        $regionId = $request->filled('region_id') ? $request->region_id : Region::value('id');

        $store = Store::create([
            'name' => $request->name,
            'region_id' => $regionId,
            'address' => $request->address,
            'governorate' => $request->governorate,
            'sub_area' => $request->sub_area,
            'phone' => $request->phone,
            'working_hours' => $request->working_hours,
            'facebook_url' => $request->facebook_url,
            'instagram_url' => $request->instagram_url,
            'telegram_url' => $request->telegram_url,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'image' => $imagePath,
            'cover_image' => $coverImagePath,
        ]);

        $store->load('region');

        return response()->json([
            'status' => 'success',
            'message' => 'تم إضافة المتجر بنجاح',
            'store' => $this->formatStore($store),
        ], 201);
    }

    /**
     * تعديل بيانات متجر موجود
     */
    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        if (! $store) {
            return response()->json(['status' => 'error', 'message' => 'المتجر غير موجود'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'nullable|exists:regions,id',
            'address' => 'nullable|string|max:500',
            'governorate' => 'nullable|string|max:100',
            'sub_area' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:20',
            'working_hours' => 'nullable|string|max:255',
            'facebook_url' => 'nullable|string|max:255',
            'instagram_url' => 'nullable|string|max:255',
            'telegram_url' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'name.required' => 'اسم المتجر مطلوب',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 3 ميجابايت',
            'cover_image.image' => 'ملف الغلاف يجب أن يكون صورة',
            'cover_image.max' => 'حجم صورة الغلاف يجب ألا يتجاوز 5 ميجابايت',
        ]);

        // رفع صورة جديدة وحذف القديمة إن وجدت
        if ($request->hasFile('image')) {
            if ($store->image) {
                Storage::disk('public')->delete($store->image);
            }
            $store->image = $request->file('image')->store('stores', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($store->cover_image) {
                Storage::disk('public')->delete($store->cover_image);
            }
            $store->cover_image = $request->file('cover_image')->store('stores', 'public');
        }

        $store->name = $request->name;
        $store->region_id = $request->filled('region_id') ? $request->region_id : ($store->region_id ?: Region::value('id'));
        $store->address = $request->address;
        if ($request->has('governorate')) $store->governorate = $request->governorate;
        if ($request->has('sub_area')) $store->sub_area = $request->sub_area;
        $store->phone = $request->phone;
        $store->working_hours = $request->working_hours;
        $store->facebook_url = $request->facebook_url;
        $store->instagram_url = $request->instagram_url;
        $store->telegram_url = $request->telegram_url;
        $store->latitude = $request->latitude;
        $store->longitude = $request->longitude;
        $store->save();

        $store->load('region');

        return response()->json([
            'status' => 'success',
            'message' => 'تم تحديث المتجر بنجاح',
            'store' => $this->formatStore($store),
        ]);
    }

    /**
     * جلب بيانات متجر محدد مع منتجاته
     */
    public function show($id)
    {
        $store = Store::with(['region', 'prices.item'])->find($id);

        if (! $store) {
            return response()->json(['status' => 'error', 'message' => 'المتجر غير موجود'], 404);
        }

        // تنسيق المنتجات
        $products = $store->prices->map(function ($price) {
            return [
                'id' => $price->id,
                'item_id' => $price->item_id,
                'name' => $price->item->name ?? 'غير معروف',
                'category' => $price->item->category ?? 'غير محدد',
                'price' => $price->price,
                'image_url' => $price->item->image_url ?? null,
                'updated_at' => $price->updated_at?->diffForHumans() ?? 'غير معروف',
            ];
        });


        $regions = Region::orderBy('area_name')->get(['id', 'city_or_governorate', 'area_name']);

        $all_categories = Category::pluck('name')->merge(Item::distinct()->pluck('category'))->filter()->unique()->values();

        return response()->json([
            'status' => 'success',
            'store' => $this->formatStore($store),
            'products' => $products,
            'regions' => $regions,
            'all_categories' => $all_categories,
        ]);
    }

    /**
     * حذف متجر وصورته
     */
    public function destroy($id)
    {
        $store = Store::find($id);
        if (! $store) {
            return response()->json(['status' => 'error', 'message' => 'المتجر غير موجود'], 404);
        }

        // حذف الصورة من الـ storage
        if ($store->image) {
            Storage::disk('public')->delete($store->image);
        }

        $store->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'تم حذف المتجر بنجاح',
        ]);
    }

    /**
     * إضافة منتج جديد أو ربطه بالمتجر مع تحديد السعر
     */
    public function addProduct(Request $request, $id)
    {
        $store = Store::find($id);
        if (!$store) {
            return response()->json(['status' => 'error', 'message' => 'المتجر غير موجود'], 404);
        }

        $request->validate([
            'name' => 'required|string|min:2',
            'category' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'image_url' => 'nullable|string',
        ], [
            'name.required' => 'اسم الصنف مطلوب',
            'name.min' => 'اسم الصنف يجب أن يكون حرفين على الأقل',
            'category.required' => 'فئة الصنف مطلوبة',
        ]);

        // 1. الحصول على التصنيف لربط category_id
        $categoryName = trim($request->category);
        $categoryObj = \App\Models\Category::firstOrCreate(
            ['name' => $categoryName],
            ['slug' => \Illuminate\Support\Str::slug($categoryName)]
        );

        // 2. البحث عن المنتج أو إنشائه
        $item = Item::firstOrCreate(
            ['name' => trim($request->name)],
            [
                'category'    => $categoryName,
                'category_id' => $categoryObj->id,
                'image_url'   => $request->image_url ? trim($request->image_url) : null,
                'min_price'   => $request->price ? floatval($request->price) : null,
            ]
        );

        // تحديث بيانات المنتج إذا كانت الفئة أو الصورة قد أضيفت
        $updated = false;
        if (!$item->category_id || ($request->category && $item->category !== $categoryName)) {
            $item->category    = $categoryName;
            $item->category_id = $categoryObj->id;
            $updated = true;
        }
        if ($request->image_url && $item->image_url !== trim($request->image_url)) {
            $item->image_url = trim($request->image_url);
            $updated = true;
        }
        if ($request->price && (!$item->min_price || floatval($request->price) < $item->min_price)) {
            $item->min_price = floatval($request->price);
            $updated = true;
        }
        if ($updated) {
            $item->save();
        }

        // 2. تحديث أو إنشاء سعر هذا المنتج في المتجر
        $price = Price::updateOrCreate(
            [
                'store_id' => $store->id,
                'item_id' => $item->id,
            ],
            [
                'price' => $request->price ? floatval($request->price) : 0,
                'source' => $store->name,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'تم إضافة المنتج للمتجر بنجاح',
            'product' => [
                'id' => $price->id,
                'item_id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'price' => $price->price,
                'image_url' => $item->image_url,
                'updated_at' => $price->updated_at?->diffForHumans() ?? 'الآن',
            ],
        ], 201);
    }

    /**
     * تنسيق بيانات المتجر للإرجاع
     */
    private function formatStore(Store $store): array
    {
        $govLabels = [
            'شمال_غزة' => 'محافظة شمال غزة',
            'غزة' => 'محافظة غزة',
            'الوسطى' => 'محافظة الوسطى',
            'خان_يونس' => 'محافظة خان يونس',
            'رفح' => 'محافظة رفح',
        ];

        $regionLabel = null;
        if ($store->governorate) {
            $govName = $govLabels[$store->governorate] ?? $store->governorate;
            $regionLabel = $store->sub_area ? $govName.' - '.$store->sub_area : $govName;
        } elseif ($store->region) {
            $regionLabel = $store->region->area_name;
            if ($store->region->city_or_governorate) {
                $regionLabel = $store->region->city_or_governorate.' - '.$store->region->area_name;
            }
        }

        return [
            'id' => $store->id,
            'name' => $store->name,
            'phone' => $store->phone,
            'working_hours' => $store->working_hours,
            'region' => $regionLabel ?? 'غير محدد',
            'region_id' => $store->region_id,
            'address' => $store->address,
            'governorate' => $store->governorate,
            'sub_area' => $store->sub_area,
            'facebook_url' => $store->facebook_url,
            'instagram_url' => $store->instagram_url,
            'telegram_url' => $store->telegram_url,
            'latitude' => $store->latitude,
            'longitude' => $store->longitude,
            'image_url' => $store->image_url,
            'cover_image_url' => $store->cover_image_url,
            'created_at' => $store->created_at?->format('Y-m-d'),
        ];
    }
}


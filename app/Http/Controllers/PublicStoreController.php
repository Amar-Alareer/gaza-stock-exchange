<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\Store;
use Illuminate\Http\Request;

/**
 * كنترولر عام (بدون تسجيل دخول) لعرض المحلات بموقع "وفر كاش" الرئيسي.
 * منفصل عن Admin\StoreController الذي يبقى محميًا بـ auth:sanctum لعمليات الإدارة.
 */
class PublicStoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Store::with('region');

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('governorate')) {
            $query->where('governorate', $request->governorate);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sub_area', 'LIKE', "%{$search}%");
            });
        }

        $stores = $query->orderBy('created_at', 'desc')->get()->map(function ($store) {
            return [
                'id' => $store->id,
                'name' => $store->name,
                'governorate' => $store->governorate,
                'sub_area' => $store->sub_area,
                'address' => $store->address,
                'phone' => $store->phone,
                'working_hours' => $store->working_hours,
                'image_url' => $store->image_url,
                'cover_image_url' => $store->cover_image_url,
                'region' => $store->region,
            ];
        });

        return response()->json([
            'status' => 'success',
            'stores' => $stores,
            'regions' => Region::orderBy('area_name')->get(['id', 'city_or_governorate', 'area_name']),
        ]);
    }

    public function show($id)
    {
        $store = Store::with('region')->find($id);

        if (! $store) {
            return response()->json(['message' => 'المتجر غير موجود'], 404);
        }

        return response()->json($store);
    }
}

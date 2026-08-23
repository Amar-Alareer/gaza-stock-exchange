<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Item;
use App\Models\Store;
use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // الصفحة الرئيسية
    public function index()
    {
        $products = Item::latest('updated_at')->take(5)->get();
        return view('index', compact('products'));
    }

    // تحميل المزيد من المنتجات (AJAX)
    public function loadMore(Request $request)
    {
        $offset = (int) $request->get('offset', 5);
        $products = Item::latest('updated_at')->skip($offset)->take(5)->get();

        $products->transform(function ($product) {
            $product->formatted_updated_at = $product->updated_at->locale('ar')->diffForHumans();
            $product->detail_url = route('products.show', $product->id);
            return $product;
        });

        return response()->json($products);
    }

    // عرض تفاصيل منتج معين
    public function show($id)
    {
        $product = Item::findOrFail($id);
        return view('shop-details', compact('product'));
    }

    // صفحة قائمة الأسعار
    public function prices()
    {
        $products = Item::latest('updated_at')->get();
        return view('prices', compact('products'));
    }

    // صفحة المتاجر / المحلات
    public function shops()
    {
        $stores = Store::with('region')->get();
        return view('shops', compact('stores'));
    }
    public function shop_details()
    {
        $stores = Store::with('region')->get();
        return view('shop-details', compact('stores'));
    }

    // صفحة الخريطة التفاعلية
    public function map()
    {
        $stores = Store::select('id', 'name', 'latitude', 'longitude', 'address')->get();
        return view('map', compact('stores'));
    }

    // صفحة مقارنة الأسعار
    public function compare()
    {
        $items = Item::with('prices.store')->get();
        return view('compare', compact('items'));
    }
}
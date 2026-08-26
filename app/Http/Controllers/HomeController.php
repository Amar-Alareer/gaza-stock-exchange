<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Item;
use App\Models\Price;
use App\Models\Region;
use App\Models\Store;
use App\Models\UserFavorite;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // الصفحة الرئيسية
    public function index()
    {
        $products = Item::with(['prices.store', 'categoryRelation'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        $categories = Category::withCount('items')
            ->with(['items' => function ($q) {
                $q->select('id', 'category_id', 'min_price', 'name')->orderBy('min_price', 'asc');
            }])
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($categories->isEmpty()) {
            $categories = Category::withCount('items')
                ->with(['items' => function ($q) {
                    $q->select('id', 'category_id', 'min_price', 'name')->orderBy('min_price', 'asc');
                }])
                ->orderBy('id')
                ->get();
        }

        return view('index', compact('products', 'categories'));
    }

    // تحميل المزيد من المنتجات (AJAX)
    public function loadMore(Request $request)
    {
        $offset = (int) $request->get('offset', 5);
        $products = Item::with(['prices.store', 'categoryRelation'])
            ->latest('updated_at')
            ->skip($offset)
            ->take(5)
            ->get();

        $products->transform(function ($product) {
            $product->display_price = $product->best_price ? $product->best_price . ' شيكل' : 'غير محدد';
            $product->display_category = $product->category_name;
            $product->display_store = $product->best_store;
            $product->detail_url = route('products.show', $product->id);
            return $product;
        });

        return response()->json($products);
    }

    // عرض تفاصيل منتج معين أو متجر
    public function show($id)
    {
        $store = Store::with(['region', 'prices.item.categoryRelation'])->find($id);
        if ($store) {
            return $this->shop_details($id);
        }

        $product = Item::with(['prices.store', 'categoryRelation'])->find($id);
        if ($product) {
            return redirect()->route('compare', ['item_id' => $product->id]);
        }

        return redirect()->route('shops');
    }

    // صفحة تفاصيل المحل والسلع الخاصة به
    public function shop_details($id = null)
    {
        if ($id) {
            $store = Store::with(['region', 'prices.item.categoryRelation'])->find($id);
            if (!$store) {
                $store = Store::with(['region', 'prices.item.categoryRelation'])->first();
            }
        } else {
            $store = Store::with(['region', 'prices.item.categoryRelation'])->first();
        }

        if (!$store) {
            return redirect()->route('shops');
        }

        $storePrices = $store->prices()->with(['item.categoryRelation'])->get();
        $allStores = Store::with('region')->get();
        $categories = Category::where('is_active', 1)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('id')->get();
        }

        return view('shop-details', compact('store', 'storePrices', 'allStores', 'categories'));
    }

    // صفحة قائمة الأسعار
    public function prices(Request $request)
    {
        $query = Item::with(['prices.store', 'categoryRelation'])->latest('updated_at');

        if ($request->filled('store_id')) {
            $storeId = (int) $request->store_id;
            $query->whereHas('prices', fn ($p) => $p->where('store_id', $storeId));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', 'like', '%' . $search . '%'))
                  ->orWhereHas('prices.store', fn ($s) => $s->where('name', 'like', '%' . $search . '%'));
            });
        }

        if ($request->filled('category')) {
            $category = trim($request->category);
            $query->where(function ($q) use ($category) {
                $q->where('category', $category)
                  ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', $category));
            });
        }

        $products = $query->get();
        $selectedStore = $request->filled('store_id') ? Store::find($request->store_id) : null;
        $categories = Category::withCount('items')
            ->where('is_active', 1)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        if ($categories->isEmpty()) {
            $categories = Category::withCount('items')->orderBy('id')->get();
        }

        return view('prices', compact('products', 'categories', 'selectedStore'));
    }

    // صفحة المتاجر / المحلات
    public function shops(Request $request)
    {
        $query = Store::with(['region', 'prices.item.categoryRelation'])->withCount('prices');

        // Filter by Governorate / City
        if ($request->filled('city')) {
            $city = trim($request->city);
            $query->whereHas('region', fn ($r) => $r->where('city_or_governorate', $city));
        }

        // Filter by Area / Camp / Region ID
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        // Filter by Category
        if ($request->filled('category')) {
            $category = trim($request->category);
            $query->whereHas('prices.item', function ($q) use ($category) {
                $q->where('category', $category)
                  ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', $category));
            });
        }

        // Search across store name, address, phone, region, governorate, AND products sold
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhereHas('region', fn ($r) => $r->where('area_name', 'like', '%' . $search . '%')->orWhere('city_or_governorate', 'like', '%' . $search . '%'))
                  ->orWhereHas('prices.item', fn ($i) => $i->where('name', 'like', '%' . $search . '%'));
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'products_desc') {
            $query->orderBy('prices_count', 'desc');
        } else {
            $query->latest('id');
        }

        $stores = $query->get();
        $regions = Region::orderBy('city_or_governorate')->get();
        $cities = Region::select('city_or_governorate')->distinct()->pluck('city_or_governorate');
        $categories = Category::withCount('items')->where('is_active', 1)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Category::withCount('items')->orderBy('id')->get();
        }

        // User favorite stores
        $userFavoriteStoreIds = collect();
        if (auth()->check()) {
            $userFavoriteStoreIds = UserFavorite::where('user_id', auth()->id())
                ->where('type', 'store')
                ->pluck('reference_id');
        }

        return view('shops', compact('stores', 'regions', 'cities', 'categories', 'userFavoriteStoreIds'));
    }

    // صفحة الخريطة التفاعلية
    public function map(Request $request)
    {
        $query = Store::with(['region', 'prices.item'])->whereNotNull('latitude')->whereNotNull('longitude');

        if ($request->filled('city')) {
            $city = trim($request->city);
            $query->whereHas('region', fn ($r) => $r->where('city_or_governorate', $city));
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%')
                  ->orWhereHas('region', fn ($r) => $r->where('area_name', 'like', '%' . $search . '%')->orWhere('city_or_governorate', 'like', '%' . $search . '%'));
            });
        }

        $stores = $query->get();
        $allStores = Store::with(['region', 'prices.item'])->get();
        $regions = Region::orderBy('city_or_governorate')->get();
        $cities = Region::select('city_or_governorate')->distinct()->pluck('city_or_governorate');
        $categories = Category::where('is_active', 1)->orderBy('sort_order')->get();

        return view('map', compact('stores', 'allStores', 'regions', 'cities', 'categories'));
    }

    // صفحة مقارنة الأسعار
    public function compare(Request $request)
    {
        $categories = Category::where('is_active', 1)->orderBy('sort_order')->get();
        if ($categories->isEmpty()) {
            $categories = Category::orderBy('id')->get();
        }

        $regions = Region::orderBy('city_or_governorate')->get();
        $cities = Region::select('city_or_governorate')->distinct()->pluck('city_or_governorate');

        // All items for comparison selection
        $allItems = Item::with(['prices.store.region', 'categoryRelation'])->orderBy('name')->get();

        // Selected item mode: can be 'all', null, or a specific item ID
        $selectedItemId = $request->input('item_id');
        $isAllMode = ($selectedItemId === 'all');
        $selectedItem = null;

        if (!$isAllMode && $selectedItemId && is_numeric($selectedItemId)) {
            $selectedItem = $allItems->firstWhere('id', (int) $selectedItemId);
        }

        if (!$isAllMode && !$selectedItem && $request->filled('category')) {
            $cat = trim($request->category);
            $selectedItem = $allItems->first(function ($item) use ($cat) {
                return $item->category === $cat || ($item->categoryRelation && $item->categoryRelation->name === $cat);
            });
        }

        if (!$isAllMode && !$selectedItem && $request->filled('search')) {
            $search = trim($request->search);
            $selectedItem = $allItems->first(function ($item) use ($search) {
                return str_contains($item->name, $search) || str_contains($item->category_name, $search);
            });
        }

        if (!$isAllMode && !$selectedItem) {
            // Default to the first item that has prices recorded so page loads with rich comparison data
            $selectedItem = $allItems->first(fn ($i) => $i->prices->isNotEmpty()) ?? $allItems->first();
        }

        // Secondary item for side-by-side comparison
        $compareItemId = $request->input('compare_with_id');
        $compareItem = null;
        if ($compareItemId && is_numeric($compareItemId)) {
            $compareItem = $allItems->firstWhere('id', (int) $compareItemId);
        }

        // Specific Item Prices across stores
        $itemPrices = collect();
        if ($selectedItem) {
            $pricesQuery = Price::with(['store.region', 'item'])
                ->where('item_id', $selectedItem->id)
                ->whereHas('store');

            if ($request->filled('city')) {
                $city = trim($request->city);
                $pricesQuery->whereHas('store.region', fn ($r) => $r->where('city_or_governorate', $city));
            }

            if ($request->filled('region_id')) {
                $pricesQuery->whereHas('store', fn ($s) => $s->where('region_id', $request->region_id));
            }

            $itemPrices = $pricesQuery->orderBy('price', 'asc')->get();
        }

        // Stores query for All-Mode & Map
        $storesQuery = Store::with(['region', 'prices.item.categoryRelation']);

        if ($request->filled('city')) {
            $city = trim($request->city);
            $storesQuery->whereHas('region', fn ($r) => $r->where('city_or_governorate', $city));
        }

        if ($request->filled('region_id')) {
            $storesQuery->where('region_id', $request->region_id);
        }

        if ($request->filled('category')) {
            $catFilter = trim($request->category);
            $storesQuery->whereHas('prices.item', function ($q) use ($catFilter) {
                $q->where('category', $catFilter)
                  ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', $catFilter));
            });
        }

        $storesList = $storesQuery->get();

        // Calculate for each store: Cheapest item, best category, etc.
        $storeAnalyses = $storesList->map(function ($store) use ($request) {
            $prices = $store->prices->filter(fn ($p) => $p->item && $p->price > 0);

            if ($request->filled('category')) {
                $cat = trim($request->category);
                $prices = $prices->filter(fn ($p) => $p->item->category === $cat || ($p->item->categoryRelation && $p->item->categoryRelation->name === $cat));
            }

            $cheapestPriceRecord = $prices->sortBy('price')->first();

            // Find best category by lowest average price or highest discount
            $groupedByCat = $prices->groupBy(fn ($p) => $p->item->category_name);
            $cheapestCat = null;
            $lowestCatAvg = 999999;

            foreach ($groupedByCat as $catName => $catPrices) {
                $avg = $catPrices->avg('price');
                if ($avg < $lowestCatAvg) {
                    $lowestCatAvg = $avg;
                    $cheapestCat = $catName;
                }
            }

            return [
                'store'            => $store,
                'prices_count'     => $prices->count(),
                'cheapest_item'    => $cheapestPriceRecord?->item?->name ?? 'سلع متنوعة',
                'cheapest_price'   => $cheapestPriceRecord?->price ?? null,
                'cheapest_category'=> $cheapestCat ?? ($store->prices->isNotEmpty() ? $store->prices->first()->item?->category_name : 'عام'),
            ];
        });

        // Stores for Leaflet map
        $mapStores = Store::with(['region', 'prices.item'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('compare', compact(
            'allItems',
            'categories',
            'regions',
            'cities',
            'selectedItem',
            'compareItem',
            'itemPrices',
            'mapStores',
            'storeAnalyses',
            'isAllMode'
        ));
    }

    // البحث الفوري في النافبار (Live Search AJAX)
    public function liveSearch(Request $request)
    {
        $query = trim($request->input('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['items' => [], 'stores' => []]);
        }

        // Search items/products
        $items = Item::where('name', 'like', '%' . $query . '%')
            ->orWhere('category', 'like', '%' . $query . '%')
            ->orWhereHas('categoryRelation', fn ($c) => $c->where('name', 'like', '%' . $query . '%'))
            ->limit(5)
            ->get()
            ->map(fn ($item) => [
                'id'       => $item->id,
                'name'     => $item->name,
                'category' => $item->category_name,
                'price'    => $item->best_price ? number_format($item->best_price, 2) . ' ₪' : null,
                'url'      => route('products.show', $item->id),
            ]);

        // Search stores
        $stores = Store::with('region')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('address', 'like', '%' . $query . '%')
            ->orWhereHas('region', fn ($r) => $r->where('area_name', 'like', '%' . $query . '%')->orWhere('city_or_governorate', 'like', '%' . $query . '%'))
            ->limit(4)
            ->get()
            ->map(fn ($store) => [
                'id'     => $store->id,
                'name'   => $store->name,
                'region' => $store->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store->address ?? 'غزة'),
                'url'    => route('shop-details.show', $store->id),
            ]);

        return response()->json([
            'items'     => $items,
            'stores'    => $stores,
            'all_url'   => route('prices', ['search' => $query]),
        ]);
    }
}
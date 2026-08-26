<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | دليل المحلات</title>
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=8') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/imges/logo.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    .store-card-full {
      background: #fff;
      border-radius: 20px;
      border: 1.5px solid #f1f5f9;
      box-shadow: 0 4px 18px rgba(0,0,0,0.05);
      transition: all 0.25s ease;
    }
    .store-card-full:hover {
      transform: translateY(-4px);
      box-shadow: 0 10px 28px rgba(22, 163, 74, 0.12);
      border-color: #86efac;
    }
    .fav-heart-btn {
      background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 50%;
      width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
      color: #cbd5e1; transition: all 0.2s; cursor: pointer;
    }
    .fav-heart-btn.active { color: #ef4444; background: #fef2f2; border-color: #fecaca; }
    .fav-heart-btn:hover { transform: scale(1.15); color: #ef4444; }
    .category-pill-btn {
      border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
      border-radius: 30px; padding: 6px 16px; font-weight: 700; font-size: 0.85rem;
      text-decoration: none; display: inline-flex; align-items: center; gap: 6px;
      transition: all 0.2s;
    }
    .category-pill-btn:hover, .category-pill-btn.active {
      background: #16a34a; color: #fff; border-color: #16a34a;
    }
    .store-distance-badge {
      display: inline-flex; align-items: center; gap: 4px;
      background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;
      border-radius: 8px; padding: 2px 8px; font-weight: 700; font-size: 0.75rem;
    }
    .store-action-btn {
      font-weight: 700; font-size: 0.84rem; border-radius: 12px;
      padding: 8px 12px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
      text-decoration: none; transition: all 0.2s;
    }
    #modal-store-map {
      height: 340px;
      width: 100%;
      border-radius: 16px;
      z-index: 1;
    }
    .custom-store-pin {
      background: #16a34a; border: 2.5px solid #fff; border-radius: 50%;
      width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;
      color: #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.25); font-size: 1.1rem;
    }
    .user-location-pin {
      background: #2563eb; border: 3px solid #fff; border-radius: 50%;
      width: 22px; height: 22px; box-shadow: 0 0 0 6px rgba(37,99,235,0.25);
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-wafar">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">

        <div class="brand-logo order-1">
          <a href="{{ route('index') }}" class="d-flex align-items-center gap-2">
            <img src="{{ asset('assets/imges/logo.png') }}" alt="وفر كاش" class="logo-icon">
            <span class="logo-text d-none d-sm-block">
              <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
              <span class="en">CASH WAFAR</span>
            </span>
          </a>
        </div>

        <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('map') }}">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <form action="{{ route('prices') }}" method="GET" class="search-pill m-0">
            <input type="text" name="search" placeholder="طحين ، سكر">
            <button type="submit">
              <i class="bi bi-search"></i>
            </button>
          </form>

          <a href="{{ route('compare') }}" class="btn-compare">
            <i class="bi bi-arrow-left-right"></i>
            مقارنة الاسعار
          </a>

          @if(Auth::check())
            <a href="{{ route('profile') }}" class="d-flex align-items-center text-decoration-none" title="الملف الشخصي">
              <img src="{{ Auth::user()->profile_picture_url }}" alt="{{ Auth::user()->name }}" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 2px solid var(--brand-green);">
            </a>
          @else
            <div class="d-flex align-items-center gap-2">
              <a href="{{ route('login') }}" class="btn-nav-login">
                <i class="bi bi-box-arrow-in-left"></i> دخول
              </a>
              <a href="{{ route('signup') }}" class="btn-nav-signup">
                <i class="bi bi-person-plus"></i> حساب جديد
              </a>
            </div>
          @endif
        </div>

        <div class="d-flex d-lg-none align-items-center gap-2 order-3">
          @if(Auth::check())
            <a href="{{ route('profile') }}" class="d-flex align-items-center text-decoration-none">
              <img src="{{ Auth::user()->profile_picture_url }}" alt="{{ Auth::user()->name }}" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid var(--brand-green);">
            </a>
          @else
            <a href="{{ route('login') }}" class="btn-nav-login py-1 px-2" style="font-size:0.8rem;">
              دخول
            </a>
          @endif
          <button class="navbar-toggler-custom" type="button" aria-label="القائمة">
            <i class="bi bi-list fs-4"></i>
          </button>
        </div>
      </div>

      <!-- MOBILE SECONDARY ROW -->
      <div class="d-flex d-lg-none align-items-center gap-2 mt-2">
        <form action="{{ route('prices') }}" method="GET" class="search-pill flex-grow-1 m-0">
          <input type="text" name="search" placeholder="طحين ، سكر">
          <button type="submit"><i class="bi bi-search"></i></button>
        </form>
        <a href="{{ route('compare') }}" class="btn-compare"><i class="bi bi-arrow-left-right me-1"></i> مقارنة</a>
      </div>
    </div>

    <div class="mobile-nav-panel">
      <a class="nav-link" href="{{ route('index') }}">
        <i class="bi bi-house-door-fill"></i> الرئيسية
      </a>
      <a class="nav-link" href="{{ route('map') }}">
        <i class="bi bi-map-fill"></i> الخريطة التفاعلية
      </a>
      <a class="nav-link active" href="{{ route('shops') }}">
        <i class="bi bi-shop"></i> دليل المحلات
      </a>
      <a class="nav-link" href="{{ route('prices') }}">
        <i class="bi bi-tag-fill"></i> قائمة الأسعار
      </a>
      <a class="nav-link" href="{{ route('compare') }}">
        <i class="bi bi-arrow-left-right"></i> مقارنة الأسعار
      </a>
      @if(Auth::check())
        <a class="nav-link" href="{{ route('profile') }}">
          <i class="bi bi-person-circle"></i> الملف الشخصي
        </a>
      @else
        <div class="mobile-nav-auth-card d-flex flex-column gap-2 mt-auto">
          <a href="{{ route('login') }}" class="btn btn-outline-light w-100 rounded-pill fw-bold py-2">
            <i class="bi bi-box-arrow-in-left me-1"></i> تسجيل دخول
          </a>
          <a href="{{ route('signup') }}" class="btn btn-success w-100 rounded-pill fw-bold py-2" style="background:#24df64;color:#0b2516;border:none;">
            <i class="bi bi-person-plus-fill me-1"></i> إنشاء حساب جديد
          </a>
        </div>
      @endif
    </div>
  </nav>

  <!-- HERO HEADER BANNER -->
  <section class="shops-header-banner">
    <img src="{{ asset('assets/imges/map.png') }}" alt="المحلات" class="hero-bg-img">
    <div class="container d-flex align-items-center justify-content-between flex-wrap">
      <div class="shops-banner-text text-end">
        <h1 class="page-header-title mb-1"><span class="brand-green">دليل</span> <span class="brand-white">المحلات والمتاجر</span> <i class="bi bi-shop text-success fs-3"></i></h1>
        <p class="page-header-sub">ابحث عن المحلات المعتمدة في منطقتك، تصفح سلعها وقارن أسعارها</p>
      </div>
      <div class="shops-banner-graphic d-none d-md-block">
        <img src="{{ asset('assets/imges/shops.png') }}" alt="محلات" class="banner-store-img">
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- FILTER & SEARCH CARD -->
      <form action="{{ route('shops') }}" method="GET" id="shops-filter-form" class="filter-card-container mb-4">
        <div class="row g-3 align-items-end">

          <!-- Search Input -->
          <div class="col-12 col-md-4">
            <label class="form-label fw-bold small text-muted mb-1"><i class="bi bi-search me-1"></i> بحث شامل (اسم المحل، السلعة، رقم الهاتف)</label>
            <div class="search-input-wrapper">
              <input type="text" name="search" class="form-control modern-search-input" placeholder="ابحث باسم المحل أو سلعة تباع فيه..." value="{{ request('search') }}">
              <button type="submit" class="search-circle-btn" title="بحث">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </div>

          <!-- City / Governorate -->
          <div class="col-6 col-md-2">
            <label class="form-label fw-bold small text-muted mb-1">المحافظة</label>
            <select name="city" class="form-select border-1 rounded-3" id="shops-city-filter" onchange="document.getElementById('shops-filter-form').submit();">
              <option value="">كل المحافظات</option>
              @foreach($cities as $c)
                <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
              @endforeach
            </select>
          </div>

          <!-- Region / Camp -->
          <div class="col-6 col-md-3">
            <label class="form-label fw-bold small text-muted mb-1">الحي / المنطقة</label>
            <select name="region_id" class="form-select border-1 rounded-3" id="shops-region-filter" onchange="document.getElementById('shops-filter-form').submit();">
              <option value="">كل المناطق والأحياء</option>
              @foreach($regions as $r)
                <option value="{{ $r->id }}" data-city="{{ $r->city_or_governorate }}" {{ request('region_id') == $r->id ? 'selected' : '' }}>
                  {{ $r->city_or_governorate }} - {{ $r->area_name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Category Filter -->
          <div class="col-6 col-md-2">
            <label class="form-label fw-bold small text-muted mb-1">تصنيف السلع</label>
            <select name="category" class="form-select border-1 rounded-3" onchange="document.getElementById('shops-filter-form').submit();">
              <option value="">جميع التصنيفات</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
          </div>

          <!-- Sorting -->
          <div class="col-6 col-md-1">
            <label class="form-label fw-bold small text-muted mb-1">الترتيب</label>
            <select name="sort" class="form-select border-1 rounded-3" onchange="document.getElementById('shops-filter-form').submit();">
              <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
              <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>أ - ي</option>
              <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>الأكثر سلعاً</option>
            </select>
          </div>

        </div>
      </form>

      <!-- RESULTS HEADER -->
      <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
          <h5 class="fw-bold text-dark mb-1">
            <i class="bi bi-shop-window text-success me-2"></i>
            المحلات المتاحة: <span class="brand-green">{{ $stores->count() }} متجر</span>
          </h5>
          @if(request('search') || request('city') || request('region_id') || request('category'))
            <div class="small text-muted">
              نتائج الفلترة: 
              @if(request('search')) <span>تطابق "<strong>{{ request('search') }}</strong>"</span> @endif
              @if(request('city')) <span>في محافظة <strong>{{ request('city') }}</strong></span> @endif
              @if(request('category')) <span>قسم <strong>{{ request('category') }}</strong></span> @endif
            </div>
          @endif
        </div>

        @if(request('search') || request('city') || request('region_id') || request('category') || request('sort'))
          <a href="{{ route('shops') }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold d-flex align-items-center gap-1">
            <i class="bi bi-x-circle"></i> مسح جميع الفلاتر
          </a>
        @endif
      </div>

      <!-- SHOPS GRID -->
      <div class="row g-4 mb-5" id="shops-grid-container">
        @forelse($stores as $store)
          @php
            $storeImg = $store->image_url ?: asset('assets/imges/shops.png');
            $regionName = $store->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store->address ?? 'قطاع غزة');
            $isFav = $userFavoriteStoreIds->contains($store->id);
            // Collect unique category names from store prices
            $storeCategories = $store->prices->map(fn($p) => $p->item?->category_name)->filter()->unique()->take(3);
            
            $storeDataArray = [
              'id' => $store->id,
              'name' => $store->name,
              'image' => $storeImg,
              'region' => $regionName,
              'address' => $store->address ?? '',
              'phone' => $store->phone ?? '',
              'lat' => $store->latitude,
              'lng' => $store->longitude,
              'prices_count' => $store->prices_count,
              'details_url' => route('shop-details.show', $store->id),
              'prices_url' => route('prices', ['store_id' => $store->id]),
            ];
          @endphp
          <div class="col-12 col-md-6 col-lg-4 shop-grid-item"
               data-store-id="{{ $store->id }}"
               data-lat="{{ $store->latitude ?? '' }}"
               data-lng="{{ $store->longitude ?? '' }}">
            <div class="store-card-full p-3.5 p-md-4 h-100 d-flex flex-column justify-content-between">
              <div>
                <!-- Top Badge & Favorite Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5 rounded-pill fw-bold" style="font-size:0.78rem;">
                    <i class="bi bi-patch-check-fill text-success me-1"></i> محل موثوق
                  </span>
                  <button
                    type="button"
                    class="fav-heart-btn {{ $isFav ? 'active' : '' }}"
                    onclick="toggleStoreFavorite({{ $store->id }}, this)"
                    title="{{ $isFav ? 'إزالة من المفضلة' : 'حفظ في المفضلة' }}">
                    <i class="bi bi-heart-fill"></i>
                  </button>
                </div>

                <!-- Store Info & Avatar -->
                <div class="d-flex align-items-start gap-3 mb-3">
                  <a href="{{ route('shop-details.show', $store->id) }}">
                    <img src="{{ $storeImg }}" alt="{{ $store->name }}" class="rounded-circle shadow-sm"
                         style="width: 58px; height: 58px; object-fit: cover; border: 2.5px solid var(--brand-green);"
                         onerror="this.src='{{ asset('assets/imges/shops.png') }}'">
                  </a>
                  <div class="flex-grow-1">
                    <h5 class="fw-bold mb-1">
                      <a href="{{ route('shop-details.show', $store->id) }}" class="text-dark text-decoration-none hover-green">
                        {{ $store->name }}
                      </a>
                    </h5>
                    <div class="small text-muted mb-1">
                      <i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $regionName }}
                    </div>
                    <!-- Dynamic Distance Badge -->
                    <span class="store-distance-badge" id="store-dist-badge-{{ $store->id }}">
                      <i class="bi bi-compass"></i> قيد تحديد المسافة...
                    </span>
                  </div>
                </div>

                <!-- Store Meta & Phone -->
                <div class="p-2.5 bg-light rounded-3 mb-3 d-flex justify-content-between align-items-center small">
                  <div>
                    <span class="text-muted fw-bold">السلع المسجلة:</span>
                    <strong class="brand-green ms-1">{{ $store->prices_count }} سلعة</strong>
                  </div>
                  @if($store->phone)
                    <div>
                      <a href="tel:{{ $store->phone }}" class="text-decoration-none fw-bold text-success d-flex align-items-center gap-1">
                        <i class="bi bi-telephone-fill"></i> {{ $store->phone }}
                      </a>
                    </div>
                  @endif
                </div>

                <!-- Categories Badges -->
                @if($storeCategories->isNotEmpty())
                  <div class="d-flex gap-1 mb-3 flex-wrap">
                    @foreach($storeCategories as $catName)
                      <span class="badge bg-light text-dark border">
                        <i class="bi bi-tag-fill text-success me-1"></i> {{ $catName }}
                      </span>
                    @endforeach
                  </div>
                @endif
              </div>

              <!-- Activated Action Buttons -->
              <div class="d-flex flex-column gap-2 mt-2 pt-2 border-top">
                <div class="d-flex gap-2">
                  <a href="{{ route('shop-details.show', $store->id) }}" class="btn btn-success store-action-btn flex-grow-1">
                    <i class="bi bi-shop"></i> صفحة المحل
                  </a>
                  <a href="{{ route('prices', ['store_id' => $store->id]) }}" class="btn btn-outline-success store-action-btn" title="تصفح أسعار هذا المحل">
                    <i class="bi bi-tag-fill"></i> السلع والأسعار
                  </a>
                </div>

                <!-- Popup Interactive Map Button -->
                <button type="button" 
                        class="btn btn-outline-secondary store-action-btn btn-sm w-100" 
                        onclick='openStoreMapModal(@json($storeDataArray))'>
                  <i class="bi bi-geo-alt-fill text-danger"></i> موقعه على الخريطة والمسافة
                </button>
              </div>

            </div>
          </div>
        @empty
          <div class="col-12 text-center py-5 text-muted">
            <div style="font-size: 3.5rem; color: #94a3b8;"><i class="bi bi-shop-window"></i></div>
            <h4 class="fw-bold text-dark mt-3">لا توجد محلات مطابقة لمعايير البحث</h4>
            <p class="text-muted mb-4">جرب البحث بكلمات أخرى أو اختر محافظة وتصنيف مختلفين</p>
            <a href="{{ route('shops') }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold">
              <i class="bi bi-arrow-clockwise me-1"></i> عرض جميع المحلات
            </a>
          </div>
        @endforelse
      </div>

    </div>
  </main>

  <!-- POPUP MODAL: STORE LOCATION & DISTANCE MAP -->
  <div class="modal fade" id="storeMapModal" tabindex="-1" aria-labelledby="storeMapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
        
        <!-- Modal Header -->
        <div class="modal-header border-0 bg-light p-3.5 px-4 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <img id="modal-store-img" src="" alt="متجر" class="rounded-circle shadow-sm" style="width: 48px; height: 48px; object-fit: cover; border: 2px solid var(--brand-green);">
            <div>
              <h5 class="modal-title fw-bolder text-dark mb-0" id="modal-store-name">اسم المتجر</h5>
              <div class="small text-muted" id="modal-store-region"><i class="bi bi-geo-alt-fill text-success"></i> المنطقة</div>
            </div>
          </div>
          <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body p-4">
          
          <!-- Leaflet Interactive Map -->
          <div id="modal-store-map" class="shadow-sm border mb-3"></div>

          <!-- Distance & Details Info Card -->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="p-3 bg-light rounded-3 h-100 border">
                <div class="small text-muted fw-bold mb-1">المسافة المقدرة من موقعك:</div>
                <div class="fs-5 fw-bolder brand-green d-flex align-items-center gap-2" id="modal-store-distance">
                  <i class="bi bi-compass"></i> قيد الحساب...
                </div>
                <div class="small text-muted mt-1" id="modal-store-est-time">حوالي دقائق مشياً أو بالسيارة</div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <div class="p-3 bg-light rounded-3 h-100 border d-flex flex-column justify-content-between">
                <div>
                  <div class="small text-muted fw-bold mb-1">بيانات التواصل والسلع:</div>
                  <div class="small mb-1 text-dark" id="modal-store-address"><i class="bi bi-geo me-1"></i> العنوان الكامل</div>
                  <div class="small text-dark" id="modal-store-phone"><i class="bi bi-telephone-fill text-success me-1"></i> هاتف التواصل</div>
                </div>
                <div class="small text-muted mt-1">
                  السلع المسجلة: <strong class="text-success" id="modal-store-prices-count">0</strong>
                </div>
              </div>
            </div>
          </div>

        </div>

        <!-- Modal Footer Actions -->
        <div class="modal-footer border-0 bg-light p-3 px-4 d-flex justify-content-between flex-wrap gap-2">
          <a href="#" target="_blank" rel="noopener noreferrer" id="modal-google-maps-btn" class="btn btn-outline-primary rounded-pill fw-bold d-flex align-items-center gap-2">
            <i class="bi bi-map-fill"></i> فتح الاتجاهات في Google Maps
          </a>
          <div class="d-flex gap-2">
            <a href="#" id="modal-prices-btn" class="btn btn-outline-success rounded-pill fw-bold">
              <i class="bi bi-tag-fill me-1"></i> السلع والأسعار
            </a>
            <a href="#" id="modal-details-btn" class="btn btn-success rounded-pill fw-bold">
              <i class="bi bi-shop me-1"></i> صفحة المحل الكاملة
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    // ===== Dynamic Region Filtering based on City =====
    const cityFilter = document.getElementById('shops-city-filter');
    const regionFilter = document.getElementById('shops-region-filter');
    function updateRegionsByCity(selectedCity) {
      if (!regionFilter) return;
      Array.from(regionFilter.options).forEach(opt => {
        if (!opt.value) return; // Keep "كل المناطق"
        const optCity = opt.getAttribute('data-city');
        if (!selectedCity || optCity === selectedCity) {
          opt.style.display = 'block';
        } else {
          opt.style.display = 'none';
        }
      });
    }

    if (cityFilter && regionFilter) {
      updateRegionsByCity(cityFilter.value);
    }

    // ===== Haversine Distance Calculation =====
    let currentUserLat = 31.501;
    let currentUserLng = 34.466;
    let isUserLocationKnown = false;

    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371;
      const dLat = (lat2 - lat1) * Math.PI / 180;
      const dLon = (lon2 - lon1) * Math.PI / 180;
      const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon/2) * Math.sin(dLon/2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
      return R * c;
    }

    function formatDistance(distKm) {
      if (distKm < 1) {
        return Math.round(distKm * 1000) + ' م';
      }
      return distKm.toFixed(1) + ' كم';
    }

    // Geolocation Calculation on Cards
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(pos) {
        currentUserLat = pos.coords.latitude;
        currentUserLng = pos.coords.longitude;
        isUserLocationKnown = true;

        document.querySelectorAll('.shop-grid-item').forEach(card => {
          const lat = parseFloat(card.getAttribute('data-lat'));
          const lng = parseFloat(card.getAttribute('data-lng'));
          const storeId = card.getAttribute('data-store-id');
          const distBadge = document.getElementById('store-dist-badge-' + storeId);

          if (!isNaN(lat) && !isNaN(lng) && distBadge) {
            const dist = calculateDistance(currentUserLat, currentUserLng, lat, lng);
            distBadge.innerHTML = `<i class="bi bi-compass text-primary"></i> يبعد ${formatDistance(dist)}`;
          } else if (distBadge) {
            distBadge.innerHTML = `<i class="bi bi-geo-alt text-muted"></i> قريب منك`;
          }
        });
      }, function(err) {
        document.querySelectorAll('.store-distance-badge').forEach(b => {
          b.innerHTML = `<i class="bi bi-geo-alt text-success"></i> متجر محلي`;
        });
      }, { timeout: 5000 });
    }

    // ===== Popup Modal Map Setup =====
    let modalMap = null;
    let modalStoreMarker = null;
    let modalUserMarker = null;
    let modalRouteLine = null;

    function openStoreMapModal(store) {
      // Set Modal Texts & Links
      document.getElementById('modal-store-name').innerText = store.name;
      document.getElementById('modal-store-img').src = store.image;
      document.getElementById('modal-store-region').innerHTML = `<i class="bi bi-geo-alt-fill text-success"></i> ${store.region}`;
      document.getElementById('modal-store-address').innerHTML = `<i class="bi bi-geo me-1"></i> ${store.address || store.region}`;
      document.getElementById('modal-store-phone').innerHTML = store.phone 
        ? `<a href="tel:${store.phone}" class="text-decoration-none text-success fw-bold"><i class="bi bi-telephone-fill me-1"></i> ${store.phone}</a>`
        : `<span class="text-muted"><i class="bi bi-telephone me-1"></i> غير متوفر</span>`;
      document.getElementById('modal-store-prices_count') ? document.getElementById('modal-store-prices_count').innerText = (store.prices_count || 0) + ' سلعة' : null;
      document.getElementById('modal-details-btn').href = store.details_url;
      document.getElementById('modal-prices-btn').href = store.prices_url;

      const storeLat = parseFloat(store.lat) || 31.501;
      const storeLng = parseFloat(store.lng) || 34.466;

      document.getElementById('modal-google-maps-btn').href = `https://www.google.com/maps/dir/?api=1&destination=${storeLat},${storeLng}`;

      // Calculate & Display Distance
      let distText = 'قريب منك';
      let estTimeText = '';
      if (store.lat && store.lng) {
        const distKm = calculateDistance(currentUserLat, currentUserLng, storeLat, storeLng);
        distText = `يبعد ${formatDistance(distKm)}`;
        if (distKm < 1) {
          estTimeText = `حوالي ${Math.ceil(distKm * 12)} دقيقة مشياً 🚶‍♂️`;
        } else {
          estTimeText = `حوالي ${Math.ceil(distKm * 2.5)} دقيقة بالسيارة 🚗`;
        }
      }
      document.getElementById('modal-store-distance').innerHTML = `<i class="bi bi-compass"></i> ${distText}`;
      document.getElementById('modal-store-est-time').innerText = estTimeText;

      // Show Bootstrap Modal
      const modalEl = document.getElementById('storeMapModal');
      const bsModal = new bootstrap.Modal(modalEl);
      bsModal.show();

      // Initialize or Update Leaflet Map once modal is fully shown
      modalEl.addEventListener('shown.bs.modal', function onModalShown() {
        modalEl.removeEventListener('shown.bs.modal', onModalShown);

        if (!modalMap) {
          modalMap = L.map('modal-store-map').setView([storeLat, storeLng], 14);
          L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap',
            maxZoom: 18
          }).addTo(modalMap);
        } else {
          modalMap.invalidateSize();
        }

        // Clear existing layers
        if (modalStoreMarker) modalMap.removeLayer(modalStoreMarker);
        if (modalUserMarker) modalMap.removeLayer(modalUserMarker);
        if (modalRouteLine) modalMap.removeLayer(modalRouteLine);

        // Store Pin
        const storeIcon = L.divIcon({
          className: 'custom-pin-wrap',
          html: `<div class="custom-store-pin"><i class="bi bi-shop"></i></div>`,
          iconSize: [38, 38],
          iconAnchor: [19, 19],
          popupAnchor: [0, -20]
        });

        modalStoreMarker = L.marker([storeLat, storeLng], { icon: storeIcon }).addTo(modalMap);
        modalStoreMarker.bindPopup(`<div class="fw-bold p-1 text-center">${store.name}</div>`).openPopup();

        const bounds = [[storeLat, storeLng]];

        // If user location available, add user pin & route line
        if (isUserLocationKnown) {
          const userIcon = L.divIcon({
            className: 'user-pin-wrap',
            html: `<div class="user-location-pin" title="موقعك"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 11]
          });
          modalUserMarker = L.marker([currentUserLat, currentUserLng], { icon: userIcon }).addTo(modalMap);
          modalUserMarker.bindPopup('<div class="fw-bold p-1 text-center">📍 موقعك الحالي</div>');
          bounds.push([currentUserLat, currentUserLng]);

          // Connecting Line
          modalRouteLine = L.polyline([[currentUserLat, currentUserLng], [storeLat, storeLng]], {
            color: '#16a34a',
            weight: 3.5,
            dashArray: '8, 8',
            opacity: 0.85
          }).addTo(modalMap);
        }

        modalMap.fitBounds(bounds, { padding: [40, 40] });
      });
    }

    // ===== Toggle Store Favorite =====
    function toggleStoreFavorite(id, btn) {
      @if(!Auth::check())
        window.location.href = '{{ route("login") }}';
        return;
      @endif
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      fetch('{{ route("favorites.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ type: 'store', reference_id: id })
      })
      .then(res => {
        if (res.status === 401 || res.redirected) {
          window.location.href = '{{ route("login") }}';
          return;
        }
        return res.json();
      })
      .then(data => {
        if (!data) return;
        if (data.status === 'unauthenticated') {
          window.location.href = '{{ route("login") }}';
        } else if (data.status === 'added') {
          btn.classList.add('active');
          btn.style.transform = 'scale(1.25)';
          setTimeout(() => btn.style.transform = 'scale(1)', 200);
          showToast(data.message, 'success');
        } else if (data.status === 'removed') {
          btn.classList.remove('active');
          showToast(data.message, 'warning');
        }
      })
      .catch(() => showToast('حدث خطأ، حاول مرة أخرى', 'danger'));
    }

    function showToast(msg, type='success') {
      const t = document.createElement('div');
      t.className = `alert alert-${type} position-fixed d-flex align-items-center gap-2 shadow-lg`;
      t.style.cssText = 'bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 240px; border-radius: 12px; font-weight: 700;';
      t.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}"></i> ${msg}`;
      document.body.appendChild(t);
      setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.4s'; setTimeout(() => t.remove(), 400); }, 2200);
    }
  </script>

  @include('partials.footer')
</body>

</html>

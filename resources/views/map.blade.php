<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | خريطة المحلات والموقع الحالي</title>
  <meta name="description" content="استعرض واكتشف المحلات والمتاجر القريبة منك على الخريطة التفاعلية مع حساب المسافات">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=8') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/imges/logo.png') }}">

  <!-- LEAFLET.JS MAP STYLES -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

  <style>
    #interactive-map {
      height: 600px;
      width: 100%;
      border-radius: 20px;
      z-index: 1;
    }
    .leaflet-popup-content-wrapper {
      border-radius: 18px;
      padding: 10px;
      font-family: 'Tajawal', sans-serif;
      direction: rtl;
      text-align: right;
      box-shadow: 0 14px 32px rgba(0,0,0,0.18);
    }
    .leaflet-popup-content {
      margin: 8px 8px;
      line-height: 1.5;
    }
    .store-popup-card {
      min-width: 250px;
    }
    .store-popup-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 10px;
    }
    .store-popup-img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      object-fit: cover;
      border: 2.5px solid var(--brand-green);
      flex-shrink: 0;
    }
    .store-popup-title {
      font-weight: 800;
      font-size: 1.05rem;
      color: #111;
      margin: 0;
    }
    .store-popup-meta {
      font-size: 0.85rem;
      color: #555;
      margin-bottom: 12px;
      display: flex;
      flex-direction: column;
      gap: 5px;
    }
    .store-popup-meta a {
      color: inherit;
      text-decoration: none;
    }
    .store-popup-meta a:hover {
      color: var(--brand-green);
      text-decoration: underline;
    }
    .store-popup-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
      width: 100%;
      background: var(--brand-green);
      color: #0b1f13 !important;
      text-align: center;
      font-weight: 800;
      font-size: 0.9rem;
      padding: 8px 12px;
      border-radius: 12px;
      text-decoration: none;
      transition: background .2s, transform .15s;
      margin-bottom: 6px;
    }
    .store-popup-btn:hover {
      background: #1fcf5b;
      transform: translateY(-1px);
    }
    .store-popup-btn-nav {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      width: 100%;
      background: #f1f5f9;
      color: #334155 !important;
      text-align: center;
      font-weight: 700;
      font-size: 0.82rem;
      padding: 6px 12px;
      border-radius: 10px;
      text-decoration: none;
      transition: all .15s;
      border: 1px solid #e2e8f0;
    }
    .store-popup-btn-nav:hover {
      background: #e2e8f0;
      color: #0f172a !important;
    }
    .map-sidebar-card {
      height: 600px;
      display: flex;
      flex-direction: column;
    }
    .map-shop-list-scroll {
      flex-grow: 1;
      overflow-y: auto;
      padding-right: 4px;
    }
    .map-shop-list-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .map-shop-list-scroll::-webkit-scrollbar-thumb {
      background: #d1d5db;
      border-radius: 10px;
    }
    .map-shop-item {
      cursor: pointer;
      border: 2px solid transparent;
      border-radius: 16px;
      transition: all .2s ease;
      background: #f8faf9;
    }
    .map-shop-item:hover, .map-shop-item.active {
      border-color: var(--brand-green);
      background: #f0fdf6 !important;
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(36,223,100,0.15);
    }
    .distance-badge {
      background: rgba(36, 223, 100, 0.15);
      color: #0f5132;
      font-weight: 800;
      font-size: 0.78rem;
      padding: 4px 10px;
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      border: 1px solid rgba(36, 223, 100, 0.3);
      white-space: nowrap;
    }
    .custom-map-pin {
      background: var(--brand-green);
      border: 3px solid #ffffff;
      color: #0b1f13;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 900;
      box-shadow: 0 4px 14px rgba(0,0,0,0.3);
      transition: transform .2s ease;
    }
    .custom-map-pin:hover {
      transform: scale(1.18);
    }
    .user-location-pulse {
      width: 22px;
      height: 22px;
      background: #3b82f6;
      border: 3px solid #ffffff;
      border-radius: 50%;
      box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
      animation: pulse-blue 1.8s infinite;
    }
    @keyframes pulse-blue {
      0% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7);
      }
      70% {
        box-shadow: 0 0 0 14px rgba(59, 130, 246, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
      }
    }
    .map-recenter-btn {
      position: absolute;
      top: 15px;
      left: 15px;
      z-index: 1000;
      background: #ffffff;
      color: #334155;
      border: 1px solid #cbd5e1;
      padding: 8px 14px;
      border-radius: 12px;
      font-weight: 800;
      font-size: 0.85rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 7px;
      transition: all 0.2s ease;
    }
    .map-recenter-btn:hover {
      background: #f8fafc;
      color: var(--brand-green);
      border-color: var(--brand-green);
      transform: translateY(-1px);
    }
    .search-input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }
    .search-clear-btn {
      position: absolute;
      left: 10px;
      background: none;
      border: none;
      color: #94a3b8;
      font-size: 15px;
      cursor: pointer;
      display: none;
      padding: 0 4px;
    }
    .search-clear-btn:hover {
      color: #ef4444;
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
          <li class="nav-item"><a class="nav-link active" href="{{ route('map') }}">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <form action="{{ route('prices') }}" method="GET">
            <div class="search-pill">
              <input type="text" name="search" placeholder="طحين ، سكر">
              <button type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>
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
        <form action="{{ route('prices') }}" method="GET" class="d-flex align-items-center flex-grow-1">
          <div class="search-pill flex-grow-1">
            <input type="text" name="search" placeholder="طحين ، سكر">
            <button type="submit"><i class="bi bi-search"></i></button>
          </div>
        </form>
        <a href="{{ route('compare') }}" class="btn-compare"><i class="bi bi-arrow-left-right me-1"></i> مقارنة</a>
      </div>
    </div>

    <div class="mobile-nav-panel">
      <a class="nav-link" href="{{ route('index') }}">
        <i class="bi bi-house-door-fill"></i> الرئيسية
      </a>
      <a class="nav-link active" href="{{ route('map') }}">
        <i class="bi bi-map-fill"></i> الخريطة التفاعلية
      </a>
      <a class="nav-link" href="{{ route('shops') }}">
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
  <section class="page-header-banner">
    <img src="{{ asset('assets/imges/map.png') }}" alt="خريطة المحلات" class="hero-bg-img">
    <div class="container d-flex align-items-center justify-content-between flex-wrap py-4 gap-3">
      <div class="text-end">
        <h1 class="page-header-title mb-1"><span class="brand-green">خريطة</span> <span class="brand-white">المحلات</span> <i class="bi bi-geo-alt-fill text-success fs-3"></i></h1>
        <p class="page-header-sub mb-0">استعرض المحلات القريبة منك مرتبة حسب المسافة مع حساب بعد كل محل عن موقعك</p>
      </div>
      <div class="d-none d-md-block">
        <img src="{{ asset('assets/imges/shops.png') }}" alt="المحلات" class="banner-store-img">
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- FILTER CARD -->
      <div class="filter-card-container mb-4">
        <form action="{{ route('map') }}" method="GET" id="map-filter-form" onsubmit="return false;">
          <div class="row g-3 align-items-center">
            
            <div class="col-12 col-md-3">
              <button type="button" id="btn-user-location" class="btn-locate w-100 justify-content-center py-2 d-flex align-items-center gap-2">
                <span id="btn-user-icon"><i class="bi bi-geo-alt-fill text-danger"></i></span>
                <span id="btn-user-text">تحديد موقعي الحالي</span>
              </button>
            </div>

            <div class="col-12 col-md-3">
              <div class="search-input-wrapper">
                <input type="text" name="search" id="filter-search-input" class="form-control modern-search-input" placeholder="ابحث باسم المحل أو العنوان..." value="{{ request('search') }}" autocomplete="off">
                <button type="submit" class="search-circle-btn" title="بحث">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>

            <div class="col-12 col-md-3">
              <select name="city" id="filter-city-select" class="form-select border-1 rounded-3">
                <option value="">كل المحافظات</option>
                @foreach($cities as $city)
                  <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-3">
              <select name="region_id" id="filter-region-select" class="form-select border-1 rounded-3">
                <option value="">كل المناطق والأحياء</option>
                @foreach($regions as $reg)
                  <option value="{{ $reg->id }}" data-city="{{ $reg->city_or_governorate }}" {{ request('region_id') == $reg->id ? 'selected' : '' }}>{{ $reg->city_or_governorate }} - {{ $reg->area_name }}</option>
                @endforeach
              </select>
            </div>

          </div>
        </form>
      </div>

      <!-- MAIN SPLIT VIEW (SIDEBAR + MAP) -->
      <div class="row g-3 pb-5">
        
        <!-- SIDEBAR SHOP LIST -->
        <div class="col-12 col-lg-4">
          <div class="bg-white rounded-4 shadow-sm p-3 map-sidebar-card">
            
            <!-- SIDEBAR HEADER WITH PROXIMITY COUNT -->
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
              <div>
                <div class="fw-bold text-dark fs-6" id="sidebar-header-title">
                  <span id="nearby-count" class="brand-green fw-bolder">{{ $stores->count() }}</span> محلات قريبة من موقعك
                </div>
                <div class="text-muted" style="font-size:0.75rem;" id="location-status-text">
                  <i class="bi bi-compass text-success"></i> يتم الترتيب حسب الأقرب لموقعك الحالي
                </div>
              </div>
              <button type="button" id="btn-reset-filters" class="btn btn-link text-decoration-none small text-muted p-0" style="display:none;"><i class="bi bi-x"></i> إلغاء الفلتر</button>
            </div>

            <!-- DYNAMIC STORE LIST CONTAINER -->
            <div class="map-shop-list-scroll" id="shop-list-container">
              <!-- Rendered dynamically via JavaScript for live filtering & distance sorting -->
            </div>

          </div>
        </div>

        <!-- LEAFLET INTERACTIVE MAP DISPLAY -->
        <div class="col-12 col-lg-8">
          <div class="bg-white rounded-4 shadow-sm p-2 overflow-hidden h-100 position-relative">
            <!-- RECENTER / FIT ALL BUTTON -->
            <button type="button" id="btn-fit-map" class="map-recenter-btn" title="عرض جميع المحلات على الخريطة">
              <i class="bi bi-bullseye text-success"></i> عرض كل المحلات
            </button>
            <div id="interactive-map"></div>
          </div>
        </div>

      </div>

    </div>
  </main>

  <!-- FOOTER -->
  @include('partials.footer')

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>

  <!-- LEAFLET.JS JAVASCRIPT -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script>
    // All stores data injected from database
    const allStoresData = @json($allStores);
    let rawStoresData = [...allStoresData];
    let currentUserLat = null;
    let currentUserLng = null;
    let userMarker = null;

    // Default Gaza Strip center coordinates
    const defaultCenterLat = 31.4600;
    const defaultCenterLng = 34.4000;

    const map = L.map('interactive-map').setView([defaultCenterLat, defaultCenterLng], 11);

    // OpenStreetMap Tile Layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19
    }).addTo(map);

    let markers = {};

    // Custom Map Pin for Stores
    function createCustomPin() {
        return L.divIcon({
            className: 'custom-pin-container',
            html: `<div class="custom-map-pin" style="width: 38px; height: 38px; font-size: 1.15rem;"><i class="bi bi-shop"></i></div>`,
            iconSize: [38, 38],
            iconAnchor: [19, 38],
            popupAnchor: [0, -36]
        });
    }

    // User Location Pin
    function createUserPin() {
        return L.divIcon({
            className: 'user-pin-container',
            html: `<div class="user-location-pulse"></div>`,
            iconSize: [22, 22],
            iconAnchor: [11, 11],
            popupAnchor: [0, -14]
        });
    }

    // Haversine Distance Formula (km)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        if (!lat1 || !lon1 || !lat2 || !lon2) return null;
        const R = 6371; // Earth radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
    }

    function formatDistance(distanceKm) {
        if (distanceKm === null || distanceKm === undefined || isNaN(distanceKm)) {
            return 'غير محدد';
        }
        if (distanceKm < 1) {
            const meters = Math.round(distanceKm * 1000);
            return `${meters} متر`;
        }
        return `${distanceKm.toFixed(1)} كم`;
    }

    // Render/Refresh Map Markers & Popups
    function renderMapMarkers() {
        // Remove existing store markers
        Object.values(markers).forEach(m => map.removeLayer(m));
        markers = {};

        const bounds = [];
        if (currentUserLat && currentUserLng) {
            bounds.push([currentUserLat, currentUserLng]);
        }

        rawStoresData.forEach(store => {
            if (!store.latitude || !store.longitude) return;

            const lat = parseFloat(store.latitude);
            const lng = parseFloat(store.longitude);
            bounds.push([lat, lng]);

            const storeImg = store.image_url || (store.image 
                ? (store.image.startsWith('http') || store.image.startsWith('data:image') ? store.image : `{{ asset('storage') }}/${store.image}`)
                : `{{ asset('assets/imges/shops.png') }}`);
            const regionName = store.region ? `${store.region.city_or_governorate} - ${store.region.area_name}` : 'قطاع غزة';
            const address = store.address || '';
            const phone = store.phone || '';
            const distanceText = (store.distance !== undefined && store.distance < 900000) ? formatDistance(store.distance) : '';
            const storeDetailsUrl = `{{ url('/shop_details') }}/${store.id}`;
            const googleNavUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

            const popupContent = `
                <div class="store-popup-card">
                  <div class="store-popup-header">
                    <img src="${storeImg}" alt="${escapeHtml(store.name)}" class="store-popup-img">
                    <div>
                      <h4 class="store-popup-title">${escapeHtml(store.name)}</h4>
                      <span class="badge bg-success bg-opacity-25 text-success" style="font-size:0.75rem;"><i class="bi bi-geo-alt-fill"></i> ${escapeHtml(regionName)}</span>
                    </div>
                  </div>
                  <div class="store-popup-meta">
                    ${distanceText ? `<div class="fw-bold text-success"><i class="bi bi-compass"></i> يبعد عنك: ${escapeHtml(distanceText)}</div>` : ''}
                    ${address ? `<div><i class="bi bi-geo"></i> ${escapeHtml(address)}</div>` : ''}
                    ${phone ? `<div><a href="tel:${escapeHtml(phone)}"><i class="bi bi-telephone-fill text-success"></i> ${escapeHtml(phone)}</a></div>` : ''}
                  </div>
                  <a href="${storeDetailsUrl}" class="store-popup-btn">
                    <i class="bi bi-shop"></i> عرض المحل والسلع
                  </a>
                  <a href="${googleNavUrl}" target="_blank" rel="noopener noreferrer" class="store-popup-btn-nav">
                    <i class="bi bi-map-fill"></i> فتح الاتجاهات في الخريطة
                  </a>
                </div>
            `;

            const marker = L.marker([lat, lng], { icon: createCustomPin() })
                .addTo(map)
                .bindPopup(popupContent);

            markers[store.id] = marker;

            marker.on('click', () => {
                highlightSidebarItem(store.id);
            });
        });

        return bounds;
    }

    // Render & Sort Sidebar Store Cards by Distance
    function renderSidebarList() {
        const container = document.getElementById('shop-list-container');
        if (!container) return;

        // If user location is known, calculate distances & sort
        if (currentUserLat && currentUserLng) {
            rawStoresData.forEach(store => {
                if (store.latitude && store.longitude) {
                    store.distance = calculateDistance(currentUserLat, currentUserLng, parseFloat(store.latitude), parseFloat(store.longitude));
                } else {
                    store.distance = 999999;
                }
            });

            // Sort from closest to furthest
            rawStoresData.sort((a, b) => a.distance - b.distance);

            document.getElementById('location-status-text').innerHTML = '<i class="bi bi-compass text-success"></i> تم الترتيب تلقائياً من الأقرب إلى الأبعد';
        }

        document.getElementById('nearby-count').innerText = rawStoresData.length;

        if (rawStoresData.length === 0) {
            container.innerHTML = `
                <div class="text-center py-5 text-muted">
                  <div style="font-size: 2.5rem;"><i class="bi bi-shop-window text-muted"></i></div>
                  <div class="fw-bold mt-2">لا توجد محلات مطابقة في هذا البحث أو المنطقة</div>
                  <button type="button" class="btn btn-sm btn-success rounded-pill mt-3" onclick="resetAllFilters()"><i class="bi bi-arrow-clockwise"></i> عرض جميع المحلات</button>
                </div>
            `;
            return;
        }

        let html = '';
        rawStoresData.forEach(store => {
            const storeImg = store.image_url || (store.image 
                ? (store.image.startsWith('http') || store.image.startsWith('data:image') ? store.image : `{{ asset('storage') }}/${store.image}`)
                : `{{ asset('assets/imges/shops.png') }}`);
            const regionName = store.region ? `${store.region.city_or_governorate} - ${store.region.area_name}` : 'قطاع غزة';
            const distanceFormatted = (store.distance !== undefined && store.distance < 900000) ? formatDistance(store.distance) : 'قريب منك';
            const storeDetailsUrl = `{{ url('/shop_details') }}/${store.id}`;

            html += `
                <div class="map-shop-item p-3 mb-2 d-flex justify-content-between align-items-center"
                     onclick="focusStoreOnMap(${store.latitude}, ${store.longitude}, ${store.id})"
                     id="sidebar-store-${store.id}">
                  <div class="d-flex align-items-center gap-3">
                    <img src="${storeImg}" alt="${escapeHtml(store.name)}" class="rounded-circle shadow-sm" style="width: 44px; height: 44px; object-fit: cover;">
                    <div>
                      <div class="fw-bold text-dark fs-6">${escapeHtml(store.name)}</div>
                      <div class="small text-muted d-flex align-items-center gap-1">
                        <i class="bi bi-geo-alt-fill text-success" style="font-size:0.8rem;"></i> ${escapeHtml(regionName)}
                      </div>
                      <div class="store-distance-wrap mt-1">
                        <span class="distance-badge">
                          <i class="bi bi-compass"></i> يبعد ${escapeHtml(distanceFormatted)}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex flex-column align-items-end gap-1">
                    <a href="${storeDetailsUrl}" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1" style="font-size: 0.75rem;" onclick="event.stopPropagation();">
                      <i class="bi bi-arrow-left"></i> عرض المحل
                    </a>
                  </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Function to pan and focus on a specific store
    function focusStoreOnMap(lat, lng, storeId) {
        if (!lat || !lng) return;
        map.flyTo([lat, lng], 15, { duration: 1.0 });
        if (markers[storeId]) {
            setTimeout(() => {
                markers[storeId].openPopup();
            }, 800);
        }
        highlightSidebarItem(storeId);
    }

    function highlightSidebarItem(storeId) {
        document.querySelectorAll('.map-shop-item').forEach(el => el.classList.remove('active'));
        const el = document.getElementById(`sidebar-store-${storeId}`);
        if (el) {
            el.classList.add('active');
            el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    // Set and Handle User Location
    function setUserLocation(lat, lng) {
        currentUserLat = lat;
        currentUserLng = lng;

        if (userMarker) {
            map.removeLayer(userMarker);
        }

        userMarker = L.marker([lat, lng], { icon: createUserPin() })
            .addTo(map)
            .bindPopup('<b style="color:#2563eb;"><i class="bi bi-geo-alt-fill"></i> موقعك الحالي</b>');

        renderSidebarList();
        renderMapMarkers();

        const btnText = document.getElementById('btn-user-text');
        const btnIcon = document.getElementById('btn-user-icon');
        if (btnText) btnText.innerText = 'تم تحديد موقعك ';
        if (btnIcon) btnIcon.innerHTML = '<i class="bi bi-check-circle-fill text-white"></i>';
    }

    // Locate Current User Position Button
    document.getElementById('btn-user-location').addEventListener('click', function() {
        const btn = this;
        const btnText = document.getElementById('btn-user-text');
        const btnIcon = document.getElementById('btn-user-icon');

        // If location is already known, smoothly focus on it
        if (currentUserLat && currentUserLng) {
            map.flyTo([currentUserLat, currentUserLng], 15, { duration: 1.0 });
            if (userMarker) {
                setTimeout(() => userMarker.openPopup(), 700);
            }
            return;
        }

        btnText.innerText = 'جاري تحديد موقعك...';
        btnIcon.innerHTML = '<span class="spinner-border spinner-border-sm text-white" role="status"></span>';
        btn.disabled = true;

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    setUserLocation(lat, lng);
                    map.flyTo([lat, lng], 14, { duration: 1.2 });
                    btn.disabled = false;
                },
                function(error) {
                    alert('يرجى السماح بصلاحية الموقع في المتصفح لحساب المسافات بدقة.');
                    btnText.innerText = 'تحديد موقعي الحالي';
                    btnIcon.innerHTML = '<i class="bi bi-geo-alt-fill text-danger"></i>';
                    btn.disabled = false;
                },
                { enableHighAccuracy: true, timeout: 10000 }
            );
        } else {
            alert('المتصفح لا يدعم تحديد الموقع.');
            btnText.innerText = 'تحديد موقعي الحالي';
            btnIcon.innerHTML = '<i class="bi bi-geo-alt-fill text-danger"></i>';
            btn.disabled = false;
        }
    });

    // Fit Bounds / Show all Stores Button
    document.getElementById('btn-fit-map').addEventListener('click', function() {
        fitMapToAllMarkers();
    });

    function fitMapToAllMarkers() {
        const bounds = [];
        if (currentUserLat && currentUserLng) {
            bounds.push([currentUserLat, currentUserLng]);
        }
        rawStoresData.forEach(s => {
            if (s.latitude && s.longitude) {
                bounds.push([parseFloat(s.latitude), parseFloat(s.longitude)]);
            }
        });

        if (bounds.length > 1) {
            map.fitBounds(bounds, { padding: [40, 40] });
        } else if (bounds.length === 1) {
            map.setView(bounds[0], 14);
        } else {
            map.setView([defaultCenterLat, defaultCenterLng], 11);
        }
    }

    // Live Filtering Logic (Search, City, Region)
    const searchInput = document.getElementById('filter-search-input');
    const citySelect = document.getElementById('filter-city-select');
    const regionSelect = document.getElementById('filter-region-select');
    const clearSearchBtn = document.getElementById('btn-clear-search');
    const resetFiltersBtn = document.getElementById('btn-reset-filters');

    function applyFilters() {
        const searchQuery = (searchInput.value || '').trim().toLowerCase();
        const selectedCity = citySelect.value;
        const selectedRegion = regionSelect.value;

        // Show/hide clear search button
        if (clearSearchBtn) {
            clearSearchBtn.style.display = searchQuery ? 'block' : 'none';
        }

        // Show/hide reset filters button
        const isFiltered = searchQuery || selectedCity || selectedRegion;
        if (resetFiltersBtn) {
            resetFiltersBtn.style.display = isFiltered ? 'inline-block' : 'none';
        }

        rawStoresData = allStoresData.filter(store => {
            // 1. City Match
            if (selectedCity) {
                const storeCity = store.region?.city_or_governorate || '';
                if (storeCity !== selectedCity) return false;
            }

            // 2. Region Match
            if (selectedRegion) {
                if (String(store.region_id) !== String(selectedRegion)) return false;
            }

            // 3. Search Match (name, address, area, city)
            if (searchQuery) {
                const name = (store.name || '').toLowerCase();
                const address = (store.address || '').toLowerCase();
                const area = (store.region?.area_name || '').toLowerCase();
                const city = (store.region?.city_or_governorate || '').toLowerCase();

                if (!name.includes(searchQuery) && !address.includes(searchQuery) && !area.includes(searchQuery) && !city.includes(searchQuery)) {
                    return false;
                }
            }

            return true;
        });

        renderSidebarList();
        renderMapMarkers();
        fitMapToAllMarkers();
    }

    // Event Listeners for Filters
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            applyFilters();
            searchInput.focus();
        });
    }
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            // Filter regions dropdown based on selected city
            const city = this.value;
            const regionOptions = regionSelect.querySelectorAll('option');
            regionOptions.forEach(opt => {
                if (!opt.value) {
                    opt.style.display = 'block';
                    return;
                }
                const optCity = opt.getAttribute('data-city');
                if (!city || optCity === city) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            });
            if (regionSelect.value && regionSelect.selectedOptions[0]?.style.display === 'none') {
                regionSelect.value = '';
            }
            applyFilters();
        });
    }
    if (regionSelect) {
        regionSelect.addEventListener('change', applyFilters);
    }
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', resetAllFilters);
    }

    function resetAllFilters() {
        if (searchInput) searchInput.value = '';
        if (citySelect) citySelect.value = '';
        if (regionSelect) regionSelect.value = '';
        if (clearSearchBtn) clearSearchBtn.style.display = 'none';
        if (resetFiltersBtn) resetFiltersBtn.style.display = 'none';

        if (regionSelect) {
            regionSelect.querySelectorAll('option').forEach(opt => opt.style.display = 'block');
        }

        rawStoresData = [...allStoresData];
        renderSidebarList();
        renderMapMarkers();
        fitMapToAllMarkers();
    }

    // Initialize map and geolocation on load
    window.addEventListener('DOMContentLoaded', () => {
        renderSidebarList();
        const bounds = renderMapMarkers();

        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    setUserLocation(position.coords.latitude, position.coords.longitude);
                },
                () => {
                    // Fallback to initial Gaza center calculation
                    setUserLocation(defaultCenterLat, defaultCenterLng);
                },
                { timeout: 4000 }
            );
        } else {
            setUserLocation(defaultCenterLat, defaultCenterLng);
        }
    });

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
  </script>
</body>

</html>
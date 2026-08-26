<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | مقارنة الاسعار</title>
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=10') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/imges/logo.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    .compare-header { padding: 20px 0 10px; }
    #compare-map {
      height: 520px;
      width: 100%;
      border-radius: 20px;
      z-index: 1;
    }
    .leaflet-popup-content-wrapper {
      border-radius: 16px;
      direction: rtl;
      text-align: right;
      box-shadow: 0 10px 28px rgba(0,0,0,0.18);
      font-family: 'Tajawal', sans-serif;
    }
    .leaflet-popup-content { margin: 8px 10px; line-height: 1.4; }
    .store-popup-header { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
    .store-popup-img { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid var(--brand-green); }
    .store-popup-title { font-weight: 800; font-size: 1rem; color: #111; margin: 0; }
    .store-popup-btn {
      display: inline-flex; align-items: center; justify-content: center; gap: 5px;
      width: 100%; background: #16a34a; color: #fff !important; text-align: center;
      font-weight: 700; font-size: 0.85rem; padding: 7px 12px; border-radius: 10px;
      text-decoration: none; margin-top: 6px; transition: background 0.2s;
    }
    .store-popup-btn:hover { background: #15803d; }
    .custom-store-pin {
      background: #16a34a; border: 2.5px solid #fff; border-radius: 50%;
      width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
      color: #fff; box-shadow: 0 4px 14px rgba(0,0,0,0.25); font-size: 1rem;
      transition: transform 0.2s;
    }
    .custom-store-pin:hover { transform: scale(1.18); }
    .custom-store-pin.selected {
      background: #dc2626; transform: scale(1.25); box-shadow: 0 6px 18px rgba(220,38,38,0.4);
    }
    .user-location-pin {
      background: #2563eb; border: 3px solid #fff; border-radius: 50%;
      width: 22px; height: 22px; box-shadow: 0 0 0 6px rgba(37,99,235,0.25);
    }
    .table-compare-row { cursor: pointer; transition: all 0.15s; }
    .table-compare-row:hover { background-color: #f0fdf4 !important; }
    .table-compare-row.active-row { background-color: #dcfce7 !important; font-weight: bold; }
    .distance-badge {
      display: inline-flex; align-items: center; gap: 4px;
      background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;
      border-radius: 10px; padding: 3px 8px; font-weight: 700; font-size: 0.75rem;
    }
    .category-deal-badge {
      display: inline-flex; align-items: center; gap: 4px;
      background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;
      border-radius: 10px; padding: 4px 10px; font-weight: 700; font-size: 0.8rem;
    }
    .rank-badge {
      width: 28px; height: 28px; border-radius: 50%; display: inline-flex;
      align-items: center; justify-content: center; font-weight: 800; font-size: 0.82rem;
    }
    .rank-1 { background: #fef08a; color: #854d0e; border: 1.5px solid #eab308; }
    .rank-2 { background: #e2e8f0; color: #475569; border: 1.5px solid #94a3b8; }
    .rank-3 { background: #fed7aa; color: #9a3412; border: 1.5px solid #f97316; }
    .rank-other { background: #f1f5f9; color: #64748b; }
    .vs-box {
      background: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 24px;
    }
    .vs-card {
      background: #fff; border-radius: 16px; padding: 20px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
  </style>
</head>

<body>
  @include('partials.splash-screen')

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
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
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
      <a class="nav-link" href="{{ route('shops') }}">
        <i class="bi bi-shop"></i> دليل المحلات
      </a>
      <a class="nav-link" href="{{ route('prices') }}">
        <i class="bi bi-tag-fill"></i> قائمة الأسعار
      </a>
      <a class="nav-link active" href="{{ route('compare') }}">
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

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- PAGE TITLE -->
      <div class="compare-header text-center mb-4">
        <h1 class="fw-bolder">مقارنة <span class="brand-green">الأسعار والمحلات</span> <i class="bi bi-arrow-left-right text-success fs-3"></i></h1>
        <p class="text-muted fs-6">قارن بين المحلات الأقرب إليك واكتشف أرخص متجر في منطقتك لكل تصنيف</p>
      </div>

      <!-- GUEST RESTRICTION NOTICE -->
      @if(!Auth::check())
        <div class="alert alert-warning border-0 rounded-4 shadow-sm p-3 mb-4 d-flex align-items-center justify-content-between flex-wrap gap-3" style="background: linear-gradient(135deg, #fffbeb, #fef3c7); border: 1.5px solid #fde68a !important;">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: #f59e0b; color: #fff; font-size: 1.25rem; flex-shrink: 0;">
              <i class="bi bi-lock-fill"></i>
            </div>
            <div>
              <div class="fw-bolder text-dark" style="font-size: 0.95rem;">تخصيص وتحديد تفاصيل المقارنة متاح للمستخدمين المسجلين فقط</div>
              <div class="small text-muted">سجل دخولك لتتمكن من تغيير السلع وتحديد المتاجر والمقارنة وجهاً لوجه بدقة</div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <a href="{{ route('login') }}" class="btn btn-warning fw-bold rounded-pill px-4" style="background:#f59e0b;color:#fff;border:none;">
              <i class="bi bi-box-arrow-in-left me-1"></i> تسجيل الدخول
            </a>
            <a href="{{ route('signup') }}" class="btn btn-outline-dark fw-bold rounded-pill px-3">
              إنشاء حساب
            </a>
          </div>
        </div>
      @endif

      <!-- FILTER BAR (FORM) -->
      <form action="{{ route('compare') }}" method="GET" id="compare-filter-form" @if(!Auth::check()) onsubmit="return requireLoginForCompare(event);" @endif>
        <div class="filter-card-container mb-4">
          <div class="row g-3 align-items-end">

            <!-- Submit Button -->
            <div class="col-12 col-md-2 order-md-1">
              <button type="submit" class="btn-locate w-100 justify-content-center py-2 d-flex align-items-center gap-2" @if(!Auth::check()) onclick="return requireLoginForCompare(event);" @endif>
                <i class="bi bi-funnel-fill"></i> تحديث المقارنة
              </button>
            </div>

            <!-- Item Select (Includes ALL mode option) -->
            <div class="col-12 col-md-3 order-md-2">
              <label class="form-label fw-bold small">السلعة المطلوب مقارنتها</label>
              <select name="item_id" class="form-select border-1 rounded-3 fw-bold text-success" id="compare-item-select"
                      @if(!Auth::check()) onchange="requireLoginForCompare(event);" @else onchange="document.getElementById('compare-filter-form').submit();" @endif>
                <option value="all" {{ (request('item_id') == 'all' || !isset($selectedItem) || !$selectedItem) ? 'selected' : '' }}>
                  🌟 الكل (مقارنة المحلات حسب الأقرب والتصنيف الأرخص)
                </option>
                <optgroup label="مقارنة سلعة معينة عبر المحلات">
                  @foreach($allItems as $item)
                    <option value="{{ $item->id }}" data-category="{{ $item->category_name }}" {{ (isset($selectedItem) && $selectedItem && $selectedItem->id == $item->id && request('item_id') != 'all') ? 'selected' : '' }}>
                      {{ $item->name }} ({{ $item->category_name }})
                    </option>
                  @endforeach
                </optgroup>
              </select>
            </div>

            <!-- Category Filter -->
            <div class="col-6 col-md-2 order-md-3">
              <label class="form-label fw-bold small">تصنيف السلعة</label>
              <select name="category" class="form-select border-1 rounded-3" id="compare-category-select"
                      @if(!Auth::check()) onchange="requireLoginForCompare(event);" @else onchange="filterItemsByCategory(this.value);" @endif>
                <option value="">جميع التصنيفات</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- City / Governorate -->
            <div class="col-6 col-md-2 order-md-4">
              <label class="form-label fw-bold small">المحافظة</label>
              <select name="city" class="form-select border-1 rounded-3" id="city-filter"
                      @if(!Auth::check()) onchange="requireLoginForCompare(event);" @else onchange="document.getElementById('compare-filter-form').submit();" @endif>
                <option value="">كل المحافظات</option>
                @foreach($cities as $c)
                  <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
              </select>
            </div>

            <!-- Region / Area -->
            <div class="col-12 col-md-3 order-md-5">
              <label class="form-label fw-bold small">الحي / المنطقة</label>
              <select name="region_id" class="form-select border-1 rounded-3" id="region-filter"
                      @if(!Auth::check()) onchange="requireLoginForCompare(event);" @else onchange="document.getElementById('compare-filter-form').submit();" @endif>
                <option value="">كل المناطق</option>
                @foreach($regions as $r)
                  <option value="{{ $r->id }}" data-city="{{ $r->city_or_governorate }}" {{ request('region_id') == $r->id ? 'selected' : '' }}>
                    {{ $r->city_or_governorate }} - {{ $r->area_name }}
                  </option>
                @endforeach
              </select>
            </div>

          </div>
        </div>
      </form>

      <!-- MODE BANNER -->
      @if($selectedItem && request('item_id') != 'all')
        {{-- SPECIFIC ITEM BANNER --}}
        <div class="bg-white rounded-4 p-3 mb-4 shadow-sm border d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #dcfce7; color: #16a34a; font-size: 1.5rem;">
              <i class="bi bi-box-seam"></i>
            </div>
            <div>
              <h4 class="fw-bolder mb-1">{{ $selectedItem->name }}</h4>
              <div class="text-muted small">
                <i class="bi bi-tag-fill text-success me-1"></i> {{ $selectedItem->category_name }}
                &nbsp;·&nbsp; مقارنة في <strong class="text-dark">{{ $itemPrices->count() }} متجر</strong>
                &nbsp;·&nbsp; <span class="text-primary fw-bold" id="distance-sort-status"><i class="bi bi-compass"></i> مرتب حسب الأقرب مسافة</span>
              </div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-3">
            @if($itemPrices->isNotEmpty())
              @php
                $cheapest = $itemPrices->first();
                $highest  = $itemPrices->last();
                $diff     = $highest->price - $cheapest->price;
              @endphp
              <div class="text-end">
                <div class="small text-muted fw-bold">أرخص سعر متاح:</div>
                <div class="fs-4 fw-bolder brand-green">{{ number_format($cheapest->price, 2) }} ₪</div>
              </div>
              @if($diff > 0)
                <div class="badge bg-success-subtle text-success p-2 rounded-3 text-end">
                  <div>توفير يصل إلى</div>
                  <strong class="fs-6">{{ number_format($diff, 2) }} ₪</strong>
                </div>
              @endif
            @endif

            <button class="btn btn-outline-success rounded-pill px-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#vsProductCollapse">
              <i class="bi bi-arrow-left-right me-1"></i> مقارنة منتجين
            </button>
          </div>
        </div>
      @else
        {{-- ALL / GENERAL STORE COMPARISON BANNER --}}
        <div class="bg-white rounded-4 p-3 mb-4 shadow-sm border d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; background: #dbeafe; color: #2563eb; font-size: 1.5rem;">
              <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div>
              <h4 class="fw-bolder mb-1">
                مقارنة المحلات حسب الأقرب مسافة
                @if(request('category'))
                  <span class="badge bg-success-subtle text-success fs-6 ms-2">أرخص متجر في {{ request('category') }}</span>
                @endif
              </h4>
              <div class="text-muted small">
                <i class="bi bi-shop me-1 text-success"></i> عرض كل متجر والتصنيف الأرخص لديه مرتباً من <strong>الأقرب حتى الأبعد</strong> لموقعك.
              </div>
            </div>
          </div>

          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-primary rounded-pill px-3 py-1.5 fw-bold btn-sm" onclick="getUserLocation()">
              <i class="bi bi-crosshair me-1"></i> تحديث موقعي الحالي
            </button>
            <button class="btn btn-outline-success rounded-pill px-3 py-1.5 fw-bold btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#vsProductCollapse">
              <i class="bi bi-arrow-left-right me-1"></i> مقارنة منتجين
            </button>
          </div>
        </div>
      @endif

      <!-- COLLAPSE: SIDE BY SIDE PRODUCT COMPARISON -->
      <div class="collapse mb-4 {{ request('compare_with_id') ? 'show' : '' }}" id="vsProductCollapse">
        <div class="vs-box">
          <h5 class="fw-bold text-center mb-3"><i class="bi bi-lightning-charge-fill text-warning me-1"></i> مقارنة سلعتين وجهاً لوجه</h5>
          <form action="{{ route('compare') }}" method="GET" class="row g-3 justify-content-center mb-4">
            <div class="col-12 col-md-5">
              <label class="form-label fw-bold small text-muted">السلعة الأولى</label>
              <select name="item_id" class="form-select rounded-3 fw-bold" onchange="this.form.submit()">
                @foreach($allItems as $item)
                  <option value="{{ $item->id }}" {{ (isset($selectedItem) && $selectedItem && $selectedItem->id == $item->id) ? 'selected' : '' }}>
                    {{ $item->name }} ({{ $item->category_name }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-12 col-md-5">
              <label class="form-label fw-bold small text-muted">السلعة الثانية للمقارنة</label>
              <select name="compare_with_id" class="form-select rounded-3 fw-bold" onchange="this.form.submit()">
                <option value="">-- اختر سلعة ثانية --</option>
                @foreach($allItems as $item)
                  @if(!$selectedItem || $item->id != $selectedItem->id)
                    <option value="{{ $item->id }}" {{ request('compare_with_id') == $item->id ? 'selected' : '' }}>
                      {{ $item->name }} ({{ $item->category_name }})
                    </option>
                  @endif
                @endforeach
              </select>
            </div>
          </form>

          @if($compareItem && $selectedItem)
          <div class="row g-4 justify-content-center">
            <!-- Product 1 Card -->
            <div class="col-12 col-md-5">
              <div class="vs-card border-top border-4 border-success text-center">
                <div class="badge bg-success-subtle text-success mb-2 px-3 py-1 fw-bold">السلعة الأولى</div>
                <h4 class="fw-bolder mb-1">{{ $selectedItem->name }}</h4>
                <div class="text-muted small mb-3">{{ $selectedItem->category_name }}</div>
                <div class="p-3 bg-light rounded-3 mb-2">
                  <div class="small text-muted">أرخص سعر</div>
                  <div class="fs-3 fw-bolder brand-green">{{ $selectedItem->best_price ? number_format($selectedItem->best_price, 2) . ' ₪' : 'غير متوفر' }}</div>
                  <div class="small text-muted mt-1"><i class="bi bi-shop me-1"></i> {{ $selectedItem->best_store ?? 'متجر محلي' }}</div>
                </div>
                <a href="{{ route('products.show', $selectedItem->id) }}" class="btn btn-outline-success btn-sm w-100 rounded-pill mt-2">عرض التفاصيل</a>
              </div>
            </div>

            <!-- VS Badge -->
            <div class="col-12 col-md-1 d-flex align-items-center justify-content-center">
              <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center shadow" style="width:48px;height:48px;font-size:1.1rem;">
                VS
              </div>
            </div>

            <!-- Product 2 Card -->
            <div class="col-12 col-md-5">
              <div class="vs-card border-top border-4 border-primary text-center">
                <div class="badge bg-primary-subtle text-primary mb-2 px-3 py-1 fw-bold">السلعة الثانية</div>
                <h4 class="fw-bolder mb-1">{{ $compareItem->name }}</h4>
                <div class="text-muted small mb-3">{{ $compareItem->category_name }}</div>
                <div class="p-3 bg-light rounded-3 mb-2">
                  <div class="small text-muted">أرخص سعر</div>
                  <div class="fs-3 fw-bolder text-primary">{{ $compareItem->best_price ? number_format($compareItem->best_price, 2) . ' ₪' : 'غير متوفر' }}</div>
                  <div class="small text-muted mt-1"><i class="bi bi-shop me-1"></i> {{ $compareItem->best_store ?? 'متجر محلي' }}</div>
                </div>
                <a href="{{ route('products.show', $compareItem->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill mt-2">عرض التفاصيل</a>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- COMPARE DATA & MAP SPLIT -->
      <div class="row g-4 mb-5">

        <!-- TABLE SECTION -->
        <div class="col-12 col-lg-7">
          <div class="bg-white rounded-4 shadow-sm overflow-hidden p-3 h-100 d-flex flex-column">

            <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
              <h5 class="fw-bold mb-0">
                @if($selectedItem && request('item_id') != 'all')
                  <i class="bi bi-list-check text-success me-2"></i>
                  مقارنة أسعار <span class="brand-green">{{ $selectedItem->name }}</span> حسب الأقرب
                @else
                  <i class="bi bi-shop text-success me-2"></i>
                  مقارنة المحلات والتصنيف الأرخص حسب المسافة
                @endif
              </h5>
              <span class="badge bg-light text-muted border" id="store-count-badge">
                {{ ($selectedItem && request('item_id') != 'all') ? $itemPrices->count() : $storeAnalyses->count() }} محل
              </span>
            </div>

            <div class="table-responsive flex-grow-1">
              <table class="table align-middle text-center table-hover mb-0" id="comparison-table">
                <thead class="table-light">
                  @if($selectedItem && request('item_id') != 'all')
                    <tr>
                      <th style="width: 35px;">#</th>
                      <th class="text-start">المحل / المتجر</th>
                      <th>المسافة</th>
                      <th>المنطقة / العنوان</th>
                      <th>السعر</th>
                      <th>الفارق</th>
                      <th>الخريطة</th>
                    </tr>
                  @else
                    <tr>
                      <th style="width: 35px;">#</th>
                      <th class="text-start">المحل / المتجر</th>
                      <th>المسافة</th>
                      <th>المنطقة</th>
                      <th>أرخص تصنيف عنده</th>
                      <th>أرخص سلعة معروضة</th>
                      <th>الخريطة</th>
                    </tr>
                  @endif
                </thead>
                <tbody id="comparison-tbody">
                  @if($selectedItem && request('item_id') != 'all')
                    {{-- SPECIFIC ITEM ROWS --}}
                    @php $minPrice = $itemPrices->isNotEmpty() ? $itemPrices->first()->price : 0; @endphp
                    @forelse($itemPrices as $index => $priceRecord)
                      @php
                        $store = $priceRecord->store;
                        $storeRegion = $store?->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store?->address ?? 'قطاع غزة');
                        $diffFromMin = $priceRecord->price - $minPrice;
                        $rankClass   = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other'));
                      @endphp
                      <tr class="table-compare-row" id="row-store-{{ $store?->id }}"
                          data-store-id="{{ $store?->id }}"
                          data-lat="{{ $store?->latitude ?? '' }}"
                          data-lng="{{ $store?->longitude ?? '' }}"
                          data-price="{{ $priceRecord->price }}"
                          onclick="focusStoreOnMap({{ $store?->id }}, {{ $store?->latitude ?? 'null' }}, {{ $store?->longitude ?? 'null' }})">
                        <td>
                          <span class="rank-badge {{ $rankClass }} row-rank">{{ $index + 1 }}</span>
                        </td>
                        <td class="text-start">
                          <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shop text-success fs-5"></i>
                            <div>
                              <a href="{{ route('shop-details.show', $store?->id) }}" class="fw-bold text-dark text-decoration-none hover-green" onclick="event.stopPropagation();">
                                {{ $store?->name ?? 'متجر' }}
                              </a>
                              @if($store?->phone)
                                <div class="small text-muted" style="font-size:0.75rem;"><i class="bi bi-telephone"></i> {{ $store->phone }}</div>
                              @endif
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="distance-badge distance-val" id="dist-store-{{ $store?->id }}">
                            <i class="bi bi-compass"></i> قيد الحساب...
                          </span>
                        </td>
                        <td>
                          <span class="small text-muted">{{ $storeRegion }}</span>
                        </td>
                        <td>
                          <span class="fw-bold fs-6 {{ $index == 0 ? 'text-success' : 'text-dark' }}">
                            {{ number_format($priceRecord->price, 2) }} ₪
                          </span>
                        </td>
                        <td>
                          @if($diffFromMin == 0)
                            <span class="badge bg-success rounded-pill px-2 py-1" style="font-size:0.75rem;">🥇 الأرخص</span>
                          @else
                            <span class="badge bg-light text-danger border rounded-pill px-2 py-1" style="font-size:0.75rem;">
                              +{{ number_format($diffFromMin, 2) }} ₪
                            </span>
                          @endif
                        </td>
                        <td>
                          @if($store && $store->latitude && $store->longitude)
                            <button
                              type="button"
                              class="btn btn-sm btn-outline-success rounded-circle p-1"
                              style="width: 32px; height: 32px;"
                              title="تحديد على الخريطة"
                              onclick="event.stopPropagation(); focusStoreOnMap({{ $store->id }}, {{ $store->latitude }}, {{ $store->longitude }})">
                              <i class="bi bi-geo-alt-fill"></i>
                            </button>
                          @else
                            <span class="text-muted small">—</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                          <i class="bi bi-inbox fs-1 d-block mb-2 text-warning"></i>
                          <h6 class="fw-bold">لا توجد أسعار مسجلة لهذه السلعة في المنطقة المحددة</h6>
                        </td>
                      </tr>
                    @endforelse
                  @else
                    {{-- ALL STORES / CATEGORY GENERAL COMPARISON ROWS --}}
                    @forelse($storeAnalyses as $index => $itemAnalysis)
                      @php
                        $store = $itemAnalysis['store'];
                        $storeRegion = $store?->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store?->address ?? 'قطاع غزة');
                        $rankClass   = $index == 0 ? 'rank-1' : ($index == 1 ? 'rank-2' : ($index == 2 ? 'rank-3' : 'rank-other'));
                      @endphp
                      <tr class="table-compare-row" id="row-store-{{ $store?->id }}"
                          data-store-id="{{ $store?->id }}"
                          data-lat="{{ $store?->latitude ?? '' }}"
                          data-lng="{{ $store?->longitude ?? '' }}"
                          data-price="{{ $itemAnalysis['cheapest_price'] ?? 999999 }}"
                          onclick="focusStoreOnMap({{ $store?->id }}, {{ $store?->latitude ?? 'null' }}, {{ $store?->longitude ?? 'null' }})">
                        <td>
                          <span class="rank-badge {{ $rankClass }} row-rank">{{ $index + 1 }}</span>
                        </td>
                        <td class="text-start">
                          <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-shop text-success fs-5"></i>
                            <div>
                              <a href="{{ route('shop-details.show', $store?->id) }}" class="fw-bold text-dark text-decoration-none hover-green" onclick="event.stopPropagation();">
                                {{ $store?->name ?? 'متجر' }}
                              </a>
                              @if($store?->phone)
                                <div class="small text-muted" style="font-size:0.75rem;"><i class="bi bi-telephone"></i> {{ $store->phone }}</div>
                              @endif
                            </div>
                          </div>
                        </td>
                        <td>
                          <span class="distance-badge distance-val" id="dist-store-{{ $store?->id }}">
                            <i class="bi bi-compass"></i> قيد الحساب...
                          </span>
                        </td>
                        <td>
                          <span class="small text-muted">{{ $storeRegion }}</span>
                        </td>
                        <td>
                          <span class="category-deal-badge">
                            <i class="bi bi-patch-check-fill text-success"></i>
                            {{ $itemAnalysis['cheapest_category'] }}
                          </span>
                        </td>
                        <td>
                          @if($itemAnalysis['cheapest_price'])
                            <div class="fw-bold text-dark small">{{ $itemAnalysis['cheapest_item'] }}</div>
                            <span class="badge bg-success-subtle text-success fw-bold">{{ number_format($itemAnalysis['cheapest_price'], 2) }} ₪</span>
                          @else
                            <span class="text-muted small">عروض عامة</span>
                          @endif
                        </td>
                        <td>
                          @if($store && $store->latitude && $store->longitude)
                            <button
                              type="button"
                              class="btn btn-sm btn-outline-success rounded-circle p-1"
                              style="width: 32px; height: 32px;"
                              title="تحديد على الخريطة"
                              onclick="event.stopPropagation(); focusStoreOnMap({{ $store->id }}, {{ $store->latitude }}, {{ $store->longitude }})">
                              <i class="bi bi-geo-alt-fill"></i>
                            </button>
                          @else
                            <span class="text-muted small">—</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                          <i class="bi bi-shop fs-1 d-block mb-2 text-warning"></i>
                          <h6 class="fw-bold">لا توجد محلات مسجلة في هذا النطاق</h6>
                        </td>
                      </tr>
                    @endforelse
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- LEAFLET INTERACTIVE MAP -->
        <div class="col-12 col-lg-5">
          <div class="bg-white rounded-4 shadow-sm overflow-hidden p-3 h-100 d-flex flex-column">
            <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
              <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i> خريطة المحلات</h5>
              <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill py-1 px-3 fw-bold" onclick="resetMapView()">
                <i class="bi bi-arrows-fullscreen"></i> شمول الكل
              </button>
            </div>
            <div id="compare-map" class="flex-grow-1 shadow-sm"></div>
          </div>
        </div>

      </div>

      <!-- SMART STATS SECTION -->
      <section class="pb-5">
        <div class="section-title">
          <span class="bar"></span>
          <h2><i class="bi bi-bar-chart-line-fill text-success me-1"></i> إحصائيات المقارنة الذكية</h2>
        </div>

        <div class="row g-4">
          <!-- Card 1: Nearest & Cheapest Store -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-4 shadow-sm h-100 border-top border-4 border-success">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i> أفضل صفقة وأقرب متجر</h5>
                <span class="badge bg-success">أوفر شراء</span>
              </div>
              <div id="smart-nearest-card">
                @if($storeAnalyses->isNotEmpty())
                  @php $firstStore = $storeAnalyses->first(); @endphp
                  <div class="store-box p-3 bg-light rounded-3 mb-3">
                    <div class="fw-bold fs-5 text-dark mb-1">{{ $firstStore['store']?->name }}</div>
                    <div class="small text-muted mb-2"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $firstStore['store']?->region?->area_name ?? 'غزة' }}</div>
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="text-muted small">الأرخص في:</span>
                      <strong class="text-success">{{ $firstStore['cheapest_category'] }}</strong>
                    </div>
                  </div>
                  <a href="{{ route('shop-details.show', $firstStore['store']?->id) }}" class="btn btn-outline-success btn-sm w-100 rounded-pill fw-bold">
                    <i class="bi bi-shop me-1"></i> زيارة المتجر وعرض السلع
                  </a>
                @endif
              </div>
            </div>
          </div>

          <!-- Card 2: Price Range & Average -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-4 shadow-sm h-100 border-top border-4 border-primary">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-calculator-fill text-primary me-2"></i> نطاق المقارنة</h5>
                <span class="badge bg-light text-dark border">{{ $storeAnalyses->count() }} محلات في النطاق</span>
              </div>
              <div class="d-flex flex-column gap-2 mb-3">
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded-3">
                  <span class="small text-muted fw-bold">أرخص سعر في الخضراوات:</span>
                  <strong class="text-success">متوفر في أقرب المتاجر</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded-3">
                  <span class="small text-muted fw-bold">المقارنة التلقائية:</span>
                  <strong class="text-primary">مرتبة من الأقرب للأبعد</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded-3">
                  <span class="small text-muted fw-bold">تحديث الأسعار:</span>
                  <strong class="text-success">لحظة بلحظة 🟢</strong>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 3: Quick Navigation -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-4 shadow-sm h-100 border-top border-4 border-warning">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-lightning-fill text-warning me-2"></i> سلع شائعة للمقارنة</h5>
                <span class="badge bg-warning-subtle text-dark">سريعة</span>
              </div>
              <div class="d-flex flex-column gap-2">
                @foreach($allItems->take(4) as $quickItem)
                  <a href="{{ route('compare', ['item_id' => $quickItem->id]) }}" class="text-decoration-none d-flex align-items-center justify-content-between p-2 rounded-3 bg-light text-dark hover-green">
                    <span class="fw-bold small"><i class="bi bi-box-seam text-success me-1"></i> {{ $quickItem->name }}</span>
                    <span class="badge bg-success-subtle text-success">{{ $quickItem->best_price ? number_format($quickItem->best_price, 2) . ' ₪' : 'قارن' }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>

        </div>
      </section>

    </div>
  </main>

  <!-- FOOTER -->
  @include('partials.footer')

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    // ===== Leaflet Map Setup =====
    let compareMap;
    let markersMap = {};
    let allBounds = [];
    let userMarker = null;

    // Default to Gaza center if geolocation is not available
    let currentUserLat = 31.501;
    let currentUserLng = 34.466;

    const storesData = @json($mapStores);

    // Haversine Distance Calculation (KM)
    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371; // Earth's radius in km
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

    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Leaflet Map
      compareMap = L.map('compare-map').setView([currentUserLat, currentUserLng], 12);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
      }).addTo(compareMap);

      // Create Store Pins on Map
      storesData.forEach(store => {
        if (store.latitude && store.longitude) {
          const lat = parseFloat(store.latitude);
          const lng = parseFloat(store.longitude);
          allBounds.push([lat, lng]);

          const customIcon = L.divIcon({
            className: 'custom-pin-wrap',
            html: `<div class="custom-store-pin" id="pin-store-${store.id}"><i class="bi bi-shop"></i></div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -20]
          });

          const storeImg = store.image_url || (store.image 
            ? (store.image.startsWith('http') || store.image.startsWith('data:image') ? store.image : '{{ asset("storage") }}/' + store.image)
            : '{{ asset("assets/imges/shops.png") }}');

          const regionName = store.region ? (store.region.city_or_governorate + ' - ' + store.region.area_name) : (store.address || 'غزة');
          const detailsUrl = `{{ url('/shop_details') }}/${store.id}`;
          const googleNavUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

          const popupHtml = `
            <div class="store-popup-card">
              <div class="store-popup-header">
                <img src="${storeImg}" class="store-popup-img" alt="${store.name}">
                <div>
                  <h6 class="store-popup-title">${store.name}</h6>
                  <div class="small text-muted"><i class="bi bi-geo-alt text-success"></i> ${regionName}</div>
                </div>
              </div>
              <div class="small text-muted mb-2" id="map-popup-dist-${store.id}"></div>
              <a href="${detailsUrl}" class="store-popup-btn">
                <i class="bi bi-shop"></i> عرض صفحة المحل
              </a>
              <a href="${googleNavUrl}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-secondary w-100 rounded-2 mt-1 fw-bold" style="font-size:0.8rem;">
                <i class="bi bi-map"></i> الاتجاهات عبر خرائط جوجل
              </a>
            </div>
          `;

          const marker = L.marker([lat, lng], { icon: customIcon }).addTo(compareMap);
          marker.bindPopup(popupHtml);
          markersMap[store.id] = marker;
        }
      });

      if (allBounds.length > 0) {
        compareMap.fitBounds(allBounds, { padding: [30, 30] });
      }

      // Try Geolocation
      getUserLocation();
    });

    // ===== Get User Location and Re-sort Table from Nearest to Furthest =====
    function getUserLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            currentUserLat = position.coords.latitude;
            currentUserLng = position.coords.longitude;

            // Add/update user marker on map
            if (userMarker) {
              userMarker.setLatLng([currentUserLat, currentUserLng]);
            } else {
              const userIcon = L.divIcon({
                className: 'user-pin-wrap',
                html: `<div class="user-location-pin" title="موقعك الحالي"></div>`,
                iconSize: [22, 22],
                iconAnchor: [11, 11]
              });
              userMarker = L.marker([currentUserLat, currentUserLng], { icon: userIcon }).addTo(compareMap);
              userMarker.bindPopup('<div class="fw-bold p-1 text-center">📍 موقعك الحالي</div>');
            }

            updateDistancesAndSortTable();
          },
          function(err) {
            // If location denied or failed, use default Gaza center
            updateDistancesAndSortTable();
          },
          { timeout: 6000, enableHighAccuracy: true }
        );
      } else {
        updateDistancesAndSortTable();
      }
    }

    // ===== Calculate Distance for each row and sort from Nearest to Furthest =====
    function updateDistancesAndSortTable() {
      const tbody = document.getElementById('comparison-tbody');
      if (!tbody) return;

      const rows = Array.from(tbody.querySelectorAll('.table-compare-row'));
      if (rows.length === 0) return;

      // Calculate distance for each row
      rows.forEach(row => {
        const lat = parseFloat(row.getAttribute('data-lat'));
        const lng = parseFloat(row.getAttribute('data-lng'));
        const storeId = row.getAttribute('data-store-id');

        if (!isNaN(lat) && !isNaN(lng)) {
          const distKm = calculateDistance(currentUserLat, currentUserLng, lat, lng);
          row.setAttribute('data-distance', distKm);
          const distBadge = row.querySelector('.distance-val');
          if (distBadge) {
            distBadge.innerHTML = `<i class="bi bi-compass text-primary"></i> ${formatDistance(distKm)}`;
          }

          // Update popup distance text if open
          const popupDist = document.getElementById('map-popup-dist-' + storeId);
          if (popupDist) {
            popupDist.innerHTML = `<i class="bi bi-compass text-primary"></i> يبعد عنك: ${formatDistance(distKm)}`;
          }
        } else {
          row.setAttribute('data-distance', 999999);
          const distBadge = row.querySelector('.distance-val');
          if (distBadge) {
            distBadge.innerHTML = `<i class="bi bi-compass text-muted"></i> غير محدد`;
          }
        }
      });

      // Sort rows from nearest to furthest
      rows.sort((a, b) => {
        const distA = parseFloat(a.getAttribute('data-distance')) || 999999;
        const distB = parseFloat(b.getAttribute('data-distance')) || 999999;
        return distA - distB;
      });

      // Re-append sorted rows and update rank numbers
      rows.forEach((row, idx) => {
        tbody.appendChild(row);
        const rankSpan = row.querySelector('.row-rank');
        if (rankSpan) {
          rankSpan.innerText = (idx + 1);
          rankSpan.className = 'rank-badge row-rank ' + (idx === 0 ? 'rank-1' : (idx === 1 ? 'rank-2' : (idx === 2 ? 'rank-3' : 'rank-other')));
        }
      });

      const distStatus = document.getElementById('distance-sort-status');
      if (distStatus) {
        distStatus.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> تم الترتيب تلقائياً من الأقرب مسافة إلى الأبعد';
      }
    }

    // ===== Focus Store on Map when row is clicked =====
    function focusStoreOnMap(storeId, lat, lng) {
      document.querySelectorAll('.table-compare-row').forEach(r => r.classList.remove('active-row'));
      const row = document.getElementById('row-store-' + storeId);
      if (row) row.classList.add('active-row');

      document.querySelectorAll('.custom-store-pin').forEach(p => p.classList.remove('selected'));
      const pin = document.getElementById('pin-store-' + storeId);
      if (pin) pin.classList.add('selected');

      if (markersMap[storeId] && lat && lng) {
        compareMap.setView([lat, lng], 15, { animate: true });
        markersMap[storeId].openPopup();
      }
    }

    function resetMapView() {
      if (allBounds.length > 0) {
        compareMap.fitBounds(allBounds, { padding: [30, 30] });
      }
    }

    // Dynamic Category Filter for Items
    function filterItemsByCategory(catName) {
      const itemSelect = document.getElementById('compare-item-select');
      if (itemSelect && catName) {
        let found = false;
        Array.from(itemSelect.options).forEach(opt => {
          const optCat = opt.getAttribute('data-category');
          if (optCat === catName && !found) {
            opt.selected = true;
            found = true;
          }
        });
        if (!found) {
          itemSelect.value = 'all';
        }
      }
      document.getElementById('compare-filter-form').submit();
    }

    // Dynamic Region Filter based on City
    const cityFilter = document.getElementById('city-filter');
    const regionFilter = document.getElementById('region-filter');
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

    // Require Login For Guest comparison customization
    function requireLoginForCompare(e) {
      @if(!Auth::check())
        if (e && e.preventDefault) e.preventDefault();
        if (e && e.stopPropagation) e.stopPropagation();
        window.location.href = '{{ route("login") }}';
        return false;
      @endif
      return true;
    }
  </script>
</body>

</html>

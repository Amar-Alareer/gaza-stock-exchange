<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | {{ $store->name ?? 'صفحة المحل' }}</title>
  <meta name="description" content="استعرض تفاصيل وأسعار السلع المتوفرة لدى {{ $store->name ?? 'المحل' }}">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=8') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/imges/logo.png') }}">
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
          <form action="{{ route('prices') }}" method="GET" class="d-flex align-items-center">
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

  @php
    $storeImg = $store->image 
        ? (str_starts_with($store->image, 'http') ? $store->image : asset('storage/'.$store->image))
        : asset('assets/imges/shops.png');
    $regionName = $store->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store->address ?? 'قطاع غزة');
  @endphp

  <!-- STORE DETAILS HERO BANNER -->
  <section class="shops-header-banner py-5">
    <img src="{{ asset('assets/imges/baner.png') }}" alt="خريطة" class="hero-bg-img">
    <div class="container position-relative">
      <div class="d-flex justify-content-between align-items-start">
        <div class="text-end">
          <h1 class="page-header-title mb-0"><span class="brand-green">صفحة</span> <span class="brand-white">المحل</span> <i class="bi bi-shop text-success fs-3"></i></h1>
          <p class="page-header-sub">بيانات وأسعار السلع المتوفرة لدى المتجر</p>
        </div>
      </div>

      <!-- CENTER OVERLAY STORE CARD -->
      <div class="store-hero-overlay-card mx-auto text-center bg-white p-4 rounded-4 shadow-lg">
        <div class="store-badge-icon mb-2">
          <img src="{{ $storeImg }}" alt="{{ $store->name }}" class="rounded-circle shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 3px solid var(--brand-green);">
        </div>
        <h3 class="fw-bolder mb-2">{{ $store->name }}</h3>
        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $regionName }}</p>
        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
          <a href="{{ route('map') }}?search={{ urlencode($store->name) }}" class="btn btn-outline-success rounded-pill fw-bold px-3 py-1.5 small d-inline-flex align-items-center gap-1">
            <i class="bi bi-geo-alt"></i> موقعه على الخريطة
          </a>
          @if($store->phone)
            <a href="tel:{{ $store->phone }}" class="btn btn-success bg-gradient border-0 px-3 py-1.5 rounded-pill fw-bold small d-inline-flex align-items-center gap-1 text-white">
              <i class="bi bi-telephone-fill"></i> اتصال مباشر
            </a>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <div class="row g-4 pt-3">
        <!-- SIDEBAR INFO CARD (RIGHT IN RTL) -->
        <div class="col-12 col-lg-4">
          <div class="bg-white rounded-4 shadow-sm p-4 text-end position-relative h-100">
            <div class="bell-icon-top mb-3 d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-light text-success fs-5">
              <i class="bi bi-shop"></i>
            </div>

            <div class="section-title mb-3">
              <span class="bar"></span>
              <h4 class="fw-bold m-0">{{ $store->name }}</h4>
            </div>

            <div class="shop-info-list d-flex flex-column gap-3 mb-4">
              <div class="d-flex align-items-center gap-2">
                <span class="fs-5 text-success"><i class="bi bi-geo-alt-fill"></i></span>
                <div>
                  <div class="fw-bold text-dark">المنطقة والفرع</div>
                  <div class="small text-muted">{{ $regionName }}</div>
                </div>
              </div>

              @if($store->address)
                <div class="d-flex align-items-center gap-2">
                  <span class="fs-5 text-muted"><i class="bi bi-pin-map"></i></span>
                  <div>
                    <div class="fw-bold text-dark">العنوان بالتفصيل</div>
                    <div class="small text-muted">{{ $store->address }}</div>
                  </div>
                </div>
              @endif

              <div class="d-flex align-items-start gap-2">
                <span class="fs-5 text-muted"><i class="bi bi-clock"></i></span>
                <div>
                  <div class="fw-bold text-dark">ساعات العمل</div>
                  <div class="small text-muted">{{ $store->working_hours ?? 'يومياً من 8:00 صباحاً حتى 10:00 مساءً' }}</div>
                </div>
              </div>
            </div>

            <a href="{{ route('map') }}?search={{ urlencode($store->name) }}" class="btn-locate w-100 justify-content-center py-2.5 mb-3 text-decoration-none d-flex align-items-center gap-2">
              <i class="bi bi-geo-alt-fill"></i> عرض الموقع على الخريطة
            </a>

            @if($store->latitude && $store->longitude)
              <a href="https://www.google.com/maps/dir/?api=1&destination={{ $store->latitude }},{{ $store->longitude }}" target="_blank" rel="noopener" class="btn btn-outline-secondary w-100 justify-content-center py-2 mb-4 rounded-3 d-flex align-items-center gap-2 small fw-bold">
                <i class="bi bi-map-fill text-success"></i> فتح مسار التنقل (Google Maps)
              </a>
            @endif

            <div class="contact-rows small d-flex flex-column gap-2 border-top pt-3">
              @if($store->phone)
                <div class="d-flex align-items-center justify-content-between">
                  <a href="tel:{{ $store->phone }}" class="text-decoration-none text-dark fw-bold"><i class="bi bi-telephone-fill text-success me-1"></i> {{ $store->phone }}</a>
                  <span class="text-muted"><i class="bi bi-phone"></i></span>
                </div>
              @endif
              @if($store->facebook_url)
                <div class="d-flex align-items-center justify-content-between">
                  <a href="{{ $store->facebook_url }}" target="_blank" rel="noopener" class="text-decoration-none text-primary fw-bold"><i class="bi bi-facebook me-1"></i> صفحة الفيسبوك</a>
                  <span class="text-primary"><i class="bi bi-globe"></i></span>
                </div>
              @endif
            </div>
          </div>
        </div>

        <!-- PRODUCTS GRID (LEFT IN RTL) -->
        <div class="col-12 col-lg-8">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-box-seam text-success me-1"></i> السلع والأسعار المسجلة ({{ $storePrices->count() }})</h4>
            <div class="small text-muted"><i class="bi bi-clock me-1"></i> اخر تحديث: اليوم</div>
          </div>

          <!-- PRODUCTS GRID -->
          @if($storePrices->isEmpty())
            <div class="bg-white rounded-4 shadow-sm p-5 text-center my-3 text-muted">
              <div style="font-size: 3rem;"><i class="bi bi-cart-x"></i></div>
              <h5 class="fw-bold mt-2">لا توجد سلع مضافة حالياً لهذا المحل</h5>
              <p class="text-muted small">يمكنك تصفح جميع الأسعار ومقارنتها عبر صفحة الأسعار العامة</p>
              <a href="{{ route('prices') }}" class="btn btn-success rounded-pill px-4 mt-2"><i class="bi bi-arrow-clockwise me-1"></i> تصفح كل الأسعار</a>
            </div>
          @else
            <div class="row g-3 pb-4">
              @foreach($storePrices as $sp)
                @php
                  $item = $sp->item;
                  if (!$item) continue;
                  $categoryName = $item->categoryRelation?->name ?? $item->category ?? 'مواد غذائية';
                  $catIcons = ['خضراوات'=>'bi-basket','فواكه'=>'bi-apple','لحوم'=>'bi-egg-fried','مواد غذائية'=>'bi-cart3','زيوت ودهون'=>'bi-droplet','حبوب'=>'bi-box-seam','أسماك'=>'bi-water','ألبان'=>'bi-cup-hot','مخبوزات'=>'bi-cookie','وقود'=>'bi-fuel-pump'];
                  $catIcon = $catIcons[$categoryName] ?? 'bi-box-seam';
                @endphp
                <div class="col-12 col-sm-6 col-md-4">
                  <div class="product-card h-100">
                    <div class="category-badge"><i class="bi {{ $catIcon }}"></i></div>
                    <div class="product-img-wrap">
                      @if($item->image_url && str_starts_with($item->image_url, 'http'))
                        <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="product-img" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                        <div style="display:none;font-size:2.5rem;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                      @elseif($item->image_url && file_exists(public_path('storage/'.$item->image_url)))
                        <img src="{{ asset('storage/'.$item->image_url) }}" alt="{{ $item->name }}" class="product-img">
                      @else
                        <div style="font-size:3rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                      @endif
                    </div>
                    <div class="product-info text-end">
                      <div class="product-name">{{ $item->name }}</div>
                      <div class="product-sub"><i class="bi bi-tag me-1"></i> {{ $categoryName }}</div>
                      <div class="product-price">{{ number_format($sp->price, 2) }} ₪</div>
                    </div>
                    <a href="{{ route('compare') }}?search={{ urlencode($item->name) }}" class="btn-compare-store text-decoration-none text-center d-block">
                      <i class="bi bi-arrow-left-right me-1"></i> قارن السعر مع المحلات
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="main-footer">
    <div class="container text-center">
      <div class="footer-logo d-flex align-items-center justify-content-center gap-2">
        <img src="{{ asset('assets/imges/logo.png') }}" alt="وفر كاش" class="footer-logo-img">
        <span class="footer-logo-text"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
      </div>
      <div class="footer-tagline">منصة لمتابعة أسعار السوق في منطقتك</div>
      <div class="footer-links d-flex justify-content-center gap-4">
        <a href="#">سياسة الخصوصية</a>
        <a href="#">الشروط والاحكام</a>
        <a href="#">تواصل معنا</a>
      </div>
      <div class="copyright">© 2026 وفر كاش، جميع الحقوق محفوظة</div>
    </div>
  </footer>

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>
</body>

</html>
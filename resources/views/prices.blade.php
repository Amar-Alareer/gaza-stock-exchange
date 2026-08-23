<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | كل الأسعار</title>
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/css/style.css?v=2')}}">
  <link rel="icon" type="image/png" href="{{asset('assets/imges/logo.png')}}">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-wafar">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">

        <div class="brand-logo order-1">
          <a href="{{ route('index') }}" class="d-flex align-items-center gap-2">
            <img src="{{asset('assets/imges/logo.png')}}" alt="وفر كاش" class="logo-icon">
            <span class="logo-text d-none d-sm-block">
              <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
              <span class="en">CASH WAFAR</span>
            </span>
          </a>
        </div>

        <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
          <li class="nav-item"><a class="nav-link" href="{{route('index')}}">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('map') }}">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <div class="search-pill">
            <input type="text" placeholder="طحين ، سكر">
            <button type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
            </button>
          </div>

          <a href="{{route('compare')}}" class="btn-compare">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
            مقارنة الاسعار
          </a>
          <a href="{{route('profile')}}" class="icon-circle" title="الملف الشخصي">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
          </a>
        </div>

        <div class="d-flex d-lg-none align-items-center gap-2 order-3">
          <a href="{{ route('profile') }}" class="icon-circle">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
          </a>
          <button class="navbar-toggler-custom">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
          </button>
        </div>
      </div>

      <div class="d-flex d-lg-none align-items-center gap-2 mt-2">
        <div class="search-pill flex-grow-1">
          <input type="text" placeholder="طحين ، سكر">
          <button type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
        </div>
        <a href="{{ route('compare') }}" class="btn-compare">مقارنة الاسعار 🪙</a>
      </div>
    </div>

    <div class="mobile-nav-panel">
      <a class="nav-link" href="{{ route('index') }}">الرئيسية</a>
      <a class="nav-link" href="{{ route('map') }}">الخريطة</a>
      <a class="nav-link" href="{{ route('shops') }}">المحلات</a>
      <a class="nav-link active" href="{{ route('prices') }}">الاسعار</a>
    </div>
  </nav>

  <!-- HERO BANNER -->
  <section class="shops-header-banner py-5">
    <img src="{{asset('assets/imges/map.png')}}" alt="أسعار السلع" class="hero-bg-img">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-4">
      <div class="shops-banner-graphic d-none d-md-block">
        <img src="{{asset('assets/imges/item.png')}}" alt="أسعار السلع" class="banner-store-img" style="max-height: 140px;">
      </div>

      <div class="shops-banner-text text-end flex-grow-1" style="max-width: 500px;">
        <h1 class="page-header-title mb-1">كل <span class="brand-green">الأسعار</span> في مكان واحد</h1>
        <p class="page-header-sub mb-3">تصفح أسعار السلع المحدثة في جميع المناطق</p>

        <div class="search-pill w-100 bg-dark bg-opacity-50 p-1 border border-secondary border-opacity-25" style="border-radius: 28px;">
          <input type="text" placeholder="البحث عن سلعة معينة" class="px-3">
          <button type="button" class="btn-locate py-2 px-3 fs-6">🔍</button>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- CATEGORY FILTER PILLS BAR -->
      <div class="d-flex justify-content-end gap-2 mb-4 flex-wrap">
        <button class="btn btn-dark rounded-pill px-4 fw-bold">الكل</button>
        <button class="btn btn-light rounded-pill px-3 fw-bold border">مواد تموينية</button>
        <button class="btn btn-light rounded-pill px-3 fw-bold border">خضراوات</button>
        <button class="btn btn-light rounded-pill px-3 fw-bold border">لحوم</button>
        <button class="btn btn-light rounded-pill px-3 fw-bold border">وقود</button>
      </div>

      <!-- PRODUCTS GRID -->
      <div class="row g-4 pb-4">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-45%</div>
            <div class="category-badge">🌾</div>
            <div class="product-img-wrap">
              <img src="{{asset('assets/imges/then.png')}}" alt="طحين" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">طحين</div>
              <div class="product-sub">كيس 25 كيلو</div>
              <div class="product-price">35 شيكل</div>
            </div>
            <a href="{{ route('compare') }}" class="btn-compare-store text-center text-decoration-none d-block">قارن مع 12 محل</a>
          </div>
        </div>
      </div>

      <button class="btn-more mb-5">تحميل المزيد....</button>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="main-footer">
    <div class="container text-center">
      <div class="footer-logo d-flex align-items-center justify-content-center gap-2">
        <img src="{{asset('assets/imges/logo.png')}}" alt="وفر كاش" class="footer-logo-img">
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

  <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/script.js')}}"></script>
</body>

</html>
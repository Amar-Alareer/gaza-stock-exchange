<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | الملف الشخصي</title>
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="icon" type="image/png" href="{{asset('assets/imges/logo.png')}}">
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
          <li class="nav-item"><a class="nav-link" href="{{route('index')}}">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('map') }}">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <div class="search-pill">
            <input type="text" placeholder="طحين ، سكر">
            <button type="button">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5" />
                <path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" />
              </svg>
            </button>
          </div>

          <a href="{{route('compare')}}" class="btn-compare">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            مقارنة الاسعار
          </a>
          <a href="{{route('profile')}}" class="icon-circle" title="الملف الشخصي">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </a>
        </div>

        <div class="d-flex d-lg-none align-items-center gap-2 order-3">
          <a href="{{ route('profile') }}" class="icon-circle">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </a>
          <button class="navbar-toggler-custom" onclick="document.querySelector('.mobile-nav-panel').classList.toggle('open')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <!-- PROFILE HEADER BANNER -->
  <section class="profile-header-banner">
    <img src="{{ asset('assets/imges/map.png') }}" alt="خريطة" class="hero-bg-img">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- LOGOUT BUTTON -->
        <a href="{{route('login')}}" class="btn-logout">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
          تسجيل خروج
        </a>

        <!-- USER INFO -->
        <div class="d-flex align-items-center gap-3 text-end">
          <div>
            <h1 class="user-name mb-1">محمد</h1>
            <div class="user-location">📍 حي التفاح - غزة</div>
          </div>
          <div class="avatar-wrapper">
            <img src="{{ asset('assets/imges/pngtree-character-default-avatar-image_2237203.jpg') }}" alt="محمد" class="user-avatar">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body pt-0">
    <div class="container">

      <!-- STATS ROW -->
      <div class="profile-stats-row row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number">12</div>
            <div class="stat-label">سلعة مفضلة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number">4</div>
            <div class="stat-label">تنبيهات نشطة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number">3</div>
            <div class="stat-label">محلات مفضلة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number text-green">200 ₪</div>
            <div class="stat-label">توفير هذا الشهر</div>
          </div>
        </div>
      </div>

      <!-- TABS BAR -->
      <div class="profile-tabs-bar mb-4">
        <button class="tab-btn active">السلع المفضلة</button>
        <button class="tab-btn">تنبيهات الاسعار</button>
        <button class="tab-btn">إعدادات الحساب</button>
      </div>

      <!-- FAVORITE ITEMS GRID -->
      <div class="row g-4 pb-5">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-45%</div>
            <div class="category-badge">🌾</div>
            <div class="product-img-wrap">
              <img src="{{ asset('assets/imges/then.png') }}" alt="طحين" class="product-img">
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

    </div>
  </main>

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js') }}"></script>
</body>
</html>
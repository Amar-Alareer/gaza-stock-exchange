<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | الملف الشخصي</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css?v=2">
  <link rel="icon" type="image/png" href="imges/logo.png">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-wafar">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">

        <div class="brand-logo order-1">
          <a href="index.html" class="d-flex align-items-center gap-2">
            <img src="imges/logo.png" alt="وفر كاش" class="logo-icon">
            <span class="logo-text d-none d-sm-block">
              <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
              <span class="en">CASH WAFAR</span>
            </span>
          </a>
        </div>

        <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
          <li class="nav-item"><a class="nav-link" href="index.html">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="map.html">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link" href="shops.html">المحلات</a></li>
          <li class="nav-item"><a class="nav-link" href="prices.html">الاسعار</a></li>
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

          <a href="compare.html" class="btn-compare">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            مقارنة الاسعار
          </a>
          <a href="profile.html" class="icon-circle active" title="الملف الشخصي">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </a>
        </div>

        <div class="d-flex d-lg-none align-items-center gap-2 order-3">
          <a href="profile.html" class="icon-circle">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path
                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg>
          </a>
          <button class="navbar-toggler-custom">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
              <path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
          </button>
        </div>
      </div>

      <!-- MOBILE SECONDARY ROW -->
      <div class="d-flex d-lg-none align-items-center gap-2 mt-2">
        <div class="search-pill flex-grow-1">
          <input type="text" placeholder="طحين ، سكر">
          <button type="button"><svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg></button>
        </div>
        <a href="compare.html" class="btn-compare">مقارنة الاسعار 🪙</a>
      </div>
    </div>

    <div class="mobile-nav-panel">
    <a class="nav-link" href="index.html">الرئيسية</a>
    <a class="nav-link" href="map.html">الخريطة</a>
    <a class="nav-link" href="shops.html">المحلات</a>
    <a class="nav-link" href="prices.html">الاسعار</a>
    </div>
  </nav>

  <!-- PROFILE HEADER BANNER -->
  <section class="profile-header-banner">
    <img src="imges/map.png" alt="خريطة" class="hero-bg-img">
    <div class="container">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <!-- LOGOUT BUTTON -->
        <a href="login.html" class="btn-logout">
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
            <h1 class="user-name mb-1">محمد </h1>
            <div class="user-location">📍 حي التفاح - غزة</div>
          </div>
          <div class="avatar-wrapper">
            <img src="imges/pngtree-character-default-avatar-image_2237203.jpg" alt="محمد " class="user-avatar">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body pt-0">
    <div class="container">

      <!-- STATS ROW (4 CARDS) -->
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

      <!-- FAVORITE ITEMS GRID (8 CARDS) -->
      <div class="row g-4 pb-5">
        <!-- Item 1: طحين -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-45%</div>
            <div class="category-badge">🌾</div>
            <div class="product-img-wrap">
              <img src="imges/then.png" alt="طحين" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">طحين</div>
              <div class="product-sub">كيس 25 كيلو</div>
              <div class="product-price">35 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 12 محل</button>
          </div>
        </div>

        <!-- Item 2: مانجا -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-21%</div>
            <div class="category-badge">🥭</div>
            <div class="product-img-wrap">
              <img src="imges/mango.png" alt="مانجا" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">مانجا</div>
              <div class="product-sub">1 كيلو</div>
              <div class="product-price">10 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 3: باذنجان -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-25%</div>
            <div class="category-badge">🍆</div>
            <div class="product-img-wrap">
              <img src="imges/bad.png" alt="باذنجان" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">باذنجان</div>
              <div class="product-sub">4 علب</div>
              <div class="product-price">10 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 4: قهوة -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-29%</div>
            <div class="category-badge">☕</div>
            <div class="product-img-wrap">
              <img src="imges/coffee.png" alt="قهوة" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">قهوة</div>
              <div class="product-sub">علبة</div>
              <div class="product-price">35 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 5: دجاج -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-21%</div>
            <div class="category-badge">🍗</div>
            <div class="product-img-wrap">
              <img src="imges/chkn.png" alt="دجاج" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">دجاج</div>
              <div class="product-sub">1 كيلو</div>
              <div class="product-price">20 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 6: شامبو 350ml -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-21%</div>
            <div class="category-badge">🧴</div>
            <div class="product-img-wrap">
              <img
                src="imges/shampo1.png"
                alt="شامبو" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">شامبو</div>
              <div class="product-sub">350 ملم</div>
              <div class="product-price">11 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 7: شامبو 750ml -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-25%</div>
            <div class="category-badge">🧴</div>
            <div class="product-img-wrap">
              <img
                src="imges/shampo.png"
                alt="شامبو" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">شامبو</div>
              <div class="product-sub">750 ملم</div>
              <div class="product-price">10 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 10 محل</button>
          </div>
        </div>

        <!-- Item 8: طحين 2 -->
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="product-card">
            <div class="discount-badge">-45%</div>
            <div class="category-badge">🌾</div>
            <div class="product-img-wrap">
              <img src="imges/then.png" alt="طحين" class="product-img">
            </div>
            <div class="product-info">
              <div class="product-name">طحين</div>
              <div class="product-sub">كيس 25 كيلو</div>
              <div class="product-price">35 شيكل</div>
            </div>
            <button class="btn-compare-store">قارن مع 12 محل</button>
          </div>
        </div>
      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="main-footer">
    <div class="container text-center">
      <div class="footer-logo d-flex align-items-center justify-content-center gap-2">
        <img src="imges/logo.png" alt="وفر كاش" class="footer-logo-img">
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

  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/script.js"></script>
</body>

</html><?php /**PATH C:\xampp\htdocs\gaza-stock-exchange\resources\views/profile.blade.php ENDPATH**/ ?>
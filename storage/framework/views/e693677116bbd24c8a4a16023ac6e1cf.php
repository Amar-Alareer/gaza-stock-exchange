<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>وفر كاش | الرئيسية</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css?v=3">
<link rel="icon" type="image/png" href="imges/logo.png">
<?php echo $__env->make('navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-wafar">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100 flex-nowrap">

      <!-- LOGO (RIGHT IN RTL) -->
      <div class="brand-logo order-1">
        <a href="index.html" class="d-flex align-items-center gap-2">
          <img src="imges/logo.png" alt="وفر كاش" class="logo-icon">
          <span class="logo-text d-none d-sm-block">
            <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
            <span class="en">CASH WAFAR</span>
          </span>
        </a>
      </div>

      <!-- NAV LINKS (MIDDLE) -->
      <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
        <li class="nav-item"><a class="nav-link active" href="index.html">الرئيسية</a></li>
        <li class="nav-item"><a class="nav-link" href="map.html">الخريطة</a></li>
        <li class="nav-item"><a class="nav-link" href="shops.html">المحلات</a></li>
        <li class="nav-item"><a class="nav-link" href="prices.html">الاسعار</a></li>
      </ul>

      <!-- CONTROLS (LEFT IN RTL) -->
      <div class="d-none d-lg-flex align-items-center gap-3 order-3">
      
        <div class="search-pill">
          <input type="text" placeholder="طحين ، سكر">
          <button type="button">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2.5"/><path d="M21 21l-4.35-4.35" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
          </button>
        </div>

        <a href="compare.html" class="btn-compare">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
          مقارنة الاسعار
        </a>
        <a href="profile.html" class="icon-circle" title="الملف الشخصي">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
        </a>
      </div>

      <!-- MOBILE CONTROLS -->
      <div class="d-flex d-lg-none align-items-center gap-2 order-3">
        <a href="profile.html" class="icon-circle">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
        </a>
        <button class="navbar-toggler-custom">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
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
    <a class="nav-link active" href="index.html">الرئيسية</a>
    <a class="nav-link" href="map.html">الخريطة</a>
    <a class="nav-link" href="shops.html">المحلات</a>
    <a class="nav-link" href="prices.html">الاسعار</a>
  </div>
</nav>

<!-- HERO SECTION WITH SLIDING BACKGROUND IMAGES -->
<section class="hero-section">

  <!-- Carousel Background + Indicators -->
  <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">

    <!-- النقاط داخل الكاروسيل حتى تتغير تلقائياً -->
    <div class="carousel-indicators hero-dots-wrap">
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="hero-dot active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="hero-dot" aria-label="Slide 2"></button>
    </div>

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="imges/map.png" class="hero-img" alt="خريطة 1">
      </div>
      <div class="carousel-item">
        <img src="imges/22.png" class="hero-img" alt="صورة 2">
      </div>
    </div>
  </div>

  <!-- طبقة اللون الشفافة فوق الصورة -->
  <div class="hero-overlay"></div>

  <!-- المحتوى -->
  <div class="container hero-content text-center">
    <div class="from-line">من <span class="brand-highlight">وفر<span class="white-part">كاش</span></span></div>
    <h1 class="hero-title">اعرف اسعار السوق<br>حسب منطقتك</h1>

    <button class="btn-locate">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
      حدد موقعك
    </button>
  </div>

</section>

<!-- MAIN CONTENT CONTAINER (LIGHT GREEN BACKDROP) -->
<main class="page-body">
  <div class="container">

    <!-- MOST DEMANDED SECTION -->
    <section class="mb-5">
      <div class="section-title">
        <span class="bar"></span>
        <h2>الأكثر طلباً</h2>
      </div>

      <!-- TABLE VIEW (DESKTOP & TABLET) -->
      <div class="wafar-table table-responsive d-none d-sm-block">
        <table class="table table-borderless align-middle">
          <thead>
            <tr>
              <th>السلعة</th>
              <th>السعر</th>
              <th>الكمية</th>
              <th>المحل</th>
              <th>اخر تحديث</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><div class="cell-card item-name">طحن</div></td>
              <td><div class="cell-card price-tag">40 شيكل</div></td>
              <td><div class="cell-card">25كيلو</div></td>
              <td><div class="cell-card">مول لاكاسا</div></td>
              <td><div class="cell-card updated">منذ ساعة</div></td>
            </tr>
            <tr>
              <td><div class="cell-card item-name">بندورة</div></td>
              <td><div class="cell-card price-tag">8 شيكل</div></td>
              <td><div class="cell-card">1كيلو</div></td>
              <td><div class="cell-card">محلات عمو عماد</div></td>
              <td><div class="cell-card updated">منذ ساعة ونصف</div></td>
            </tr>
            <tr>
              <td><div class="cell-card item-name">سكر</div></td>
              <td><div class="cell-card price-tag">4 شيكل</div></td>
              <td><div class="cell-card">1كيلو</div></td>
              <td><div class="cell-card">مول التاج</div></td>
              <td><div class="cell-card updated">منذ ساعة</div></td>
            </tr>
            <tr>
              <td><div class="cell-card item-name">صابون الغسيل</div></td>
              <td><div class="cell-card price-tag">10 شيكل</div></td>
              <td><div class="cell-card">750 غرام</div></td>
              <td><div class="cell-card">مول التاج</div></td>
              <td><div class="cell-card updated">منذ ساعة</div></td>
            </tr>
            <tr>
              <td><div class="cell-card item-name">دجاج</div></td>
              <td><div class="cell-card price-tag">23 شيكل</div></td>
              <td><div class="cell-card">1كيلو</div></td>
              <td><div class="cell-card">مذبح التاج</div></td>
              <td><div class="cell-card updated">منذ ساعتين</div></td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- MOBILE LIST VIEW -->
      <div class="simple-list d-sm-none mb-3">
        <div class="row g-0"><div class="col-6 list-head">السلعة</div><div class="col-6 list-head">السعر</div></div>
        <div class="row g-0"><div class="col-6 list-row">طحن</div><div class="col-6 list-row">40 شيكل</div></div>
        <div class="row g-0"><div class="col-6 list-row">بندورة</div><div class="col-6 list-row">8 شيكل</div></div>
        <div class="row g-0"><div class="col-6 list-row">سكر</div><div class="col-6 list-row">4 شيكل</div></div>
        <div class="row g-0"><div class="col-6 list-row">صابون الغسيل</div><div class="col-6 list-row">10 شيكل</div></div>
        <div class="row g-0"><div class="col-6 list-row">دجاج</div><div class="col-6 list-row">23 شيكل</div></div>
      </div>

      <button class="btn-more">المزيد....</button>
    </section>

      <!-- BROWSE CATEGORIES SECTION -->
      <section class="pb-5">
        <div class="section-title">
          <span class="bar"></span>
          <h2>تصفح الأقسام</h2>
        </div>

        <div class="row g-4 pt-3">
          <!-- Item 1: مواد غذائية (Wheat) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2c-1.5 2.5-3 5-3 8 0 3.5 2 6 3 9 1-3 3-5.5 3-9 0-3-1.5-5.5-3-8zm0 13c-1.1 0-2-1.3-2-3s.9-3 2-3 2 1.3 2 3-.9 3-2 3z"/>
                  <path d="M6 6c-1 2-2 4-2 6 0 3 1.5 5 3 7 1-2 2-4 2-6 0-2-1-4-3-7z" opacity="0.85"/>
                  <path d="M18 6c1 2 2 4 2 6 0 3-1.5 5-3 7-1-2-2-4-2-6 0-2 1-4 3-7z" opacity="0.85"/>
                </svg>
              </div>
              <div class="cat-name">مواد غذائية</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 2: خضراوات (Tomato) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2c.6 0 1.2.3 1.5.8.5-.2 1.1 0 1.4.4.4.4.5 1 .2 1.5C17.5 5.8 19 8 19 11c0 4.2-3.1 7.5-7 7.5S5 15.2 5 11c0-3 1.5-5.2 3.9-6.3-.3-.5-.2-1.1.2-1.5.3-.4.9-.6 1.4-.4.3-.5.9-.8 1.5-.8z"/>
                </svg>
              </div>
              <div class="cat-name">خضراوات</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 3: لحوم (Meat/Steak) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.5 4.5c-2-2-5.3-2-7.3 0L4.5 10.2c-2 2-2 5.3 0 7.3l1.8 1.8c2 2 5.3 2 7.3 0l5.7-5.7c2-2 2-5.3 0-7.3l-1.8-1.8zM11 15c-1.4 0-2.5-1.1-2.5-2.5S9.6 10 11 10s2.5 1.1 2.5 2.5S12.4 15 11 15z"/>
                </svg>
              </div>
              <div class="cat-name">لحوم</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 4: وقود (Fuel Pump) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8.5c0-.8-.5-1.5-1.2-1.8L15.7 4.6l-1.4 1.4 1.5 1.5C15.3 7.8 15 8.4 15 9c0 1.7 1.3 3 3 3h.5v4.5c0 1.1-.9 2-2 2s-2-.9-2-2V11c0-1.7-1.3-3-3-3H11V4c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v16c0 .6.4 1 1 1h5c.6 0 1-.4 1-1v-7h1c.6 0 1 .4 1 1v4.5c0 2.2 1.8 4 4 4s4-1.8 4-4V11c.6 0 1.1-.2 1.5-.6.6-.7.5-1.9-.5-1.9zM9 11H6V6h3v5z"/>
                </svg>
              </div>
              <div class="cat-name">وقود</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Row 2 Repeat -->
          <!-- Item 5: مواد غذائية (Wheat) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2c-1.5 2.5-3 5-3 8 0 3.5 2 6 3 9 1-3 3-5.5 3-9 0-3-1.5-5.5-3-8zm0 13c-1.1 0-2-1.3-2-3s.9-3 2-3 2 1.3 2 3-.9 3-2 3z"/>
                  <path d="M6 6c-1 2-2 4-2 6 0 3 1.5 5 3 7 1-2 2-4 2-6 0-2-1-4-3-7z" opacity="0.85"/>
                  <path d="M18 6c1 2 2 4 2 6 0 3-1.5 5-3 7-1-2-2-4-2-6 0-2 1-4 3-7z" opacity="0.85"/>
                </svg>
              </div>
              <div class="cat-name">مواد غذائية</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 6: خضراوات (Tomato) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 2c.6 0 1.2.3 1.5.8.5-.2 1.1 0 1.4.4.4.4.5 1 .2 1.5C17.5 5.8 19 8 19 11c0 4.2-3.1 7.5-7 7.5S5 15.2 5 11c0-3 1.5-5.2 3.9-6.3-.3-.5-.2-1.1.2-1.5.3-.4.9-.6 1.4-.4.3-.5.9-.8 1.5-.8z"/>
                </svg>
              </div>
              <div class="cat-name">خضراوات</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 7: لحوم (Meat/Steak) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M17.5 4.5c-2-2-5.3-2-7.3 0L4.5 10.2c-2 2-2 5.3 0 7.3l1.8 1.8c2 2 5.3 2 7.3 0l5.7-5.7c2-2 2-5.3 0-7.3l-1.8-1.8zM11 15c-1.4 0-2.5-1.1-2.5-2.5S9.6 10 11 10s2.5 1.1 2.5 2.5S12.4 15 11 15z"/>
                </svg>
              </div>
              <div class="cat-name">لحوم</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>

          <!-- Item 8: وقود (Fuel Pump) -->
          <div class="col-6 col-md-3">
            <div class="category-card">
              <div class="category-icon-wrapper">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 8.5c0-.8-.5-1.5-1.2-1.8L15.7 4.6l-1.4 1.4 1.5 1.5C15.3 7.8 15 8.4 15 9c0 1.7 1.3 3 3 3h.5v4.5c0 1.1-.9 2-2 2s-2-.9-2-2V11c0-1.7-1.3-3-3-3H11V4c0-.6-.4-1-1-1H5c-.6 0-1 .4-1 1v16c0 .6.4 1 1 1h5c.6 0 1-.4 1-1v-7h1c.6 0 1 .4 1 1v4.5c0 2.2 1.8 4 4 4s4-1.8 4-4V11c.6 0 1.1-.2 1.5-.6.6-.7.5-1.9-.5-1.9zM9 11H6V6h3v5z"/>
                </svg>
              </div>
              <div class="cat-name">وقود</div>
              <div class="cat-count">235 صنف</div>
            </div>
          </div>
        </div>
      </section>

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
</html>

<?php /**PATH C:\xampp\htdocs\gaza-stock-exchange\resources\views/index.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | صفحة المحل</title>
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
          <a href="index.html" class="d-flex align-items-center gap-2">
            <img src="{{asset('assets/imges/logo.png')}}" alt="وفر كاش" class="logo-icon">
            <span class="logo-text d-none d-sm-block">
              <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
              <span class="en">CASH WAFAR</span>
            </span>
          </a>
        </div>

         <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
        <li class="nav-item"><a class="nav-link active" href="{{route('index')}}">الرئيسية</a></li>
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
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
              stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            مقارنة الاسعار
          </a>
          <a href="{{route('profile')}}" class="icon-circle" title="الملف الشخصي">
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
    <a class="nav-link active" href="shops.html">المحلات</a>
    <a class="nav-link" href="prices.html">الاسعار</a>
    </div>
  </nav>

  <!-- STORE DETAILS HERO BANNER -->
  <section class="shops-header-banner py-5">
    <img src="{{asset('assets/imges/baner.png')}}" alt="خريطة" class="hero-bg-img">
    <div class="container position-relative">
      <div class="d-flex justify-content-between align-items-start">
        <div class="text-end">
          <h1 class="page-header-title mb-0"><span class="brand-green">صفحة</span> <span
              class="brand-white">المحل</span></h1>
          <p class="page-header-sub">تفاصيل أكثر</p>
        </div>
      </div>

      <!-- CENTER OVERLAY STORE CARD -->
      <div class="store-hero-overlay-card mx-auto text-center bg-white p-4 rounded-4 shadow-lg">
        <div class="store-badge-icon mb-2">
          <img src="{{asset('assets/imges/logomall.jpg')}}" alt="سوبر ماركت فتفيت" class="rounded-circle shadow-sm"
            style="width: 65px; height: 65px; object-fit: cover;">
        </div>
        <h3 class="fw-bolder mb-3">سوبر ماركت فتفيت</h3>
        <button
          class="btn btn-success bg-success bg-gradient border-0 px-4 py-2 rounded-pill fw-bold d-inline-flex align-items-center gap-2">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path
              d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
          </svg>
          إضافة للمفضلة
        </button>
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
            <div
              class="bell-icon-top mb-3 d-inline-flex align-items-center justify-content-center p-2 rounded-circle bg-light">
              🔔
            </div>

            <div class="section-title mb-3">
              <span class="bar"></span>
              <h4 class="fw-bold m-0">مول ابو دلال</h4>
            </div>

            <div class="shop-info-list d-flex flex-column gap-3 mb-4">
              <div class="d-flex align-items-center gap-2">
                <span class="fs-5">🏪</span>
                <span>النصيرات</span>
              </div>

              <div class="d-flex align-items-start gap-2">
                <span class="fs-5">🕒</span>
                <div>
                  <div class="fw-bold">ساعات العمل</div>
                  <div class="small text-muted">من 7:00 صباحاً إلى 11:00 مساءً</div>
                </div>
              </div>
            </div>

            <a href="{{ route('map') }}" class="btn-locate w-100 justify-content-center py-2.5 mb-4">
              📍 عرض على الخريطة
            </a>

            <div class="contact-rows small d-flex flex-column gap-2 border-top pt-3">
              <div class="d-flex align-items-center justify-content-between">
                <span>0590000000</span>
                <span>📱</span>
              </div>
              <div class="d-flex align-items-center justify-content-between text-success fw-bold">
                <span>+972590000000</span>
                <span>💬</span>
              </div>
            </div>
          </div>
        </div>

        <!-- PRODUCTS GRID (LEFT IN RTL) -->
        <div class="col-12 col-lg-8">
          <div class="small text-muted mb-2 text-end">اخر تحديث: منذ ساعة</div>

          <!-- SEARCH & FILTER BAR -->
          <div class="filter-card-container mb-4">
            <div class="row g-2 align-items-center">
              <div class="col-12 col-md-6">
                <select class="form-select border-1 rounded-3">
                  <option>تصنيف السلع</option>
                  <option>مواد تموينية</option>
                  <option>خضراوات</option>
                </select>
              </div>
              <div class="col-12 col-md-6">
                <div class="input-group">
                  <input type="text" class="form-control border-1 rounded-3" placeholder="البحث عن سلعة">
                  <span class="input-group-text bg-white border-1">🔍</span>
                </div>
              </div>
            </div>
          </div>

          <!-- PRODUCTS GRID (6 CARDS) -->
          <div class="row g-4 pb-4">
            <!-- Item 1: قهوة -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="product-card">
                <div class="discount-badge">-29%</div>
                <div class="category-badge">☕</div>
                <div class="product-img-wrap">
                  <img src="{{asset('assets/imges/coffee.png')}}" alt="قهوة" class="product-img">
                </div>
                <div class="product-info">
                  <div class="product-name">قهوة</div>
                  <div class="product-sub">علبة</div>
                  <div class="product-price">35 شيكل</div>
                </div>
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>

            <!-- Item 2: دجاج -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="product-card">
                <div class="discount-badge">-21%</div>
                <div class="category-badge">🍗</div>
                <div class="product-img-wrap">
                  <img src="{{asset('assets/imges/chkn.png')}}" alt="دجاج" class="product-img">
                </div>
                <div class="product-info">
                  <div class="product-name">دجاج</div>
                  <div class="product-sub">1 كيلو</div>
                  <div class="product-price">20 شيكل</div>
                </div>
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>

            <!-- Item 3: مانجا -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="product-card">
                <div class="discount-badge">-21%</div>
                <div class="category-badge">🥭</div>
                <div class="product-img-wrap">
                  <img src="{{asset('assets/imges/mango.png')}}" alt="مانجا" class="product-img">
                </div>
                <div class="product-info">
                  <div class="product-name">مانجا</div>
                  <div class="product-sub">1 كيلو</div>
                  <div class="product-price">10 شيكل</div>
                </div>
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>

            <!-- Item 4: شامبو -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="product-card">
                <div class="discount-badge">-21%</div>
                <div class="category-badge">🧴</div>
                <div class="product-img-wrap">
                  <img
                    src="{{asset('assets/imges/shampo.png')}}"
                    alt="شامبو" class="product-img">
                </div>
                <div class="product-info">
                  <div class="product-name">شامبو</div>
                  <div class="product-sub">350 ملم</div>
                  <div class="product-price">11 شيكل</div>
                </div>
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>

            <!-- Item 5: طحين -->
            <div class="col-12 col-sm-6 col-md-4">
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
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>

            <!-- Item 6: باذنجان -->
            <div class="col-12 col-sm-6 col-md-4">
              <div class="product-card">
                <div class="discount-badge">-25%</div>
                <div class="category-badge">🍆</div>
                <div class="product-img-wrap">
                  <img src="{{asset('assets/imges/bad.png')}}" alt="باذنجان" class="product-img">
                </div>
                <div class="product-info">
                  <div class="product-name">باذنجان</div>
                  <div class="product-sub">4 علب</div>
                  <div class="product-price">10 شيكل</div>
                </div>
                <button class="btn-compare-store">
                  <a href="{{ route('compare') }}" class="btn-compare-store">قارن مع 10 محل</a>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

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
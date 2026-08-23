<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | مقارنة الاسعار</title>
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
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
          <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">الرئيسية</a></li>
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

          <a href="{{ route('compare') }}" class="btn-compare">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
              <line x1="7" y1="7" x2="7.01" y2="7"></line>
            </svg>
            مقارنة الاسعار
          </a>
          <a href="{{ route('profile') }}" class="icon-circle" title="الملف الشخصي">
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

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- PAGE TITLE -->
      <div class="compare-header text-center mb-4">
        <h1 class="fw-bolder">مقارنة <span class="text-green">الاسعار</span></h1>
        <p class="text-muted fs-6">اختر المنطقة والسلعة</p>
      </div>

      <!-- FILTER BAR (CARD) -->
      <div class="filter-card-container mb-4">
        <div class="row g-3 align-items-end">
          <div class="col-12 col-md-2">
            <button class="btn-locate w-100 justify-content-center py-2">تحديث المقارنة</button>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label fw-bold small">السلعة</label>
            <select class="form-select border-1 rounded-3">
              <option>طحين</option>
              <option>سكر</option>
              <option>أرز</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label fw-bold small">تصنيف السلعة</label>
            <select class="form-select border-1 rounded-3">
              <option>مواد تموينية</option>
              <option>خضراوات</option>
              <option>لحوم</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label fw-bold small">الحي/ المخيم</label>
            <select class="form-select border-1 rounded-3">
              <option>حي التفاح</option>
              <option>مخيم الشاطئ</option>
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label fw-bold small">المحافظة</label>
            <select class="form-select border-1 rounded-3">
              <option>غزة</option>
              <option>خانيونس</option>
              <option>رفح</option>
            </select>
          </div>
        </div>
      </div>

      <!-- COMPARE DATA & MAP SPLIT -->
      <div class="row g-3 mb-5">
        <!-- TABLE (LEFT IN RTL) -->
        <div class="col-12 col-lg-7">
          <div class="bg-white rounded-4 shadow-sm overflow-hidden p-2">
            <div class="table-responsive">
              <table class="table align-middle text-center table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>السلعة</th>
                    <th>المنطقة/المخيم</th>
                    <th>السعر</th>
                    <th>ارخص محل</th>
                    <th>العنوان</th>
                    <th>الخريطة</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="fw-bold">طحين 25 كيلو</td>
                    <td>حي التفاح</td>
                    <td class="text-green fw-bold">35 شيكل</td>
                    <td>سوبر ماركت فتفيت</td>
                    <td>شارع المحطة</td>
                    <td><span class="map-pin">📍</span></td>
                  </tr>
                  <tr>
                    <td class="fw-bold">طحين 25 كيلو</td>
                    <td>حي التفاح</td>
                    <td class="text-green fw-bold">35 شيكل</td>
                    <td>سوبر ماركت النادي</td>
                    <td>شارع يافا</td>
                    <td><span class="map-pin">📍</span></td>
                  </tr>
                  <tr>
                    <td class="fw-bold">طحين 25 كيلو</td>
                    <td>حي التفاح</td>
                    <td class="text-green fw-bold">36 شيكل</td>
                    <td>مول التاج</td>
                    <td>شارع غزة القديم</td>
                    <td><span class="map-pin">📍</span></td>
                  </tr>
                  <tr>
                    <td class="fw-bold">طحين 25 كيلو</td>
                    <td>حي التفاح</td>
                    <td class="text-green fw-bold">36 شيكل</td>
                    <td>سوبر ماركت البابا</td>
                    <td>مفرق المحطة</td>
                    <td><span class="map-pin">📍</span></td>
                  </tr>
                  <tr>
                    <td class="fw-bold">طحين 25 كيلو</td>
                    <td>حي التفاح</td>
                    <td class="text-green fw-bold">38 شيكل</td>
                    <td>ابو دلال مول</td>
                    <td>دوار الزرقا</td>
                    <td><span class="map-pin">📍</span></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- MAP (RIGHT IN RTL) -->
        <div class="col-12 col-lg-5">
          <div class="compare-map-wrap rounded-4 overflow-hidden shadow-sm h-100 min-vh-300">
            <img src="{{asset('assets/imges/map.png')}}" alt="خريطة غزة" class="w-100 h-100 object-fit-cover">
          </div>
        </div>
      </div>

      <!-- SMART STATS SECTION -->
      <section class="pb-5">
        <div class="section-title">
          <span class="bar"></span>
          <h2>إحصائيات اليوم الذكية</h2>
        </div>

        <div class="row g-4">
          <!-- Card 1 -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-3 shadow-sm h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">🏪 أرخص محل في منطقتك</h5>
                <span class="badge bg-light text-dark">مخيم الشاطئ - غزة</span>
              </div>
              <div class="store-box p-2 bg-light rounded-3 mb-3 d-flex align-items-center justify-content-between">
                <div>
                  <div class="fw-bold">سوبر ماركت فتفيت</div>
                  <div class="small text-muted">📍 150 م منك</div>
                </div>
                <span class="badge bg-success">وترشح 12 % مقارنة بالأعلى</span>
              </div>
              <div class="price-list small">
                <div class="d-flex justify-content-between py-1 border-bottom"><span>طحين 25 كغ</span><span class="fw-bold text-green">35 ₪</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span>سكر 1 كغ</span><span class="fw-bold text-green">3 ₪</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span>زيت 1 لتر</span><span class="fw-bold text-green">12 ₪</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span>دجاج 1 كغ</span><span class="fw-bold text-green">23 ₪</span></div>
              </div>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-3 shadow-sm h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">📈 الأكثر بحثاً اليوم</h5>
                <span class="badge bg-light text-dark">عدد مرات البحث في كل المناطق</span>
              </div>
              <div class="p-2 bg-light rounded-3 mb-3 text-center small text-muted">
                <strong>1247</strong> بحث اليوم <span class="text-success ms-2">↑180% عن أمس</span>
              </div>
              <div class="search-stats-bars">
                <div class="d-flex align-items-center gap-2 mb-2 small">
                  <span style="width: 50px;">طحين</span>
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 85%;"></div>
                  </div>
                  <span>280</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2 small">
                  <span style="width: 50px;">سكر</span>
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 70%;"></div>
                  </div>
                  <span>221</span>
                </div>
                <div class="d-flex align-items-center gap-2 mb-2 small">
                  <span style="width: 50px;">زيت</span>
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 55%;"></div>
                  </div>
                  <span>190</span>
                </div>
                <div class="d-flex align-items-center gap-2 small">
                  <span style="width: 50px;">دجاج</span>
                  <div class="progress flex-grow-1" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 45%;"></div>
                  </div>
                  <span>160</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="col-12 col-md-4">
            <div class="bg-white rounded-4 p-3 shadow-sm h-100">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">📉 أكثر الأسعار انخفاضاً</h5>
                <span class="badge bg-light text-dark">مقارنة بأمس في كل المناطق</span>
              </div>
              <div class="p-2 bg-light rounded-3 mb-3 d-flex justify-content-between align-items-center small">
                <span><strong>7</strong> سلع انخفضت اليوم</span>
                <span class="badge bg-danger">أكبر انخفاض %20</span>
              </div>
              <div class="lowered-list small">
                <div class="d-flex justify-content-between py-1 border-bottom"><span>طحين 25 كغ</span><span class="badge bg-danger-subtle text-danger">↓ 7 ₪</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span>بندورة 1 كغ</span><span class="badge bg-danger-subtle text-danger">↓ 3 ₪</span></div>
                <div class="d-flex justify-content-between py-1 border-bottom"><span>خيار 1 كغ</span><span class="badge bg-danger-subtle text-danger">↓ 2 ₪</span></div>
                <div class="d-flex justify-content-between py-1"><span>دجاج 1 كغ</span><span class="badge bg-danger-subtle text-danger">↓ 3 ₪</span></div>
              </div>
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
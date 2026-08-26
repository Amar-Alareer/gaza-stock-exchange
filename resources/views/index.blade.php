<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | الرئيسية</title>
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

        <!-- LOGO (RIGHT IN RTL) -->
        <div class="brand-logo order-1">
          <a href="{{ route('index') }}" class="d-flex align-items-center gap-2">
            <img src="{{ asset('assets/imges/logo.png') }}" alt="وفر كاش" class="logo-icon">
            <span class="logo-text d-none d-sm-block">
              <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
              <span class="en">CASH WAFAR</span>
            </span>
          </a>
        </div>

        <!-- NAV LINKS (MIDDLE) -->
        <ul class="nav order-2 mx-auto d-none d-lg-flex align-items-center gap-4">
          <li class="nav-item"><a class="nav-link active" href="{{ route('index') }}">الرئيسية</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('map') }}">الخريطة</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <!-- CONTROLS (LEFT IN RTL) -->
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

        <!-- MOBILE CONTROLS -->
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
      <a class="nav-link active" href="{{ route('index') }}">
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

  <!-- HERO SECTION WITH SLIDING BACKGROUND IMAGES -->
  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-indicators hero-dots-wrap">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="hero-dot active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="hero-dot" aria-label="Slide 2"></button>
      </div>

      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="{{ asset('assets/imges/map.png') }}" class="hero-img" alt="خريطة 1">
        </div>
        <div class="carousel-item">
          <img src="{{ asset('assets/imges/22.png') }}" class="hero-img" alt="صورة 2">
        </div>
      </div>
    </div>

    <div class="hero-overlay"></div>

    <div class="container hero-content text-center">
      <div class="from-line">من <span class="brand-highlight">وفر<span class="white-part">كاش</span></span></div>
      <h1 class="hero-title">اعرف اسعار السوق<br>حسب منطقتك</h1>

      <a href="{{ route('map') }}" class="btn-locate text-decoration-none d-inline-flex align-items-center gap-2">
        <i class="bi bi-geo-alt-fill text-danger fs-5"></i>
        حدد موقعك على الخريطة
      </a>
    </div>
  </section>

  <!-- MAIN CONTENT CONTAINER -->
  <main class="page-body">
    <div class="container">

      <!-- MOST DEMANDED SECTION -->
      <section class="mb-5">
        <div class="section-title">
          <span class="bar"></span>
          <h2><i class="bi bi-fire text-danger me-1"></i> الأكثر طلباً</h2>
        </div>

        <!-- TABLE VIEW (DESKTOP & TABLET) -->
        <div class="wafar-table table-responsive d-none d-sm-block">
          <table class="table table-borderless align-middle">
            <thead>
              <tr>
                <th>السلعة</th>
                <th>السعر</th>
                <th>التصنيف</th>
                <th>المحل</th>
                <th>اخر تحديث</th>
              </tr>
            </thead>
            <tbody id="products-table-body">
              @forelse($products as $product)
                @php
                  $bestPrice = $product->best_price;
                  $bestStore = $product->best_store;
                  $categoryName = $product->category_name;
                  $timeAgo = $product->formatted_updated_at;
                @endphp
                <tr onclick="window.location='{{ route('products.show', $product->id) }}'" style="cursor: pointer;">
                  <td><div class="cell-card item-name"><i class="bi bi-box-seam me-1 text-success"></i> {{ $product->name }}</div></td>
                  <td><div class="cell-card price-tag">{{ $bestPrice ? $bestPrice . ' شيكل' : 'غير محدد' }}</div></td>
                  <td><div class="cell-card"><span class="badge bg-light text-dark"><i class="bi bi-tag-fill text-success me-1"></i> {{ $categoryName }}</span></div></td>
                  <td><div class="cell-card"><i class="bi bi-shop me-1 text-muted"></i> {{ $bestStore }}</div></td>
                  <td><div class="cell-card updated"><i class="bi bi-clock me-1"></i> {{ $timeAgo }}</div></td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i> لا توجد منتجات مسجلة حالياً في قاعدة البيانات</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <!-- MOBILE LIST VIEW -->
        <div class="mobile-products-list d-sm-none mb-3" id="products-mobile-list">
          <div class="mobile-list-header">
            <div class="header-col item-col">السلعة</div>
            <div class="header-col price-col">السعر</div>
          </div>
          @foreach($products as $product)
            @php
              $bestPrice = $product->best_price;
            @endphp
            <div class="mobile-item-row" onclick="window.location='{{ route('products.show', $product->id) }}'">
              <div class="item-name-cell">
                <i class="bi bi-box-seam text-success"></i>
                <span>{{ $product->name }}</span>
              </div>
              <div class="item-price-cell">
                <span class="price-val">{{ $bestPrice ? $bestPrice . ' شيكل' : 'غير محدد' }}</span>
              </div>
            </div>
          @endforeach
        </div>

        <button id="load-more-btn" class="btn-more d-inline-block text-center text-decoration-none border-0 bg-transparent cursor-pointer">
          <i class="bi bi-plus-circle me-1"></i> المزيد....
        </button>
      </section>

      <!-- BROWSE CATEGORIES SECTION -->
      <section class="pb-5">
        <div class="section-title">
          <span class="bar"></span>
          <h2>تصفح الأقسام</h2>
        </div>

        @php
          $getCatIconSvg = function($name) {
            $name = trim($name);
            // مواد غذائية / حبوب
            if (str_contains($name, 'غذائي') || str_contains($name, 'حبوب') || str_contains($name, 'طحين') || str_contains($name, 'أرز') || str_contains($name, 'تموين')) {
              return '<svg viewBox="0 0 24 24"><path d="M6.05 8.05c-2.73 2.73-2.73 7.15 0 9.88s7.15 2.73 9.88 0c.2-.2.38-.41.56-.63L14.7 15.5c-.88.88-2.3 2.07-4.46 1.7-1.46-.25-2.65-1.44-2.9-2.9-.37-2.16.82-3.58 1.7-4.46l-1.8-1.79c-.42.36-.82.7-1.19 1zm12.39-4.54c-1.4.15-2.71.74-3.74 1.77l-1.8 1.79c1.03 1.03 2.7 1.03 3.74 0 1.03-1.03 1.03-2.7 0-3.74-.07-.07-.13-.13-.2-.19v.37zm-6.19 6.2c-.52.51-.95 1.09-1.29 1.71.62-.34 1.2-.77 1.71-1.29l1.79-1.8c-.52-.51-.95-1.09-1.29-1.71-.62.34-1.2.77-1.71 1.29l-1.79 1.8c1.03 1.03 2.7 1.03 3.74 0l-1.16-1zm2.39-2.39c.52-.51.95-1.09 1.29-1.71-.62.34-1.2.77-1.71 1.29l-1.79 1.8c.52.51.95 1.09 1.29 1.71.62-.34 1.2-.77 1.71-1.29l1.79-1.8c-.87-.88-2.3-2.07-4.46-1.7-1.46.25-2.65 1.44-2.9 2.9-.37 2.16.82 3.58 1.7 4.46l1.79-1.8c1.03-1.03 2.7-1.03 3.74 0 1.03 1.03 1.03 2.7 0 3.74-.2.2-.41.38-.63.56l1.8 1.79c2.73-2.73 2.73-7.15 0-9.88-1.03-1.03-2.34-1.62-3.74-1.77v.01z"/></svg>';
            }
            // خضراوات
            if (str_contains($name, 'خضار') || str_contains($name, 'خضراوات') || str_contains($name, 'طماطم')) {
              return '<svg viewBox="0 0 24 24"><path d="M12 5.5c-4.42 0-8 3.36-8 7.5 0 4.14 3.58 7.5 8 7.5s8-3.36 8-7.5c0-4.14-3.58-7.5-8-7.5zm.5-2.75c-.28 0-.5.22-.5.5v1.28c-1.42-.16-2.58-.69-3.21-1.47-.18-.23-.52-.27-.75-.08-.23.18-.27.52-.08.75 1.04 1.28 2.89 1.87 4.54 1.99v.08c1.65-.12 3.5-.71 4.54-1.99.19-.23.15-.57-.08-.75-.23-.19-.57-.15-.75.08-.63.78-1.79 1.31-3.21 1.47V3.25c0-.28-.22-.5-.5-.5z"/></svg>';
            }
            // لحوم ودواجن
            if (str_contains($name, 'لحم') || str_contains($name, 'لحوم') || str_contains($name, 'دواجن') || str_contains($name, 'دجاج')) {
              return '<svg viewBox="0 0 24 24"><path d="M17.5 4C14.5 4 13 5.5 11 6.5 9 5.5 7.5 4 4.5 4 2 4 1 6.5 1 9.5c0 4.5 5 10.5 11 10.5s11-6 11-10.5c0-3-1-5.5-5.5-5.5zm-5.5 13c-4.2 0-7.5-4.2-7.5-7.5 0-1.8.8-3 2.5-3 1.5 0 2.5 1 4 2 1-.7 2-1.5 3.5-1.5 1.7 0 2.5 1.2 2.5 3 0 3.3-3.3 7-5 7zm-.5-5.5c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5 1.5.7 1.5 1.5-.7 1.5-1.5 1.5z"/></svg>';
            }
            // وقود ومحروقات
            if (str_contains($name, 'وقود') || str_contains($name, 'غاز') || str_contains($name, 'بنزين') || str_contains($name, 'سولار') || str_contains($name, 'محروقات')) {
              return '<svg viewBox="0 0 24 24"><path d="M19.77 7.23l.01-.01-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33 0 1.38 1.12 2.5 2.5 2.5.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v16h10v-7.5h1.5v5.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V9c0-.69-.28-1.32-.73-1.77zM12 10H6V6h6v4zm6 0c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/></svg>';
            }
            // فواكه
            if (str_contains($name, 'فواكه') || str_contains($name, 'فاكهة') || str_contains($name, 'تفاح') || str_contains($name, 'موز')) {
              return '<svg viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.81-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M15.97 4.15c.66-.8 1.1-1.92.98-3.04-1 .04-2.18.66-2.88 1.48-.6.7-.1.14-1.84 1.83-1.9 1.12-.04 2.24.62 2.82 1.46z"/></svg>';
            }
            // زيوت ودهون
            if (str_contains($name, 'زيت') || str_contains($name, 'زيوت') || str_contains($name, 'دهون')) {
              return '<svg viewBox="0 0 24 24"><path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"/></svg>';
            }
            // ألبان وأجبان
            if (str_contains($name, 'ألبان') || str_contains($name, 'لبن') || str_contains($name, 'حليب') || str_contains($name, 'جبن') || str_contains($name, 'أجبان')) {
              return '<svg viewBox="0 0 24 24"><path d="M4 19h16v2H4zm14-8v6H6v-6h12m2-2H4v10h16V9zm-5-7H9v3h6V2z"/></svg>';
            }
            // أسماك
            if (str_contains($name, 'سمك') || str_contains($name, 'أسماك') || str_contains($name, 'بحرية')) {
              return '<svg viewBox="0 0 24 24"><path d="M22 12c-4 4-8 5-13 3l-5 4v-4l-2 1v-8l2 1v-4l5 4c5-2 9-1 13 3zm-6-1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>';
            }
            // مخبوزات
            if (str_contains($name, 'مخبوز') || str_contains($name, 'خبز') || str_contains($name, 'كعك') || str_contains($name, 'معجنات')) {
              return '<svg viewBox="0 0 24 24"><path d="M12 4C7.58 4 4 7.58 4 12c0 3.31 2.69 6 6 6h4c3.31 0 6-2.69 6-6 0-4.42-3.58-8-8-8zm-3 7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm3 3c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm3-3c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z"/></svg>';
            }
            // الافتراضي (سلة تسوق)
            return '<svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/></svg>';
          };
        @endphp

        <div class="home-categories-grid">
          @forelse($categories as $category)
            @php
              $minPrice = $category->items ? $category->items->where('min_price', '>', 0)->min('min_price') : null;
              $sampleItems = $category->items ? $category->items->pluck('name')->filter()->take(2)->implode('، ') : '';
            @endphp
            <a href="{{ route('prices') }}?category={{ urlencode($category->name) }}" class="home-cat-card">
              <!-- Top Protruding Notch with Icon -->
              <div class="home-cat-tab">
                <div class="home-cat-icon">
                  @if($category->image_url)
                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}" onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                    <span style="display:none;">{!! $getCatIconSvg($category->name) !!}</span>
                  @else
                    {!! $getCatIconSvg($category->name) !!}
                  @endif
                </div>
              </div>

              <!-- Main Body Info -->
              <div class="home-cat-body">
                <h3 class="home-cat-title">{{ $category->name }}</h3>
                <span class="home-cat-count">{{ $category->items_count }} صنف</span>
              </div>

              <!-- Additional Details Footer -->
              <div class="home-cat-details">
                @if($minPrice)
                  <span class="home-cat-price-tag" title="أقل سعر مسجل في هذا القسم">
                    تبدأ من {{ number_format($minPrice, 1) }} ₪
                  </span>
                @elseif(!empty($sampleItems))
                  <span class="small text-muted text-truncate" style="max-width: 110px; font-size: 0.75rem;" title="{{ $sampleItems }}">
                    {{ $sampleItems }}
                  </span>
                @else
                  <span class="small text-muted" style="font-size: 0.75rem;">
                    مقارنة الأسعار
                  </span>
                @endif
                <span class="home-cat-arrow" title="عرض السلع">
                  <i class="bi bi-chevron-left"></i>
                </span>
              </div>
            </a>
          @empty
            <div style="grid-column: 1/-1; text-align: center; color: var(--text-muted); padding: 2rem;">لا توجد أقسام مسجلة حالياً</div>
          @endforelse
        </div>
      </section>

    </div>
  </main>

  <!-- FOOTER -->
  @include('partials.footer')

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>
  <script>
    let offset = 5;
    const loadMoreBtn = document.getElementById('load-more-btn');
    const tableBody = document.getElementById('products-table-body');
    const mobileList = document.getElementById('products-mobile-list');

    if (loadMoreBtn) {
      loadMoreBtn.addEventListener('click', function() {
        loadMoreBtn.innerText = 'جاري التحميل...';
        loadMoreBtn.disabled = true;

        fetch(`{{ route('products.loadMore') }}?offset=${offset}`)
          .then(response => response.json())
          .then(data => {
            if (data.length > 0) {
              data.forEach(product => {
                const tr = document.createElement('tr');
                tr.onclick = () => window.location = product.detail_url;
                tr.style.cursor = 'pointer';
                tr.innerHTML = `
                  <td><div class="cell-card item-name"><i class="bi bi-box-seam me-1 text-success"></i> ${product.name}</div></td>
                  <td><div class="cell-card price-tag">${product.display_price}</div></td>
                  <td><div class="cell-card"><span class="badge bg-light text-dark"><i class="bi bi-tag-fill text-success me-1"></i> ${product.display_category || '-'}</span></div></td>
                  <td><div class="cell-card"><i class="bi bi-shop me-1 text-muted"></i> ${product.display_store || '-'}</div></td>
                  <td><div class="cell-card updated"><i class="bi bi-clock me-1"></i> ${product.formatted_updated_at}</div></td>
                `;
                tableBody.appendChild(tr);

                const mobDiv = document.createElement('div');
                mobDiv.className = 'mobile-item-row';
                mobDiv.onclick = () => window.location = product.detail_url;
                mobDiv.innerHTML = `
                  <div class="item-name-cell">
                    <i class="bi bi-box-seam text-success"></i>
                    <span>${product.name}</span>
                  </div>
                  <div class="item-price-cell">
                    <span class="price-val">${product.display_price}</span>
                  </div>
                `;
                mobileList.appendChild(mobDiv);
              });

              offset += data.length;
              loadMoreBtn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> المزيد....';
              loadMoreBtn.disabled = false;
            } else {
              loadMoreBtn.innerText = 'لا توجد منتجات إضافية';
              loadMoreBtn.disabled = true;
            }
          })
          .catch(error => {
            console.error('Error:', error);
            loadMoreBtn.innerHTML = '<i class="bi bi-plus-circle me-1"></i> المزيد....';
            loadMoreBtn.disabled = false;
          });
      });
    }
  </script>
</body>
</html>
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
          <h2><i class="bi bi-grid-fill text-success me-1"></i> تصفح الأقسام</h2>
        </div>

        @php
          $catPalettes = [
            ['icon_bg'=>'#fef3c7','accent'=>'#d97706','gradient'=>'linear-gradient(135deg,#fef3c7 0%,#fffbeb 100%)','icon'=>'bi-cart3'],
            ['icon_bg'=>'#d1fae5','accent'=>'#059669','gradient'=>'linear-gradient(135deg,#d1fae5 0%,#f0fdf4 100%)','icon'=>'bi-basket'],
            ['icon_bg'=>'#fee2e2','accent'=>'#dc2626','gradient'=>'linear-gradient(135deg,#fee2e2 0%,#fff5f5 100%)','icon'=>'bi-egg-fried'],
            ['icon_bg'=>'#ede9fe','accent'=>'#7c3aed','gradient'=>'linear-gradient(135deg,#ede9fe 0%,#f5f3ff 100%)','icon'=>'bi-fuel-pump'],
            ['icon_bg'=>'#dbeafe','accent'=>'#2563eb','gradient'=>'linear-gradient(135deg,#dbeafe 0%,#eff6ff 100%)','icon'=>'bi-droplet'],
            ['icon_bg'=>'#fce7f3','accent'=>'#db2777','gradient'=>'linear-gradient(135deg,#fce7f3 0%,#fff0f8 100%)','icon'=>'bi-cup-hot'],
            ['icon_bg'=>'#e0f2fe','accent'=>'#0284c7','gradient'=>'linear-gradient(135deg,#e0f2fe 0%,#f0f9ff 100%)','icon'=>'bi-flower1'],
            ['icon_bg'=>'#dcfce7','accent'=>'#16a34a','gradient'=>'linear-gradient(135deg,#dcfce7 0%,#f0fdf4 100%)','icon'=>'bi-box-seam'],
          ];
        @endphp

        <div class="categories-grid pt-3">
          @forelse($categories as $i => $category)
            @php
              $palette = $catPalettes[$i % count($catPalettes)];
            @endphp
            <a href="{{ route('prices') }}?category={{ urlencode($category->name) }}"
               class="cat-pill"
               style="--pill-icon-bg:{{ $palette['icon_bg'] }};--pill-accent:{{ $palette['accent'] }};--pill-gradient:{{ $palette['gradient'] }}">
              <div class="cat-pill-icon" style="color: {{ $palette['accent'] }}; font-size: 1.4rem;">
                @if($category->image && (str_starts_with($category->image, 'http') || str_starts_with($category->image, 'data:image')))
                  <img src="{{ $category->image }}" alt="{{ $category->name }}">
                @elseif($category->image && file_exists(public_path('storage/'.$category->image)))
                  <img src="{{ asset('storage/'.$category->image) }}" alt="{{ $category->name }}">
                @else
                  <i class="bi {{ $palette['icon'] }}"></i>
                @endif
              </div>
              <div class="cat-pill-body">
                <div class="cat-pill-name">{{ $category->name }}</div>
                <div class="cat-pill-count">{{ $category->items_count }} صنف</div>
              </div>
              <div class="cat-pill-arrow">
                <i class="bi bi-chevron-left"></i>
              </div>
            </a>
          @empty
            <div style="grid-column:1/-1;text-align:center;color:var(--text-muted);padding:2rem;">لا توجد أقسام مسجلة حالياً</div>
          @endforelse
        </div>
      </section>

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
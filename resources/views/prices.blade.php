<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | كل الأسعار</title>
  <meta name="description" content="تصفح أسعار السلع المحدثة في جميع المناطق وقارن بين المحلات">
  <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=8') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/imges/logo.png') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
          <li class="nav-item"><a class="nav-link" href="{{ route('shops') }}">المحلات</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>

        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <form action="{{ route('prices') }}" method="GET" class="d-flex align-items-center">
            @if(request('category'))
              <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('store_id'))
              <input type="hidden" name="store_id" value="{{ request('store_id') }}">
            @endif
            <div class="search-pill">
              <input type="text" name="search" placeholder="طحين ، سكر" value="{{ request('search') }}">
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

      <div class="d-flex d-lg-none align-items-center gap-2 mt-2">
        <form action="{{ route('prices') }}" method="GET" class="d-flex align-items-center flex-grow-1">
          @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
          @endif
          @if(request('store_id'))
            <input type="hidden" name="store_id" value="{{ request('store_id') }}">
          @endif
          <div class="search-pill flex-grow-1">
            <input type="text" name="search" placeholder="طحين ، سكر" value="{{ request('search') }}">
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
      <a class="nav-link" href="{{ route('shops') }}">
        <i class="bi bi-shop"></i> دليل المحلات
      </a>
      <a class="nav-link active" href="{{ route('prices') }}">
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

  <!-- HERO BANNER -->
  <section class="shops-header-banner py-5">
    <img src="{{ asset('assets/imges/map.png') }}" alt="أسعار السلع" class="hero-bg-img">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-4">
      
      <!-- HERO TEXT & SEARCH (RIGHT SIDE IN RTL) -->
      <div class="shops-banner-text text-end flex-grow-1" style="max-width: 540px;">
        <h1 class="page-header-title mb-1">
          @if(isset($selectedStore) && $selectedStore)
            أسعار <span class="brand-green">{{ $selectedStore->name }}</span>
            <span class="d-block mt-1" style="font-size:1rem;font-weight:600;color:#9ab0a1;"><i class="bi bi-geo-alt-fill text-success"></i> {{ $selectedStore->region ? $selectedStore->region->city_or_governorate . ' - ' . $selectedStore->region->area_name : 'المتجر المحدد' }}</span>
          @else
            كل <span class="brand-green">الأسعار</span> في مكان واحد
            @if(request('category'))
              <span class="d-block mt-1" style="font-size:1.1rem;font-weight:600;color:#9ab0a1;">— قسم {{ request('category') }}</span>
            @endif
          @endif
        </h1>
        <p class="page-header-sub mb-3">
          @if(isset($selectedStore) && $selectedStore)
            استعراض جميع السلع والأسعار المتاحة لدى {{ $selectedStore->name }}
          @else
            تصفح وقارن أسعار السلع المحدثة لحظة بلحظة
          @endif
        </p>
        
        <!-- SEARCH BAR WITH SEARCH ICON ON THE RIGHT -->
        <form action="{{ route('prices') }}" method="GET" id="hero-search-form">
          @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
          @endif
          @if(request('store_id'))
            <input type="hidden" name="store_id" value="{{ request('store_id') }}">
          @endif
          <div class="hero-search-wrapper d-flex align-items-center">
            <button type="submit" class="hero-search-btn" title="بحث">
              <i class="bi bi-search fs-6"></i>
            </button>
            <input type="text" name="search" id="hero-search-input" placeholder="ابحث عن سلعة معينة (مثل: طحين، سكر، طماطم...)" class="hero-search-field" value="{{ request('search') }}" autocomplete="off">
            @if(request('search'))
              @php
                $clearHref = route('prices');
                $clearParams = [];
                if(request('category')) $clearParams[] = 'category='.urlencode(request('category'));
                if(request('store_id')) $clearParams[] = 'store_id='.request('store_id');
                if(!empty($clearParams)) $clearHref .= '?' . implode('&', $clearParams);
              @endphp
              <a href="{{ $clearHref }}" class="hero-search-clear" title="إلغاء البحث"><i class="bi bi-x-circle-fill"></i></a>
            @endif
          </div>
        </form>
      </div>

      <!-- HERO GRAPHIC (LEFT SIDE IN RTL) -->
      <div class="shops-banner-graphic d-none d-md-block">
        <img src="{{ asset('assets/imges/item.png') }}" alt="أسعار السلع" class="banner-store-img">
      </div>

    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body">
    <div class="container">

      <!-- CATEGORY FILTER PILLS -->
      @php
        $storeIdParam = request('store_id') ? '&store_id='.request('store_id') : '';
        $storeIdParamStart = request('store_id') ? '?store_id='.request('store_id') : '';
      @endphp
      <div class="prices-filter-bar">
        <a href="{{ route('prices') }}{{ $storeIdParamStart }}{{ request('search') ? ($storeIdParamStart ? '&' : '?').'search='.urlencode(request('search')) : '' }}"
           class="filter-pill {{ !request('category') ? 'active' : '' }}"
           id="filter-all"><i class="bi bi-grid-fill me-1"></i> الكل</a>
        @foreach($categories as $cat)
          <a href="{{ route('prices') }}?category={{ urlencode($cat->name) }}{{ $storeIdParam }}{{ request('search') ? '&search='.urlencode(request('search')) : '' }}"
             class="filter-pill {{ request('category') === $cat->name ? 'active' : '' }}"
             id="filter-cat-{{ $cat->id }}">
            {{ $cat->name }}
            <span class="filter-pill-count">{{ $cat->items_count }}</span>
          </a>
        @endforeach
      </div>

      <!-- STORE FILTER BANNER (عرض المتجر المحدد) -->
      @if(isset($selectedStore) && $selectedStore)
        <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-white rounded-4 shadow-sm border border-success-subtle">
          <div class="d-flex align-items-center gap-3">
            <img src="{{ $selectedStore->image_url }}" alt="{{ $selectedStore->name }}" class="rounded-circle shadow-sm"
                 style="width:46px;height:46px;object-fit:cover;border:2px solid var(--brand-green);"
                 onerror="this.src='{{ asset('assets/imges/shops.png') }}'">
            <div>
              <div class="fw-bolder text-dark">{{ $selectedStore->name }}</div>
              <div class="small text-muted">
                <i class="bi bi-geo-alt-fill text-success me-1"></i>
                {{ $selectedStore->region ? $selectedStore->region->city_or_governorate.' - '.$selectedStore->region->area_name : ($selectedStore->address ?? 'قطاع غزة') }}
              </div>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2">
            <span class="badge bg-success-subtle text-success fw-bold px-3 py-2">
              <i class="bi bi-funnel-fill me-1"></i> عرض سلع هذا المتجر فقط
            </span>
            <a href="{{ route('prices') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold">
              <i class="bi bi-x-circle me-1"></i> عرض كل السلع
            </a>
          </div>
        </div>
      @endif

      <!-- RESULTS COUNT -->
      <div class="d-flex align-items-center justify-content-between mb-4">
        <p class="text-muted mb-0" style="font-size:.9rem;font-weight:600;">
          @if(request('search') || request('category') || request('store_id'))
            <i class="bi bi-funnel-fill text-success me-1"></i> نتائج الفلترة:
            <strong style="color:var(--text-dark);">{{ $products->count() }} سلعة</strong>
            @if(request('category'))
              في قسم <strong style="color:var(--brand-green);">{{ request('category') }}</strong>
            @endif
            @if(request('search'))
              تطابق "<strong>{{ request('search') }}</strong>"
            @endif
            @if(isset($selectedStore) && $selectedStore)
              في متجر <strong style="color:var(--brand-green);">{{ $selectedStore->name }}</strong>
            @endif
          @else
            <i class="bi bi-box-seam text-success me-1"></i> إجمالي السلع: <strong style="color:var(--text-dark);">{{ $products->count() }}</strong>
          @endif
        </p>
        @if(request('category') || request('search') || request('store_id'))
          <a href="{{ route('prices') }}" class="text-decoration-none d-flex align-items-center gap-1" style="font-size:.85rem;color:var(--text-muted);font-weight:600;">
            <i class="bi bi-x-circle"></i>
            مسح الفلتر
          </a>
        @endif
      </div>

      <!-- PRODUCTS GRID -->
      @if($products->isEmpty())
        <div class="text-center py-5" style="min-height:300px;display:flex;flex-direction:column;align-items:center;justify-content:center;">
          <div style="font-size:3.5rem;margin-bottom:1rem;color:#94a3b8;"><i class="bi bi-search"></i></div>
          <h3 style="font-weight:800;color:var(--text-dark);margin-bottom:.5rem;">لا توجد نتائج</h3>
          <p class="text-muted mb-3">لم يتم العثور على سلع مطابقة للبحث الحالي</p>
          <a href="{{ route('prices') }}" class="btn-locate text-decoration-none d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-clockwise"></i> عرض كل الأسعار
          </a>
        </div>
      @else
        <div class="row g-4 pb-4" id="prices-grid">
          @foreach($products as $product)
            @php
              $bestPrice    = $product->best_price;
              $bestStore    = $product->best_store;
              $categoryName = $product->category_name;
              $catIcons     = ['خضراوات'=>'bi-basket','فواكه'=>'bi-apple','لحوم'=>'bi-egg-fried','مواد غذائية'=>'bi-cart3','زيوت ودهون'=>'bi-droplet','حبوب'=>'bi-box-seam','أسماك'=>'bi-water','ألبان'=>'bi-cup-hot','مخبوزات'=>'bi-cookie','وقود'=>'bi-fuel-pump'];
              $catIcon      = $catIcons[$categoryName] ?? 'bi-box-seam';
            @endphp
            <div class="col-12 col-sm-6 col-lg-3">
              <div class="product-card position-relative" onclick="window.location='{{ route('products.show', $product->id) }}'" style="cursor:pointer;">

                <!-- Favorite Button -->
                <button
                  type="button"
                  class="fav-btn position-absolute"
                  style="top: 10px; left: 10px; z-index: 5; background: rgba(255,255,255,0.85); backdrop-filter: blur(4px); border: none; border-radius: 50%; width: 34px; height: 34px; display: flex; align-items: center; justify-content: center; color: #cbd5e1; transition: all 0.2s;"
                  onclick="event.stopPropagation(); toggleProductFavorite({{ $product->id }}, this)"
                  title="إضافة إلى المفضلة">
                  <i class="bi bi-heart-fill"></i>
                </button>

                <div class="category-badge" title="{{ $categoryName }}"><i class="bi {{ $catIcon }}"></i></div>

                <div class="product-img-wrap">
                  @if($product->formatted_image_url)
                    <img src="{{ $product->formatted_image_url }}" alt="{{ $product->name }}" class="product-img"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                    <div style="display:none;font-size:3rem;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                  @else
                    <div style="font-size:3rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                  @endif
                </div>

                <div class="product-info">
                  <div class="product-name">{{ $product->name }}</div>
                  @if($bestStore)
                    <div class="product-sub"><i class="bi bi-shop me-1 text-muted"></i> {{ $bestStore }}</div>
                  @endif
                  <div class="product-price">
                    @if($bestPrice)
                      {{ number_format($bestPrice, 2) }} ₪
                    @else
                      <span style="color:var(--text-muted);font-size:.88rem;">السعر غير محدد</span>
                    @endif
                  </div>
                </div>

                <a href="{{ route('compare', ['item_id' => $product->id]) }}"
                   class="btn-compare-store text-center text-decoration-none d-block"
                   onclick="event.stopPropagation();">
                  <i class="bi bi-arrow-left-right me-1"></i> مقارنة الأسعار في المحلات
                </a>
              </div>
            </div>
          @endforeach
        </div>
      @endif

    </div>
  </main>

  <!-- FOOTER -->
  @include('partials.footer')

  <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/script.js?v=8') }}"></script>
  <script>
    function toggleProductFavorite(id, btn) {
      @if(!Auth::check())
        window.location.href = '{{ route("login") }}';
        return;
      @endif
      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
      fetch('{{ route("favorites.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ type: 'item', reference_id: id })
      })
      .then(res => {
        if (res.status === 401 || res.redirected) {
          window.location.href = '{{ route("login") }}';
          return;
        }
        return res.json();
      })
      .then(data => {
        if (!data) return;
        if (data.status === 'unauthenticated') {
          window.location.href = '{{ route("login") }}';
        } else if (data.status === 'added') {
          btn.style.color = '#ef4444';
          btn.style.transform = 'scale(1.2)';
          setTimeout(() => btn.style.transform = 'scale(1)', 200);
          showToast(data.message, 'success');
        } else if (data.status === 'removed') {
          btn.style.color = '#cbd5e1';
          showToast(data.message, 'warning');
        }
      })
      .catch(() => showToast('حدث خطأ، حاول مرة أخرى', 'danger'));
    }

    function showToast(msg, type='success') {
      const t = document.createElement('div');
      t.className = `alert alert-${type} position-fixed d-flex align-items-center gap-2 shadow-lg`;
      t.style.cssText = 'bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 240px; border-radius: 12px; font-weight: 700;';
      t.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : 'info-circle-fill'}"></i> ${msg}`;
      document.body.appendChild(t);
      setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.4s'; setTimeout(() => t.remove(), 400); }, 2200);
    }
  </script>
</body>

</html>

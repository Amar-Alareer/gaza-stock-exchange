<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>وفر كاش | الملف الشخصي</title>
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/css/style.css?v=8') }}">
  <link rel="icon" type="image/png" href="{{asset('assets/imges/logo.png')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <style>
    .profile-header-banner {
      position: relative;
      background: linear-gradient(135deg, #0b1f13 0%, #17692e 60%, #24df64 100%);
      padding: 40px 0 80px;
      overflow: hidden;
    }
    .profile-header-banner .hero-bg-img {
      position: absolute; inset: 0; width: 100%; height: 100%;
      object-fit: cover; opacity: 0.08;
    }
    .user-avatar {
      width: 80px !important; height: 80px !important; border-radius: 50% !important;
      object-fit: cover !important; border: 3px solid #24df64; display: inline-block !important;
      aspect-ratio: 1 / 1 !important; flex-shrink: 0;
    }
    .avatar-preview-img {
      width: 96px !important; height: 96px !important; border-radius: 50% !important;
      object-fit: cover !important; border: 3px solid #17692e; display: inline-block !important;
      aspect-ratio: 1 / 1 !important;
    }
    .user-name { font-size: 1.8rem; font-weight: 900; color: #fff; }
    .user-location { font-size: 0.9rem; color: rgba(255,255,255,0.75); }
    .btn-logout {
      background: rgba(255,255,255,0.12); backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.25); color: #fff;
      border-radius: 12px; padding: 8px 18px; font-weight: 700;
      text-decoration: none; display: inline-flex; align-items: center; gap: 7px;
      transition: all 0.2s;
    }
    .btn-logout:hover { background: rgba(255,255,255,0.22); color: #fff; }
    .profile-stat-card {
      background: #fff; border-radius: 18px; padding: 20px 16px;
      text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.06);
      border: 1.5px solid #f1f5f9; transition: transform 0.2s, box-shadow 0.2s;
    }
    .profile-stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .stat-number { font-size: 1.8rem; font-weight: 900; color: #0b1f13; }
    .stat-label { font-size: 0.8rem; color: #64748b; font-weight: 600; margin-top: 4px; }
    .profile-tabs-bar {
      display: flex; gap: 8px; background: #f8fafc; border-radius: 16px; padding: 6px; overflow-x: auto;
    }
    .tab-btn {
      flex: 1; min-width: max-content; border: none; background: transparent; padding: 10px 20px;
      border-radius: 12px; font-weight: 700; font-size: 0.9rem; cursor: pointer;
      color: #64748b; transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
      justify-content: center; font-family: 'Tajawal', sans-serif;
    }
    .tab-btn.active {
      background: #fff; color: #17692e; box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .tab-content-panel { display: none; }
    .tab-content-panel.active { display: block; }
    .empty-state {
      text-align: center; padding: 60px 20px; color: #94a3b8;
    }
    .empty-state i { font-size: 3.5rem; display: block; margin-bottom: 16px; }
    .settings-form-card {
      background: #fff; border-radius: 20px; padding: 32px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1.5px solid #f1f5f9;
    }
    .avatar-upload-wrap { position: relative; display: inline-block; cursor: pointer; }
    .avatar-upload-overlay {
      position: absolute; inset: 0; border-radius: 50%; background: rgba(0,0,0,0.45);
      display: flex; align-items: center; justify-content: center; color: #fff;
      opacity: 0; transition: opacity 0.2s; font-size: 1.3rem;
    }
    .avatar-upload-wrap:hover .avatar-upload-overlay { opacity: 1; }
    .fav-btn {
      border: none; background: none; cursor: pointer; padding: 4px 8px;
      border-radius: 8px; transition: all 0.2s; font-size: 1.1rem; color: #cbd5e1;
    }
    .fav-btn.active { color: #ef4444; }
    .fav-btn:hover { transform: scale(1.15); }
    .guest-banner {
      background: linear-gradient(135deg, #f0fdf4, #dcfce7);
      border: 2px solid #86efac; border-radius: 20px; padding: 40px;
      text-align: center;
    }
    .alert-profile { border-radius: 14px; font-weight: 600; font-size: 0.9rem; }
  </style>
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
          <li class="nav-item"><a class="nav-link" href="{{ route('prices') }}">الاسعار</a></li>
        </ul>
        <div class="d-none d-lg-flex align-items-center gap-3 order-3">
          <form action="{{ route('prices') }}" method="GET" class="search-pill m-0">
            <input type="text" name="search" placeholder="طحين ، سكر">
            <button type="submit"><i class="bi bi-search"></i></button>
          </form>
          <a href="{{route('compare')}}" class="btn-compare">
            <i class="bi bi-arrow-left-right"></i> مقارنة الاسعار
          </a>
          @if(Auth::check())
            <a href="{{route('profile')}}" class="d-flex align-items-center text-decoration-none" title="الملف الشخصي">
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
      <a class="nav-link" href="{{ route('prices') }}">
        <i class="bi bi-tag-fill"></i> قائمة الأسعار
      </a>
      <a class="nav-link" href="{{ route('compare') }}">
        <i class="bi bi-arrow-left-right"></i> مقارنة الأسعار
      </a>
      @if(Auth::check())
        <a class="nav-link active" href="{{ route('profile') }}">
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

  @if($user)
  {{-- ====== LOGGED IN VIEW ====== --}}

  <!-- PROFILE HEADER BANNER -->
  <section class="profile-header-banner">
    <img src="{{ asset('assets/imges/map.png') }}" alt="خريطة" class="hero-bg-img">
    <div class="container">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          @if($user->role === 'admin')
            <a href="http://localhost:5173/dashboard" class="btn-admin-portal d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 0.5rem 1.15rem; border-radius: 22px; font-weight: 800; font-size: 0.88rem; text-decoration: none; box-shadow: 0 4px 12px rgba(16,185,129,0.35); transition: all 0.2s;">
              <i class="bi bi-speedometer2 fs-5"></i>
              <span>لوحة التحكم الإدارية</span>
            </a>
          @endif
          <!-- LOGOUT BUTTON -->
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
              <i class="bi bi-box-arrow-right"></i> تسجيل خروج
            </button>
          </form>
        </div>

        <!-- USER INFO -->
        <div class="d-flex align-items-center gap-3 text-end">
          <div>
            <h1 class="user-name mb-1 d-flex align-items-center justify-content-end gap-2 flex-wrap">
              {{ $user->name }}
              @if($user->role === 'admin')
                <span class="badge bg-warning text-dark" style="font-size:0.72rem !important; border-radius: 8px;"><i class="bi bi-shield-fill-check"></i> مسؤول النظام</span>
              @endif
            </h1>
            <div class="user-location">
              <i class="bi bi-geo-alt-fill text-success me-1"></i>
              {{ $user->region ? $user->region->city_or_governorate . ' - ' . $user->region->area_name : ($user->address ?? 'قطاع غزة') }}
            </div>
            <div class="mt-1" style="color: rgba(255,255,255,0.6); font-size: 0.85rem;">
              <i class="bi bi-envelope-fill me-1"></i> {{ $user->email }}
              @if($user->phone)
                &nbsp;·&nbsp; <i class="bi bi-telephone-fill me-1"></i> {{ $user->phone }}
              @endif
            </div>
          </div>
          <div class="avatar-wrapper">
            <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}" class="user-avatar">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- MAIN BODY -->
  <main class="page-body pt-0">
    <div class="container">

      {{-- Flash Messages --}}
      @if(session('success'))
        <div class="alert alert-success alert-profile d-flex align-items-center gap-2 mt-3" role="alert">
          <i class="bi bi-check-circle-fill fs-5"></i> {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger alert-profile mt-3">
          @foreach($errors->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $err }}</div>
          @endforeach
        </div>
      @endif

      <!-- STATS ROW -->
      <div class="profile-stats-row row g-3 mb-4 mt-2">
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number brand-green">{{ $favoriteItems->count() }}</div>
            <div class="stat-label"><i class="bi bi-heart-fill text-danger me-1"></i> سلعة مفضلة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number">{{ $favoriteStores->count() }}</div>
            <div class="stat-label"><i class="bi bi-shop text-success me-1"></i> محلات مفضلة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number">{{ $favoriteItems->count() + $favoriteStores->count() }}</div>
            <div class="stat-label"><i class="bi bi-star-fill text-warning me-1"></i> إجمالي المفضلة</div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="profile-stat-card">
            <div class="stat-number brand-green">
              @php
                $savings = $favoriteItems->sum(function($item) {
                  $best = $item->best_price;
                  return $best ? round($best * 0.1, 0) : 0;
                });
              @endphp
              {{ $savings > 0 ? $savings . ' ₪' : '—' }}
            </div>
            <div class="stat-label"><i class="bi bi-piggy-bank-fill text-success me-1"></i> توفير تقديري</div>
          </div>
        </div>
      </div>

      <!-- TABS BAR -->
      <div class="profile-tabs-bar mb-4">
        <button class="tab-btn active" onclick="switchTab('favorites')" id="tab-favorites">
          <i class="bi bi-heart-fill text-danger"></i> السلع المفضلة
          @if($favoriteItems->count() > 0)
            <span class="badge bg-danger rounded-pill" style="font-size:0.7rem;">{{ $favoriteItems->count() }}</span>
          @endif
        </button>
        <button class="tab-btn" onclick="switchTab('stores')" id="tab-stores">
          <i class="bi bi-shop text-success"></i> المحلات المفضلة
          @if($favoriteStores->count() > 0)
            <span class="badge bg-success rounded-pill" style="font-size:0.7rem;">{{ $favoriteStores->count() }}</span>
          @endif
        </button>
        <button class="tab-btn" onclick="switchTab('settings')" id="tab-settings">
          <i class="bi bi-gear-fill text-muted"></i> إعدادات الحساب
        </button>
      </div>

      <!-- TAB: FAVORITE ITEMS -->
      <div class="tab-content-panel active" id="panel-favorites">
        @if($favoriteItems->isEmpty())
          <div class="empty-state">
            <i class="bi bi-heart text-danger"></i>
            <h5 class="fw-bold text-dark">لا توجد سلع مفضلة بعد</h5>
            <p class="text-muted mb-3">تصفح الأسعار وأضف السلع التي تهمك إلى المفضلة لمتابعتها بسهولة</p>
            <a href="{{ route('prices') }}" class="btn btn-success rounded-pill px-4 fw-bold">
              <i class="bi bi-search me-1"></i> تصفح الأسعار
            </a>
          </div>
        @else
          <div class="row g-4 pb-4">
            @foreach($favoriteItems as $item)
              @php
                $bestPrice    = $item->best_price;
                $categoryName = $item->category_name;
                $catIcons     = ['خضراوات'=>'bi-basket','فواكه'=>'bi-apple','لحوم'=>'bi-egg-fried','مواد غذائية'=>'bi-cart3','زيوت ودهون'=>'bi-droplet','حبوب'=>'bi-box-seam','أسماك'=>'bi-water','ألبان'=>'bi-cup-hot','مخبوزات'=>'bi-cookie','وقود'=>'bi-fuel-pump'];
                $catIcon      = $catIcons[$categoryName] ?? 'bi-box-seam';
              @endphp
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="product-card h-100 position-relative">
                  <!-- Remove Favorite Button -->
                  <button
                    class="fav-btn active position-absolute"
                    style="top: 10px; left: 10px; z-index: 5; background: rgba(255,255,255,0.9); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                    onclick="toggleFavorite('item', {{ $item->id }}, this)"
                    title="إزالة من المفضلة">
                    <i class="bi bi-heart-fill"></i>
                  </button>

                  <div class="category-badge"><i class="bi {{ $catIcon }}"></i></div>
                  <div class="product-img-wrap">
                    @if($item->image_url && str_starts_with($item->image_url, 'http'))
                      <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="product-img"
                           onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                      <div style="display:none;font-size:2.5rem;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                    @else
                      <div style="font-size:3rem;display:flex;align-items:center;justify-content:center;width:100%;height:100%;color:#16a34a;"><i class="bi {{ $catIcon }}"></i></div>
                    @endif
                  </div>
                  <div class="product-info text-end">
                    <div class="product-name">{{ $item->name }}</div>
                    <div class="product-sub"><i class="bi bi-tag me-1"></i> {{ $categoryName }}</div>
                    <div class="product-price">
                      {{ $bestPrice ? number_format($bestPrice, 2) . ' ₪' : 'غير محدد' }}
                    </div>
                  </div>
                  <a href="{{ route('products.show', $item->id) }}" class="btn-compare-store text-decoration-none text-center d-block">
                    <i class="bi bi-arrow-left-right me-1"></i> قارن السعر مع المحلات
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- TAB: FAVORITE STORES -->
      <div class="tab-content-panel" id="panel-stores">
        @if($favoriteStores->isEmpty())
          <div class="empty-state">
            <i class="bi bi-shop-window text-success"></i>
            <h5 class="fw-bold text-dark">لا توجد محلات مفضلة بعد</h5>
            <p class="text-muted mb-3">تصفح المحلات وأضف المحلات المفضلة لمتابعة أسعارها</p>
            <a href="{{ route('shops') }}" class="btn btn-success rounded-pill px-4 fw-bold">
              <i class="bi bi-shop me-1"></i> تصفح المحلات
            </a>
          </div>
        @else
          <div class="row g-4 pb-4">
            @foreach($favoriteStores as $store)
              @php
                $storeImg   = $store->image ? (str_starts_with($store->image,'http') ? $store->image : asset('storage/'.$store->image)) : asset('assets/imges/shops.png');
                $regionName = $store->region ? $store->region->city_or_governorate . ' - ' . $store->region->area_name : ($store->address ?? 'قطاع غزة');
              @endphp
              <div class="col-12 col-md-6 col-lg-4">
                <div class="shop-directory-card p-3 bg-white rounded-4 shadow-sm h-100 d-flex flex-column justify-content-between position-relative">
                  <!-- Remove Store Favorite -->
                  <button
                    class="fav-btn active position-absolute"
                    style="top: 12px; left: 12px; z-index: 5; background: rgba(255,255,255,0.9); border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"
                    onclick="toggleFavorite('store', {{ $store->id }}, this)"
                    title="إزالة من المفضلة">
                    <i class="bi bi-heart-fill"></i>
                  </button>

                  <div>
                    <div class="d-flex align-items-start gap-3 mb-3">
                      <img src="{{ $storeImg }}" alt="{{ $store->name }}" class="rounded-circle shadow-sm" style="width: 52px; height: 52px; object-fit: cover; border: 2px solid var(--brand-green);">
                      <div>
                        <h5 class="fw-bold mb-1">{{ $store->name }}</h5>
                        <div class="small text-muted"><i class="bi bi-geo-alt-fill text-success me-1"></i> {{ $regionName }}</div>
                        @if($store->phone)
                          <div class="small text-muted"><a href="tel:{{ $store->phone }}" class="text-decoration-none text-dark"><i class="bi bi-telephone-fill text-success me-1"></i> {{ $store->phone }}</a></div>
                        @endif
                      </div>
                    </div>
                  </div>
                  <a href="{{ route('shop-details.show', $store->id) }}" class="btn-locate w-100 justify-content-center py-2 text-decoration-none d-flex align-items-center gap-2 mt-2">
                    <i class="bi bi-shop"></i> عرض صفحة المحل
                  </a>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      <!-- TAB: SETTINGS -->
      <div class="tab-content-panel" id="panel-settings">
        <div class="row g-4 pb-5">

          <!-- Update Profile Form -->
          <div class="col-12 col-lg-7">
            <div class="settings-form-card">
              <h5 class="fw-bold mb-4"><i class="bi bi-person-gear text-success me-2"></i> تعديل بيانات الحساب</h5>

              <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Avatar Upload -->
                <div class="text-center mb-4">
                  <label for="avatar-input" class="avatar-upload-wrap" title="انقر لتغيير الصورة الشخصية">
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->name }}"
                         class="avatar-preview-img" id="avatar-preview">
                    <div class="avatar-upload-overlay">
                      <i class="bi bi-camera-fill"></i>
                    </div>
                  </label>
                  <input type="file" id="avatar-input" name="profile_picture" accept="image/*" class="d-none" onchange="previewAvatar(this)">
                  <div class="small text-muted mt-2 fw-bold"><i class="bi bi-camera me-1"></i> انقر على الصورة لاختيار صورة من جهازك</div>
                </div>

                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label fw-bold small">الاسم الكامل *</label>
                    <input type="text" name="name" class="form-control rounded-3 @error('name') is-invalid @enderror"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">البريد الإلكتروني</label>
                    <input type="email" class="form-control rounded-3 bg-light" value="{{ $user->email }}" disabled>
                    <div class="form-text">لا يمكن تغيير البريد الإلكتروني حالياً</div>
                  </div>

                  <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">رقم الهاتف</label>
                    <input type="tel" name="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror"
                           value="{{ old('phone', $user->phone) }}" placeholder="059XXXXXXX">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>

                  <div class="col-12">
                    <label class="form-label fw-bold small">العنوان / الحي</label>
                    <input type="text" name="address" class="form-control rounded-3 @error('address') is-invalid @enderror"
                           value="{{ old('address', $user->address) }}" placeholder="مثال: حي الرمال، غزة">
                    @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>

                <hr class="my-4">

                <h6 class="fw-bold mb-3"><i class="bi bi-lock-fill text-warning me-2"></i> تغيير كلمة المرور (اختياري)</h6>
                <div class="row g-3">
                  <div class="col-12">
                    <label class="form-label fw-bold small">كلمة المرور الحالية</label>
                    <input type="password" name="current_password" class="form-control rounded-3 @error('current_password') is-invalid @enderror" placeholder="أدخل كلمة المرور الحالية">
                    @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                  <div class="col-12">
                    <label class="form-label fw-bold small">كلمة المرور الجديدة</label>
                    <input type="password" name="new_password" class="form-control rounded-3 @error('new_password') is-invalid @enderror" placeholder="6 أحرف على الأقل">
                    @error('new_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                  </div>
                </div>

                <button type="submit" class="btn-locate w-100 justify-content-center py-2 mt-4 d-flex align-items-center gap-2">
                  <i class="bi bi-check-circle-fill"></i> حفظ التعديلات
                </button>
              </form>
            </div>
          </div>

          <!-- Account Info Card -->
          <div class="col-12 col-lg-5">
            <div class="settings-form-card mb-3">
              <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i> معلومات الحساب</h6>
              <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                  <div class="text-muted small fw-bold">اسم المستخدم</div>
                  <div class="fw-bold">{{ $user->name }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                  <div class="text-muted small fw-bold">البريد الإلكتروني</div>
                  <div class="fw-bold small">{{ $user->email }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                  <div class="text-muted small fw-bold">نوع الحساب</div>
                  <span class="badge bg-success rounded-pill px-3">
                    {{ $user->role === 'admin' ? 'مدير النظام' : 'مستخدم' }}
                  </span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                  <div class="text-muted small fw-bold">تاريخ الإنشاء</div>
                  <div class="fw-bold small">{{ $user->created_at->format('d/m/Y') }}</div>
                </div>
                @if($user->phone)
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3">
                  <div class="text-muted small fw-bold">رقم الهاتف</div>
                  <div class="fw-bold">{{ $user->phone }}</div>
                </div>
                @endif
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="settings-form-card">
              <h6 class="fw-bold mb-3"><i class="bi bi-lightning-fill text-warning me-2"></i> روابط سريعة</h6>
              <div class="d-flex flex-column gap-2">
                <a href="{{ route('prices') }}" class="btn btn-outline-success rounded-3 fw-bold d-flex align-items-center gap-2">
                  <i class="bi bi-tag-fill"></i> تصفح الأسعار
                </a>
                <a href="{{ route('map') }}" class="btn btn-outline-primary rounded-3 fw-bold d-flex align-items-center gap-2">
                  <i class="bi bi-geo-alt-fill"></i> خريطة المحلات
                </a>
                <a href="{{ route('compare') }}" class="btn btn-outline-secondary rounded-3 fw-bold d-flex align-items-center gap-2">
                  <i class="bi bi-arrow-left-right"></i> مقارنة الأسعار
                </a>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </main>

  @else
  {{-- ====== GUEST / NOT LOGGED IN VIEW ====== --}}

  <section class="page-body">
    <div class="container py-5">
      <div class="guest-banner mx-auto" style="max-width: 560px;">
        <div style="font-size: 3.5rem; margin-bottom: 16px;">👤</div>
        <h2 class="fw-bolder text-dark mb-2">سجّل دخولك أولاً</h2>
        <p class="text-muted mb-4">لعرض ملفك الشخصي وسلعك المفضلة، يرجى تسجيل الدخول إلى حسابك في وفر كاش.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="{{ route('login') }}" class="btn btn-success rounded-pill px-5 py-2 fw-bold fs-6">
            <i class="bi bi-box-arrow-in-right me-1"></i> تسجيل الدخول
          </a>
          <a href="{{ route('signup') }}" class="btn btn-outline-success rounded-pill px-5 py-2 fw-bold fs-6">
            <i class="bi bi-person-plus me-1"></i> إنشاء حساب جديد
          </a>
        </div>
      </div>
    </div>
  </section>
  @endif

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
  <script src="{{asset('assets/js/script.js?v=8')}}"></script>

  <script>
    // ===== Tab Switching =====
    function switchTab(tab) {
      document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.remove('active'));
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      const targetPanel = document.getElementById('panel-' + tab);
      const targetBtn = document.getElementById('tab-' + tab);
      if (targetPanel && targetBtn) {
        targetPanel.classList.add('active');
        targetBtn.classList.add('active');
      }
    }

    // Auto-select tab if in URL e.g. ?tab=settings
    document.addEventListener('DOMContentLoaded', function() {
      const urlParams = new URLSearchParams(window.location.search);
      const tab = urlParams.get('tab');
      if (tab) {
        switchTab(tab);
      }
    });

    // ===== Avatar Preview =====
    function previewAvatar(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    // ===== Toggle Favorite =====
    function toggleFavorite(type, referenceId, btn) {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

      fetch('{{ route("favorites.toggle") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ type, reference_id: referenceId }),
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'removed') {
          // Remove card from DOM with animation
          const card = btn.closest('[class*="col-"]');
          if (card) {
            card.style.transition = 'opacity 0.4s, transform 0.4s';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
              card.remove();
              updateStatCount(type);
            }, 400);
          }
          showToast(data.message, 'warning');
        } else if (data.status === 'unauthenticated') {
          window.location.href = '{{ route("login") }}';
        } else {
          btn.classList.toggle('active');
          showToast(data.message, 'success');
        }
      })
      .catch(() => showToast('حدث خطأ، حاول مرة أخرى', 'danger'));
    }

    function updateStatCount(type) {
      // Update count badges after removing
      const panelId = type === 'item' ? 'panel-favorites' : 'panel-stores';
      const panel   = document.getElementById(panelId);
      if (!panel) return;
      const remaining = panel.querySelectorAll('[class*="col-"]').length;
      if (remaining === 0) {
        const emptyHtml = type === 'item'
          ? `<div class="empty-state"><i class="bi bi-heart text-danger"></i><h5 class="fw-bold text-dark">لا توجد سلع مفضلة بعد</h5><a href="{{ route('prices') }}" class="btn btn-success rounded-pill px-4 fw-bold mt-2">تصفح الأسعار</a></div>`
          : `<div class="empty-state"><i class="bi bi-shop-window text-success"></i><h5 class="fw-bold text-dark">لا توجد محلات مفضلة بعد</h5><a href="{{ route('shops') }}" class="btn btn-success rounded-pill px-4 fw-bold mt-2">تصفح المحلات</a></div>`;
        const row = panel.querySelector('.row');
        if (row) row.outerHTML = emptyHtml;
      }
    }

    // ===== Toast Notification =====
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `alert alert-${type} position-fixed d-flex align-items-center gap-2 shadow-lg`;
      toast.style.cssText = 'bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 9999; min-width: 260px; border-radius: 14px; font-weight: 700; animation: slideUp 0.3s ease;';
      toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle-fill' : type === 'warning' ? 'exclamation-triangle-fill' : 'x-circle-fill'}"></i> ${message}`;
      document.body.appendChild(toast);
      setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.4s'; setTimeout(() => toast.remove(), 400); }, 2500);
    }

    // Auto-dismiss flash messages
    setTimeout(() => {
      document.querySelectorAll('.alert-profile').forEach(a => {
        a.style.transition = 'opacity 0.5s';
        a.style.opacity = '0';
        setTimeout(() => a.remove(), 500);
      });
    }, 4000);
  </script>
</body>

</html>
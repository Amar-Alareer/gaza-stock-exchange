<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>وفر كاش | تسجيل الدخول</title>
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{asset('assets/css/style.css?v=10')}}">
<link rel="icon" type="image/png" href="{{asset('assets/imges/logo.png')}}">
</head>
<body class="auth-page-body">
  @include('partials.splash-screen')
<img src="{{asset('assets/imges/map.png')}}" alt="خلفية" class="auth-bg-img">
<div class="container d-flex align-items-center justify-content-center min-vh-100 py-4">
  <div class="auth-card row g-0 w-100 overflow-hidden shadow-lg rounded-5" style="max-width: 1000px;">

    <!-- RIGHT PANEL IN RTL (GREEN CONTENT PANEL) -->
    <div class="col-12 col-md-6 auth-panel-right d-flex flex-column justify-content-between p-4 p-lg-5 text-end">
      <div>
        <div class="d-flex align-items-center justify-content-end gap-2 mb-4">
          <div class="logo-text text-end">
            <span class="ar"><span class="brand-green">وفر</span><span class="brand-white">كاش</span></span>
            <span class="en">CASH WAFAR</span>
          </div>
          <img src="{{asset('assets/imges/logo.png')}}" alt="وفر كاش" class="logo-icon" style="width: 46px; height: 46px;">
        </div>

        <h2 class="auth-title text-white fw-bolder mb-3">انضم لآلاف المستخدمين<br>ووفر من مصروفك اليومي</h2>
        <p class="auth-subtext text-light opacity-75 mb-4">احفظ سلعك المفضلة، فعّل تنبيهات الأسعار، واكتشف أرخص المحلات في منطقتك أولاً بأول.</p>
      </div>

      <div class="auth-features-list">
        <div class="d-flex align-items-center justify-content-between text-white py-2 border-bottom border-secondary border-opacity-25">
          <span>تنبيهات فورية عند انخفاض الأسعار</span>
          <span class="feature-check"><i class="bi bi-check-circle-fill text-success"></i></span>
        </div>
        <div class="d-flex align-items-center justify-content-between text-white py-2 border-bottom border-secondary border-opacity-25">
          <span>مقارنة أسعار بين آلاف المحلات</span>
          <span class="feature-check"><i class="bi bi-check-circle-fill text-success"></i></span>
        </div>
        <div class="d-flex align-items-center justify-content-between text-white py-2">
          <span>حفظ محلاتك وسلعك المفضلة</span>
          <span class="feature-check"><i class="bi bi-check-circle-fill text-success"></i></span>
        </div>
      </div>
    </div>

    <!-- LEFT PANEL IN RTL (FORM PANEL) -->
    <div class="col-12 col-md-6 auth-panel-left bg-light p-4 p-lg-5">
      <!-- TABS -->
      <div class="d-flex p-1 bg-secondary bg-opacity-25 rounded-pill mb-4 mx-auto" style="max-width: 320px;">
        <a href="{{route('login')}}" class="btn flex-fill rounded-pill fw-bold active-tab-btn">تسجيل دخول</a>
        <a href="{{route('signup')}}" class="btn flex-fill rounded-pill fw-bold text-muted">إنشاء حساب</a>
      </div>

      <!-- FLASH / ERROR MESSAGES -->
      @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 py-2 mb-3 rounded-3" style="font-size: 0.88rem;">
          <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger py-2 mb-3 rounded-3" style="font-size: 0.88rem;">
          @foreach($errors->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill me-1"></i> {{ $err }}</div>
          @endforeach
        </div>
      @endif

      <!-- LOGIN FORM -->
      <form action="{{route('login.post')}}" method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-bold small">البريد الإلكتروني أو اسم المستخدم</label>
          <input type="text" name="email" class="form-control form-control-lg border-0 bg-white" placeholder="yourname@gmail.com" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label fw-bold small mb-0">كلمة المرور</label>
            <a href="#" class="text-success text-decoration-none fw-bold small">نسيت كلمة المرور</a>
          </div>
          <input type="password" name="password" class="form-control form-control-lg border-0 bg-white" placeholder="••••••••••••" required>
        </div>

        <div class="d-flex justify-content-end align-items-center gap-2 mb-4">
          <label for="remember" class="fw-bold small mb-0 text-muted">تذكرني</label>
          <input type="checkbox" name="remember" id="remember" class="form-check-input mt-0" checked>
        </div>

        <button type="submit" class="btn-locate w-100 justify-content-center py-3 fs-5 mb-4 d-flex align-items-center gap-2">
          <i class="bi bi-box-arrow-in-right"></i> تسجيل دخول
        </button>
      </form>

      <div class="position-relative text-center mb-4">
        <hr class="text-muted">
        <span class="position-absolute top-50 start-50 translate-middle px-3 fw-bold bg-light text-muted small">أو</span>
      </div>

      <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary w-100 py-2.5 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 bg-white text-dark text-decoration-none shadow-sm">
        <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
        المتابعة عبر حساب جوجل
      </a>

      <!-- رابط خاص للمسؤولين للانتقال للوحة الإدارة -->
      <div class="text-center mt-4 pt-2 border-top">
        <small class="text-muted">هل أنت مسؤول النظام؟</small>
        <br>
        <a href="http://localhost:5173/login" class="d-inline-flex align-items-center gap-1 mt-1 text-decoration-none fw-bold" style="color: #10b981; font-size: 0.82rem;">
          <i class="bi bi-shield-lock-fill"></i>
          الدخول للوحة تحكم الإدارة
        </a>
      </div>
    </div>

  </div>
</div>

<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/script.js?v=8')}}"></script>
</body>
</html>

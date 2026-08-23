<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>وفر كاش | إنشاء حساب</title>
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('assets/css/style.css?v=2')}}">
<link rel="icon" type="image/png" href="{{asset('assets/imges/logo.png')}}">
</head>
<body class="auth-page-body">
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
          <span class="feature-check">✓</span>
        </div>
        <div class="d-flex align-items-center justify-content-between text-white py-2 border-bottom border-secondary border-opacity-25">
          <span>مقارنة أسعار بين آلاف المحلات</span>
          <span class="feature-check">✓</span>
        </div>
        <div class="d-flex align-items-center justify-content-between text-white py-2">
          <span>حفظ محلاتك وسلعك المفضلة</span>
          <span class="feature-check">✓</span>
        </div>
      </div>
    </div>

    <!-- LEFT PANEL IN RTL (SIGNUP FORM PANEL) -->
    <div class="col-12 col-md-6 auth-panel-left bg-light p-4 p-lg-5">
      <!-- TABS -->
      <div class="d-flex p-1 bg-secondary bg-opacity-25 rounded-pill mb-4 mx-auto" style="max-width: 320px;">
        <a href="login.html" class="btn flex-fill rounded-pill fw-bold text-muted">تسجيل دخول</a>
        <a href="signup.html" class="btn flex-fill rounded-pill fw-bold active-tab-btn">إنشاء حساب</a>
      </div>

      <!-- SIGNUP FORM -->
      <form action="{{route('index')}}">
        <div class="mb-2">
          <label class="form-label fw-bold small mb-1">الاسم الكامل</label>
          <input type="text" class="form-control border-0 bg-white" value="محمد عزالدين">
        </div>

        <div class="mb-2">
          <label class="form-label fw-bold small mb-1">البريد الإلكتروني</label>
          <input type="email" class="form-control border-0 bg-white" value="mohameed@gmail.com">
        </div>

        <div class="mb-2">
          <label class="form-label fw-bold small mb-1">رقم الهاتف</label>
          <input type="tel" class="form-control border-0 bg-white" value="0590000000">
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold small mb-1">كلمة المرور</label>
          <input type="password" class="form-control border-0 bg-white" value="••••••••••••">
        </div>

        <button type="submit" class="btn-locate w-100 justify-content-center py-2.5 fs-6 mb-3">إنشاء حساب جديد</button>
      </form>

      <div class="position-relative text-center mb-3">
        <hr class="text-muted my-1">
        <span class="position-absolute top-50 start-50 translate-middle px-3 fw-bold bg-light text-muted small">أو</span>
      </div>

      <button class="btn btn-outline-secondary w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 bg-white">
        <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/></svg>
        المتابعة عبر جوجل
      </button>
    </div>

  </div>
</div>

<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/js/script.js')}}"></script>
</body>
</html>


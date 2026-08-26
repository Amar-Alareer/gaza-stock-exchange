<footer class="main-footer">

  <div class="container">

    {{-- Top Grid --}}
    <div class="footer-top-grid">

      {{-- Column 1: Branding & About --}}
      <div class="footer-col footer-brand-col">
        <div class="footer-logo-wrap">
          <img src="{{ asset('assets/imges/logo.png') }}" alt="وفر كاش" class="footer-logo-img">
          <span class="footer-logo-text">
            <span class="brand-green">وفر</span><span class="brand-white">كاش</span>
          </span>
        </div>
        <p class="footer-about">
          منصة ذكية لمتابعة أسعار السلع الأساسية في أسواق قطاع غزة، نساعدك على اتخاذ أفضل قرارات الشراء وتوفير المال لعائلتك.
        </p>
        <div class="footer-social">
          <a href="#" class="footer-social-btn" title="Facebook" aria-label="Facebook">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="#" class="footer-social-btn" title="WhatsApp" aria-label="WhatsApp">
            <i class="bi bi-whatsapp"></i>
          </a>
          <a href="#" class="footer-social-btn" title="Telegram" aria-label="Telegram">
            <i class="bi bi-telegram"></i>
          </a>
          <a href="#" class="footer-social-btn" title="Instagram" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
          </a>
        </div>
      </div>

      {{-- Column 2: Quick Navigation --}}
      <div class="footer-col">
        <h4 class="footer-col-title">
          <i class="bi bi-compass-fill me-1"></i> التنقل السريع
        </h4>
        <ul class="footer-nav-list">
          <li><a href="{{ url('/') }}"><i class="bi bi-house-fill"></i> الرئيسية</a></li>
          <li><a href="{{ route('prices') }}"><i class="bi bi-tags-fill"></i> تصفح الأسعار</a></li>
          <li><a href="{{ route('shops') }}"><i class="bi bi-shop"></i> دليل المحلات</a></li>
          <li><a href="{{ route('map') }}"><i class="bi bi-geo-alt-fill"></i> خريطة المحلات</a></li>
          <li>
            @auth
              <a href="{{ route('profile') }}"><i class="bi bi-person-circle"></i> حسابي</a>
            @else
              <a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> تسجيل الدخول</a>
            @endauth
          </li>
        </ul>
      </div>

      {{-- Column 3: Categories --}}
      <div class="footer-col">
        <h4 class="footer-col-title">
          <i class="bi bi-grid-fill me-1"></i> أبرز الأقسام
        </h4>
        <ul class="footer-nav-list">
          <li><a href="{{ route('prices') }}?category={{ urlencode('مواد غذائية') }}"><i class="bi bi-cart3"></i> مواد غذائية</a></li>
          <li><a href="{{ route('prices') }}?category={{ urlencode('خضراوات') }}"><i class="bi bi-basket"></i> خضراوات وفواكه</a></li>
          <li><a href="{{ route('prices') }}?category={{ urlencode('لحوم') }}"><i class="bi bi-egg-fried"></i> لحوم ودواجن</a></li>
          <li><a href="{{ route('prices') }}?category={{ urlencode('ألبان') }}"><i class="bi bi-cup-hot"></i> ألبان وأجبان</a></li>
          <li><a href="{{ route('prices') }}?category={{ urlencode('وقود') }}"><i class="bi bi-fuel-pump"></i> وقود ومحروقات</a></li>
        </ul>
      </div>

      {{-- Column 4: Contact & Info --}}
      <div class="footer-col">
        <h4 class="footer-col-title">
          <i class="bi bi-envelope-fill me-1"></i> تواصل معنا
        </h4>
        <ul class="footer-contact-list">
          <li>
            <span class="footer-contact-icon"><i class="bi bi-geo-alt-fill"></i></span>
            <span>قطاع غزة، فلسطين</span>
          </li>
          <li>
            <span class="footer-contact-icon"><i class="bi bi-envelope-fill"></i></span>
            <a href="mailto:info@wafarcash.ps">info@wafarcash.ps</a>
          </li>
          <li>
            <span class="footer-contact-icon"><i class="bi bi-whatsapp"></i></span>
            <a href="https://wa.me/970599000000" target="_blank" rel="noopener">970-599-000-000</a>
          </li>
        </ul>

        {{-- App Badge --}}
        <div class="footer-app-badge mt-3">
          <i class="bi bi-shield-fill-check text-success me-1"></i>
          <span>بيانات موثوقة وأسعار حقيقية</span>
        </div>
      </div>

    </div>

    {{-- Divider --}}
    <div class="footer-divider"></div>

    {{-- Bottom Bar --}}
    <div class="footer-bottom-bar">
      <div class="footer-bottom-right">
        <span class="footer-copyright">
          © {{ date('Y') }} <strong>وفر كاش</strong> — جميع الحقوق محفوظة
        </span>
        <span class="footer-made-with">
          صُنع بـ <i class="bi bi-heart-fill text-danger"></i> في فلسطين
        </span>
      </div>
      <div class="footer-bottom-left">
        <a href="#">سياسة الخصوصية</a>
        <span class="footer-dot">·</span>
        <a href="#">الشروط والأحكام</a>
        <span class="footer-dot">·</span>
        <a href="#">اتصل بنا</a>
      </div>
    </div>

  </div>
</footer>

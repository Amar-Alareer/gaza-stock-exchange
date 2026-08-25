// =========================================================
// وفر كاش - Shared behavior across pages
// =========================================================
document.addEventListener('DOMContentLoaded', function () {

  // =========================================================
  // Mobile Offcanvas Side Drawer & Blurred Backdrop
  // =========================================================
  var togglers = document.querySelectorAll('.navbar-toggler-custom');
  var mobilePanel = document.querySelector('.mobile-nav-panel');

  if (mobilePanel) {
    // Move mobilePanel directly to body so it is not trapped inside nav stacking context
    if (mobilePanel.parentElement !== document.body) {
      document.body.appendChild(mobilePanel);
    }

    // Create Backdrop element if not already present
    var backdrop = document.querySelector('.mobile-nav-backdrop');
    if (!backdrop) {
      backdrop = document.createElement('div');
      backdrop.className = 'mobile-nav-backdrop';
      document.body.appendChild(backdrop);
    }

    // Add Header with Close Button inside Drawer if not present
    if (!mobilePanel.querySelector('.mobile-nav-header')) {
      var header = document.createElement('div');
      header.className = 'mobile-nav-header';
      header.innerHTML = '<div class="d-flex align-items-center gap-2">' +
        '<img src="/assets/imges/logo.png" alt="وفر كاش" style="width:32px;height:32px;border-radius:50%;">' +
        '<span class="fw-bold text-white fs-6"><span style="color:#24df64;">وفر</span> كاش</span>' +
      '</div>' +
      '<button type="button" class="mobile-nav-close" aria-label="إغلاق"><i class="bi bi-x-lg"></i></button>';
      mobilePanel.insertBefore(header, mobilePanel.firstChild);

      var closeBtn = header.querySelector('.mobile-nav-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', closeMobileNav);
      }
    }

    function openMobileNav() {
      mobilePanel.classList.add('open', 'show');
      backdrop.classList.add('show');
      document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
      mobilePanel.classList.remove('open', 'show');
      backdrop.classList.remove('show');
      document.body.style.overflow = '';
    }

    togglers.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        if (mobilePanel.classList.contains('open') || mobilePanel.classList.contains('show')) {
          closeMobileNav();
        } else {
          openMobileNav();
        }
      });
    });

    backdrop.addEventListener('click', closeMobileNav);

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && (mobilePanel.classList.contains('open') || mobilePanel.classList.contains('show'))) {
        closeMobileNav();
      }
    });

    mobilePanel.querySelectorAll('.nav-link').forEach(function (link) {
      link.addEventListener('click', function () {
        setTimeout(closeMobileNav, 150);
      });
    });
  }

  // Hero dots (decorative carousel indicator)
  document.querySelectorAll('.hero-dots span').forEach(function (dot, i, arr) {
    dot.addEventListener('click', function () {
      arr.forEach(function (d) { d.classList.remove('active'); });
      dot.classList.add('active');
    });
  });

  // "المزيد" load-more demo behavior
  var moreBtn = document.querySelector('.btn-more');
  if (moreBtn) {
    moreBtn.addEventListener('click', function () {
      var original = this.textContent;
      this.textContent = 'جاري التحميل...';
      var self = this;
      setTimeout(function () { self.textContent = original; }, 800);
    });
  }

  // Compare page: "تحديث المقارنة" demo behavior
  var updateBtn = document.querySelector('.btn-update');
  if (updateBtn) {
    updateBtn.addEventListener('click', function () {
      var original = this.textContent;
      this.textContent = 'جاري التحديث...';
      var self = this;
      setTimeout(function () { self.textContent = original; }, 700);
    });
  }

  // =========================================================
  // Navbar Smart Live Search
  // =========================================================
  var searchForms = document.querySelectorAll('.search-pill');
  
  searchForms.forEach(function (form) {
    var input = form.querySelector('input[name="search"]');
    if (!input) return;

    // Wrap in relative container if not already
    var wrapper = form.parentElement;
    if (!wrapper.classList.contains('navbar-search-wrapper')) {
      wrapper = document.createElement('div');
      wrapper.className = 'navbar-search-wrapper w-100';
      form.parentNode.insertBefore(wrapper, form);
      wrapper.appendChild(form);
    }

    // Create dropdown container
    var dropdown = document.createElement('div');
    dropdown.className = 'navbar-live-dropdown';
    wrapper.appendChild(dropdown);

    var debounceTimer = null;

    input.addEventListener('input', function () {
      var query = this.value.trim();
      clearTimeout(debounceTimer);

      if (query.length < 2) {
        dropdown.innerHTML = '';
        dropdown.classList.remove('show');
        return;
      }

      debounceTimer = setTimeout(function () {
        fetch('/search/live?q=' + encodeURIComponent(query))
          .then(function (res) { return res.json(); })
          .then(function (data) {
            renderLiveResults(dropdown, data, query);
          })
          .catch(function () {
            dropdown.classList.remove('show');
          });
      }, 250);
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
      if (!wrapper.contains(e.target)) {
        dropdown.classList.remove('show');
      }
    });

    // Open again if input has value on focus
    input.addEventListener('focus', function () {
      if (this.value.trim().length >= 2 && dropdown.children.length > 0) {
        dropdown.classList.add('show');
      }
    });
  });

  function renderLiveResults(dropdown, data, query) {
    var hasItems = data.items && data.items.length > 0;
    var hasStores = data.stores && data.stores.length > 0;

    if (!hasItems && !hasStores) {
      dropdown.innerHTML = '<div class="live-search-empty"><i class="bi bi-search mb-1 d-block fs-5"></i> لا توجد نتائج مطابقة لـ "' + escapeHtml(query) + '"</div>';
      dropdown.classList.add('show');
      return;
    }

    var html = '';

    // Products Section
    if (hasItems) {
      html += '<div class="live-search-section-title"><i class="bi bi-box-seam text-success me-1"></i> السلع والمنتجات</div>';
      data.items.forEach(function (item) {
        html += '<a href="' + item.url + '" class="live-search-item">' +
          '<div class="live-search-item-info">' +
            '<div class="live-search-icon"><i class="bi bi-tag-fill"></i></div>' +
            '<div>' +
              '<div class="live-search-name">' + escapeHtml(item.name) + '</div>' +
              '<div class="live-search-sub">' + escapeHtml(item.category || 'عام') + '</div>' +
            '</div>' +
          '</div>' +
          (item.price ? '<div class="live-search-price">' + item.price + '</div>' : '') +
        '</a>';
      });
    }

    // Stores Section
    if (hasStores) {
      html += '<div class="live-search-section-title"><i class="bi bi-shop text-success me-1"></i> المحلات والمتاجر</div>';
      data.stores.forEach(function (store) {
        html += '<a href="' + store.url + '" class="live-search-item">' +
          '<div class="live-search-item-info">' +
            '<div class="live-search-icon" style="background:#e0f2fe;color:#0284c7;"><i class="bi bi-shop"></i></div>' +
            '<div>' +
              '<div class="live-search-name">' + escapeHtml(store.name) + '</div>' +
              '<div class="live-search-sub"><i class="bi bi-geo-alt-fill text-success"></i> ' + escapeHtml(store.region) + '</div>' +
            '</div>' +
          '</div>' +
          '<i class="bi bi-chevron-left text-muted" style="font-size:0.75rem;"></i>' +
        '</a>';
      });
    }

    // View All Link
    html += '<a href="' + data.all_url + '" class="live-search-footer">' +
      '<i class="bi bi-search me-1"></i> عرض كل النتائج في صفحة الأسعار (' + escapeHtml(query) + ')' +
    '</a>';

    dropdown.innerHTML = html;
    dropdown.classList.add('show');
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }
});

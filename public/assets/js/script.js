// =========================================================
// وفر كاش - Shared behavior across pages
// =========================================================
document.addEventListener('DOMContentLoaded', function () {

  // Mobile hamburger menu toggle
  var toggler = document.querySelector('.navbar-toggler-custom');
  var mobilePanel = document.querySelector('.mobile-nav-panel');
  if (toggler && mobilePanel) {
    toggler.addEventListener('click', function () {
      mobilePanel.classList.toggle('open');
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
});

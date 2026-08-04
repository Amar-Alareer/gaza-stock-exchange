<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
      <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('assets/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />        <span class="brand-text fw-light">AdminLTE 4</span>
      </a>
    </div>
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation">
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon bi bi-speedometer"></i>
              <p>Dashboard <i class="nav-arrow bi bi-chevron-right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/') }}" class="nav-link active">
                  <i class="nav-icon bi bi-circle"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

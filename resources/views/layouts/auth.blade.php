<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="adminHMD professional admin dashboard template">
  <title>Dashboard</title>

  <link rel="stylesheet" href="{{ asset('assets/auth/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{asset('assets/auth/vendors/bootstrap-icons/bootstrap-icons.css')}}">
  <link rel="stylesheet" href="{{asset('assets/auth/css/style.css')}}">
  <style>
    /* High-End Action Buttons UI Design System */
    .btn-action {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.35rem;
      padding: 0.35rem 0.75rem;
      font-size: 0.8125rem;
      font-weight: 600;
      border-radius: 8px;
      border: 1px solid transparent;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
      text-decoration: none !important;
      cursor: pointer;
      line-height: 1.3;
    }
    .btn-action:hover {
      transform: translateY(-1.5px);
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    .btn-action:active {
      transform: translateY(0);
    }

    /* Colors */
    .btn-action-primary {
      background-color: #eff6ff;
      color: #1d4ed8;
      border-color: #bfdbfe;
    }
    .btn-action-primary:hover, .btn-action-primary:focus {
      background-color: #2563eb;
      color: #ffffff !important;
      border-color: #2563eb;
    }

    .btn-action-secondary {
      background-color: #f1f5f9;
      color: #475569;
      border-color: #cbd5e1;
    }
    .btn-action-secondary:hover, .btn-action-secondary:focus {
      background-color: #475569;
      color: #ffffff !important;
      border-color: #475569;
    }

    .btn-action-danger {
      background-color: #fef2f2;
      color: #dc2626;
      border-color: #fecaca;
    }
    .btn-action-danger:hover, .btn-action-danger:focus {
      background-color: #dc2626;
      color: #ffffff !important;
      border-color: #dc2626;
    }

    .btn-action-success {
      background-color: #ecfdf5;
      color: #047857;
      border-color: #a7f3d0;
    }
    .btn-action-success:hover, .btn-action-success:focus {
      background-color: #10b981;
      color: #ffffff !important;
      border-color: #10b981;
    }

    .btn-action-info {
      background-color: #f0f9ff;
      color: #0369a1;
      border-color: #bae6fd;
    }
    .btn-action-info:hover, .btn-action-info:focus {
      background-color: #0284c7;
      color: #ffffff !important;
      border-color: #0284c7;
    }

    .btn-action-warning {
      background-color: #fffbeb;
      color: #b45309;
      border-color: #fde68a;
    }
    .btn-action-warning:hover, .btn-action-warning:focus {
      background-color: #d97706;
      color: #ffffff !important;
      border-color: #d97706;
    }

    /* Executive Index Page Header & List Box Header System */
    .index-header-card {
      background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
      color: #ffffff;
      border-radius: 16px;
      padding: 1.35rem 1.75rem;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
      margin-bottom: 1.5rem;
    }
    .index-header-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.12);
      border: 1px solid rgba(255, 255, 255, 0.18);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.35rem;
      color: #ffffff;
      backdrop-filter: blur(8px);
      flex-shrink: 0;
    }
    .index-action-btn {
      background: rgba(255, 255, 255, 0.12);
      color: #ffffff !important;
      border: 1px solid rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      transition: all 0.2s ease;
      border-radius: 8px;
      font-weight: 500;
      padding: 0.45rem 1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      text-decoration: none !important;
      font-size: 0.875rem;
    }
    .index-action-btn:hover {
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff !important;
      transform: translateY(-2px);
    }
    .index-action-btn-primary {
      background: #2563eb;
      border-color: #3b82f6;
      color: #ffffff !important;
    }
    .index-action-btn-primary:hover {
      background: #1d4ed8;
      border-color: #2563eb;
    }

    /* Panel List Box Top Header */
    .panel-custom {
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      overflow: hidden;
      margin-bottom: 1.5rem;
    }
    .panel-header-custom {
      background: #ffffff;
      border-bottom: 1px solid #f1f5f9;
      padding: 1.15rem 1.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
    }
    .panel-header-title {
      font-size: 1.1rem;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: 0.6rem;
    }

    /* ==========================================================================
       Dark Mode Design System Overrides
       ========================================================================== */
    html[data-theme="dark"] {
      --admin-bg: #0b1120;
      --admin-surface: #172033;
      --admin-surface-soft: #111827;
      --admin-border: #2b384e;
      --admin-text: #e2e8f0;
      --admin-muted: #94a3b8;
    }

    html[data-theme="dark"] body {
      background-color: #0b1120 !important;
      color: #e2e8f0 !important;
    }

    /* Executive Index Card Header in Dark Mode */
    html[data-theme="dark"] .index-header-card {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;
      border: 1px solid #334155 !important;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4) !important;
    }

    /* Panel & List Card Containers */
    html[data-theme="dark"] .panel-custom,
    html[data-theme="dark"] .panel {
      background: #172033 !important;
      border-color: #2b384e !important;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3) !important;
    }
    html[data-theme="dark"] .panel-header-custom,
    html[data-theme="dark"] .panel-header {
      background: #172033 !important;
      border-bottom-color: #2b384e !important;
    }
    html[data-theme="dark"] .panel-header-title {
      color: #f8fafc !important;
    }

    /* Tables in Dark Mode */
    html[data-theme="dark"] .table {
      --bs-table-color: #e2e8f0;
      --bs-table-bg: transparent;
      --bs-table-border-color: #2b384e;
      --bs-table-hover-bg: #243044;
      --bs-table-hover-color: #ffffff;
      color: #e2e8f0 !important;
      border-color: #2b384e !important;
    }
    html[data-theme="dark"] .table thead th {
      background-color: #0f172a !important;
      color: #94a3b8 !important;
      border-bottom: 2px solid #2b384e !important;
    }
    html[data-theme="dark"] .table tbody td,
    html[data-theme="dark"] .table tbody th {
      border-color: #2b384e !important;
      color: #cbd5e1 !important;
    }

    /* Smooth Table Row Hover in Dark Mode - Eliminating harsh black hover */
    html[data-theme="dark"] .table tbody tr:hover,
    html[data-theme="dark"] .table tbody tr:hover > td,
    html[data-theme="dark"] .table tbody tr:hover > th,
    html[data-theme="dark"] .table-hover tbody tr:hover,
    html[data-theme="dark"] .table-hover tbody tr:hover > td,
    html[data-theme="dark"] .table-hover tbody tr:hover > th {
      background-color: #243044 !important;
      color: #ffffff !important;
    }

    /* Text elements inside hovered row */
    html[data-theme="dark"] .table tbody tr:hover .text-dark,
    html[data-theme="dark"] .table tbody tr:hover .text-secondary,
    html[data-theme="dark"] .table tbody tr:hover .text-muted,
    html[data-theme="dark"] .table tbody tr:hover a:not(.btn) {
      color: #ffffff !important;
    }

    /* General Text Overrides in Dark Mode */
    html[data-theme="dark"] .text-dark {
      color: #f8fafc !important;
    }
    html[data-theme="dark"] .text-secondary {
      color: #94a3b8 !important;
    }
    html[data-theme="dark"] .text-muted {
      color: #94a3b8 !important;
    }
    html[data-theme="dark"] .bg-white {
      background-color: #172033 !important;
      color: #f8fafc !important;
    }
    html[data-theme="dark"] .bg-light {
      background-color: #111827 !important;
      color: #f8fafc !important;
    }

    /* High-End Action Buttons in Dark Mode */
    html[data-theme="dark"] .btn-action-primary {
      background-color: rgba(37, 99, 235, 0.2) !important;
      color: #93c5fd !important;
      border-color: rgba(59, 130, 246, 0.4) !important;
    }
    html[data-theme="dark"] .btn-action-primary:hover {
      background-color: #2563eb !important;
      color: #ffffff !important;
      border-color: #2563eb !important;
    }

    html[data-theme="dark"] .btn-action-secondary {
      background-color: rgba(100, 116, 139, 0.2) !important;
      color: #cbd5e1 !important;
      border-color: rgba(148, 163, 184, 0.3) !important;
    }
    html[data-theme="dark"] .btn-action-secondary:hover {
      background-color: #475569 !important;
      color: #ffffff !important;
      border-color: #475569 !important;
    }

    html[data-theme="dark"] .btn-action-danger {
      background-color: rgba(220, 38, 38, 0.2) !important;
      color: #fca5a5 !important;
      border-color: rgba(239, 68, 68, 0.4) !important;
    }
    html[data-theme="dark"] .btn-action-danger:hover {
      background-color: #dc2626 !important;
      color: #ffffff !important;
      border-color: #dc2626 !important;
    }

    html[data-theme="dark"] .btn-action-success {
      background-color: rgba(16, 185, 129, 0.2) !important;
      color: #6ee7b7 !important;
      border-color: rgba(16, 185, 129, 0.4) !important;
    }
    html[data-theme="dark"] .btn-action-success:hover {
      background-color: #10b981 !important;
      color: #ffffff !important;
      border-color: #10b981 !important;
    }

    html[data-theme="dark"] .btn-action-info {
      background-color: rgba(14, 165, 233, 0.2) !important;
      color: #7dd3fc !important;
      border-color: rgba(14, 165, 233, 0.4) !important;
    }
    html[data-theme="dark"] .btn-action-info:hover {
      background-color: #0284c7 !important;
      color: #ffffff !important;
      border-color: #0284c7 !important;
    }

    html[data-theme="dark"] .btn-action-warning {
      background-color: rgba(245, 158, 11, 0.2) !important;
      color: #fde68a !important;
      border-color: rgba(245, 158, 11, 0.4) !important;
    }
    html[data-theme="dark"] .btn-action-warning:hover {
      background-color: #d97706 !important;
      color: #ffffff !important;
      border-color: #d97706 !important;
    }

    /* Stat Cards & Metric Cards in Dark Mode */
    html[data-theme="dark"] .stat-card-modern,
    html[data-theme="dark"] .metric-card {
      background: #172033 !important;
      border-color: #2b384e !important;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25) !important;
    }
    html[data-theme="dark"] .stat-card-modern:hover {
      box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.4) !important;
    }
    html[data-theme="dark"] .icon-primary { background: rgba(37, 99, 235, 0.2) !important; color: #93c5fd !important; }
    html[data-theme="dark"] .icon-success { background: rgba(16, 185, 129, 0.2) !important; color: #6ee7b7 !important; }
    html[data-theme="dark"] .icon-info { background: rgba(14, 165, 233, 0.2) !important; color: #7dd3fc !important; }

    /* Subtle Badges in Dark Mode */
    html[data-theme="dark"] .bg-primary-subtle {
      background-color: rgba(37, 99, 235, 0.2) !important;
      color: #93c5fd !important;
    }
    html[data-theme="dark"] .bg-success-subtle {
      background-color: rgba(16, 185, 129, 0.2) !important;
      color: #6ee7b7 !important;
    }
    html[data-theme="dark"] .bg-danger-subtle {
      background-color: rgba(220, 38, 38, 0.2) !important;
      color: #fca5a5 !important;
    }
    html[data-theme="dark"] .bg-warning-subtle {
      background-color: rgba(245, 158, 11, 0.2) !important;
      color: #fde68a !important;
    }
    html[data-theme="dark"] .bg-info-subtle {
      background-color: rgba(14, 165, 233, 0.2) !important;
      color: #7dd3fc !important;
    }
    html[data-theme="dark"] .bg-secondary-subtle {
      background-color: rgba(100, 116, 139, 0.2) !important;
      color: #cbd5e1 !important;
    }
  </style>
</head>

<body>
  <div class="admin-shell">
    <div class="sidebar-backdrop" data-sidebar-close></div>

    <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
      <div class="sidebar-header">
        <a class="brand-mark" aria-label="adminHMD dashboard">
          <img src="{{ asset('images/Seasons_Logo.png') }}" width="60">
          <span class="brand-copy">
            <span class="brand-title">Season Fruits</span>
            {{-- <span class="brand-subtitle">Admin Template</span> --}}
          </span>
        </a>
      </div>

      <nav class="sidebar-nav">
        <a class="nav-link active" href="{{ route('dashboard') }}" aria-current="page">
          <span class="nav-icon"><i class="bi bi-speedometer2" 4aria-hidden="true"></i></span>
          <span class="nav-text">Dashboard</span>
        </a>
          <a class="nav-link {{ request()->is('customers*') ? 'active' : '' }}"
            href="{{ route('customers.index') }}">
              <span class="nav-icon">
                  <i class="bi bi-people"></i>
              </span>
              <span class="nav-text">Customers</span>
          </a>
          <a class="nav-link {{ request()->is('products*') ? 'active' : '' }}"
            href="{{ route('products.index') }}">
              <span class="nav-icon">
                  <i class="bi bi-box"></i>
              </span>
              <span class="nav-text">Products</span>
          </a>
          <a class="nav-link {{ request()->is('sales*') ? 'active' : '' }}"
            href="{{ route('sales.index') }}">
              <span class="nav-icon">
                  <i class="bi bi-box"></i>
              </span>
              <span class="nav-text">Sale</span>
          </a>
          <a class="nav-link {{ request()->routeIs('customer-ledger.*') ? 'active' : '' }}"
            href="{{ route('customer-ledger.index') }}">
              <span class="nav-icon">
                  <i class="bi bi-journal-text"></i>
              </span>
              <span class="nav-text">Customer Ledger</span>
          </a>
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('tray-returns.summary') ? 'active' : '' }}"
              href="{{ route('tray-returns.summary') }}">
                <span class="nav-icon">
                    <i class="bi bi-grid"></i>
                </span>
                <span class="nav-text">Tray Ledger</span>
            </a>
          </li>
          @php
              $reportsActive = request()->is('reports*');
          @endphp
          <li class="nav-item">

              <a class="nav-link d-flex justify-content-between align-items-center
                  {{ $reportsActive ? 'active' : '' }}"
                  data-bs-toggle="collapse"
                  href="#reportsMenu"
                  role="button"
                  aria-expanded="{{ $reportsActive ? 'true' : 'false' }}">

                  <span>
                      <span class="nav-icon">
                          <i class="bi bi-bar-chart"></i>
                      </span>
                      <span class="nav-text">Reports</span>
                  </span>

                  <i class="bi bi-chevron-down"></i>
              </a>

              <div class="collapse {{ $reportsActive ? 'show' : '' }}" id="reportsMenu">

                  <ul class="nav flex-column ms-3 mt-2">

                      <li class="nav-item">
                          <a class="nav-link {{ request()->routeIs('reports.product-wise') ? 'active' : '' }}"
                            href="{{ route('reports.product-wise') }}">
                              <i class="bi bi-box-seam me-2"></i>
                              Product Wise Report
                          </a>
                      </li>

                  </ul>

              </div>
          </li>

        {{-- <a class="nav-link" href="add-user.html">
          <span class="nav-icon"><i class="bi bi-person-plus" aria-hidden="true"></i></span>
          <span class="nav-text">Add User</span>
        </a>
        <a class="nav-link" href="profile.html">
          <span class="nav-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
          <span class="nav-text">Profile</span>
        </a>
        <a class="nav-link" href="charts.html">
          <span class="nav-icon"><i class="bi bi-bar-chart-line" aria-hidden="true"></i></span>
          <span class="nav-text">Charts</span>
        </a>
        <a class="nav-link" href="tables.html">
          <span class="nav-icon"><i class="bi bi-table" aria-hidden="true"></i></span>
          <span class="nav-text">Tables</span>
        </a>
        <a class="nav-link" href="forms.html">
          <span class="nav-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
          <span class="nav-text">Forms</span>
        </a>
        <a class="nav-link" href="components.html">
          <span class="nav-icon"><i class="bi bi-grid-3x3-gap" aria-hidden="true"></i></span>
          <span class="nav-text">Components</span>
        </a>
        <a class="nav-link" href="alerts.html">
          <span class="nav-icon"><i class="bi bi-exclamation-triangle" aria-hidden="true"></i></span>
          <span class="nav-text">Alerts</span>
        </a>
        <a class="nav-link" href="modals.html">
          <span class="nav-icon"><i class="bi bi-window-stack" aria-hidden="true"></i></span>
          <span class="nav-text">Modals</span>
        </a>
        <a class="nav-link" href="settings.html">
          <span class="nav-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
          <span class="nav-text">Settings</span>
        </a>
        <a class="nav-link" href="blank.html">
          <span class="nav-icon"><i class="bi bi-file-earmark" aria-hidden="true"></i></span>
          <span class="nav-text">Blank Page</span>
        </a> --}}
      </nav>

     
    </aside>

    <div class="admin-main">
      <nav class="navbar admin-navbar navbar-expand bg-white">
        <div class="container-fluid px-3 px-lg-4">
          <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
          </button>

          {{-- <form class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search" placeholder="Search users, orders, reports" aria-label="Search">
          </form> --}}

          <div class="navbar-actions ms-auto">
            <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
              <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
            </button>
            {{-- <div class="dropdown">
              <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <span class="notification-dot"></span>
                <i class="bi bi-bell" aria-hidden="true"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end notification-menu">
                <div class="dropdown-header fw-bold text-body">Notifications</div>
                <a class="dropdown-item" href="users.html">
                  <span class="notification-title">New user registered</span>
                  <span class="notification-time">4 minutes ago</span>
                </a>
                <a class="dropdown-item" href="charts.html">
                  <span class="notification-title">Revenue target reached</span>
                  <span class="notification-time">32 minutes ago</span>
                </a>
                <a class="dropdown-item" href="settings.html">
                  <span class="notification-title">Security review completed</span>
                  <span class="notification-time">1 hour ago</span>
                </a>
              </div>
            </div> --}}


            {{-- <div class="dropdown">
              <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class=" d-none d-sm-inline">Vanaja</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                <li><a class="dropdown-item" href="settings.html">Account settings</a></li>
                <li><hr class="dropdown-divider"></li>
               <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign out
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
              </ul>
            </div> --}}
          </div>
        </div>
      </nav>

    @yield('content')

      <footer class="admin-footer">
        <div class="container-fluid px-3 px-lg-4">
          {{-- <span>Copyright 2026 adminHMD. <br> Developed by <a target="_blank" class="fw-bold text-success" href="https://github.com/HasanMahmudDev">Md. Hasan Mahmud</a> • Distributed by <a target="_blank" class="fw-bold text-success" href="https://themewagon.com">ThemeWagon</a> </span> --}}
          {{-- <span>Professional dashboard template.</span> --}}
        </div>
      </footer>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="{{ asset('assets/auth/js/bootstrap.bundle.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


<script src="{{ asset('assets/auth/js/main.js') }}"></script>
<script src="{{ asset('assets/auth/js/myscript.js') }}"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
</body>
</html>

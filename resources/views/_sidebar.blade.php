<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="/" class="logo d-flex align-items-center">
                <span
                    style="font-family: Arial, sans-serif; font-size:11px; line-height:1.2;"
                    class="fw-bold fst-italic text-uppercase text-white">
                    <a href="/" class="logo d-flex align-items-center">
                        <span class="brand-text">
                            SI-AKSI
                        </span>
                    </a>
                </span>

            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    @if(Auth::user()->id_role == 2)
                    <a href="{{ route('dashboard.ketua') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard Ketua Tim</p>
                    </a>
                    @elseif(Auth::user()->id_role == 1)
                    <a href="{{ route('dashboard.operator') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard Operator</p>
                    </a>
                    @else
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard Admin</p>
                    </a>
                    @endif
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('form.index') ? 'active' : '' }}">
                    <a href="{{ route('form.index') }}">
                        <i class="fas fa-edit"></i>
                        <p>Pembuatan Form</p>
                    </a>
                </li>

                <li class="nav-item {{ request()->routeIs('form.list') || request()->routeIs('form.show') ? 'active' : '' }}">
                    <a href="{{ route('form.list') }}">
                        <i class="fas fa-file"></i>
                        <p>Form</p>
                    </a>
                </li>
                <style>
                    .nav-item a .badge-success {
                        margin-right: 0;
                    }

                    .nav-item a .badge-danger {
                        margin-left: 2px;
                    }
                </style>
            </ul>
        </div>
    </div>
</div>

<style>
    .brand-text {
        font-family: "Poppins", Arial, sans-serif;
        font-size: 35px;
        font-weight: 700;
        font-style: italic;
        text-transform: uppercase;
        color: #00bcd4;
        /* biru tosca */
        letter-spacing: 1.2px;
        line-height: 1.2;
        /* rapatin antarbaris */
    }

    .sidebar[data-background-color="dark"] {
        background: #1b2b2b !important;
    }

    .logo-header[data-background-color="dark"] {
        background: #254545 !important;
    }
</style>
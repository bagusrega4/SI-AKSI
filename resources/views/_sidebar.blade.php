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
                    @if(in_array(Auth::user()->id_role, [1, 2]))
                    <a href="{{ route('welcome.page') }}">
                        <i class="fas fa-home"></i>
                        <p>Welcome Page</p>
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
                @if(in_array(Auth::user()->id_role, [3]))
                <li class="nav-item {{ request()->routeIs('form.index') ? 'active' : '' }}">
                    <a href="{{ route('form.index') }}">
                        <i class="fas fa-edit"></i>
                        <p>Pembuatan Form</p>
                    </a>
                </li>
                @endif
                <li class="nav-item {{ request()->routeIs('form.list') || request()->routeIs('form.show') ? 'active' : '' }}">
                    <a href="{{ route('form.list') }}">
                        <i class="fas fa-file"></i>
                        <p>Form</p>
                    </a>
                </li>
                @if(in_array(Auth::user()->id_role, [3]))
                <li class="nav-item {{ request()->routeIs('jawaban.*') ? 'active' : '' }}">
                    <a href="{{ route('jawaban.index') }}">
                        <i class="fas fa-list-alt"></i>
                        <p>Jawaban User</p>
                    </a>
                </li>
                @endif
                @if(in_array(Auth::user()->id_role, [3]))
                <li class="nav-item {{ request()->routeIs('manage.user.*') || request()->routeIs('manage.user.create') || request()->routeIs('manage.user.edit') ? 'active' : '' }}">
                    <a href="{{ route('manage.user.index') }}">
                        <i class="fas fa-users-cog"></i>
                        <p>Manage User</p>
                    </a>
                </li>
                @endif
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
        color: #ffffff !important;
        /* warna putih */
        letter-spacing: 1.2px;
        line-height: 1.2;
    }

    /* Pastikan semua teks & ikon sidebar berwarna putih */
    .sidebar .nav-item a p,
    .sidebar .nav-item a i,
    .sidebar .text-section,
    .sidebar .nav-item a {
        color: #ffffff !important;
    }

    /* Tambahkan sedikit efek agar tulisan lebih jelas */
    .sidebar .nav-item a p,
    .sidebar .nav-item a i {
        text-shadow: 0 0 4px rgba(0, 0, 0, 0.4);
    }

    /* Sidebar background dengan gradasi merah gelap */
    .sidebar[data-background-color="dark"] {
        background: linear-gradient(135deg, #7D0A0A, #BF3131) !important;
        color: #fff;
    }

    /* Logo/header background */
    .logo-header[data-background-color="dark"] {
        background: rgba(0, 0, 0, 0.25) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Nav item default */
    .sidebar .nav-item a {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        color: #ffffff !important;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    /* Hover */
    .sidebar .nav-item a:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #ffffff !important;
        transform: translateX(4px);
    }

    /* Active */
    .sidebar .nav-item.active a {
        background: linear-gradient(90deg, #BF3131, #7D0A0A);
        color: #ffffff !important;
        font-weight: bold;
    }

    /* Icons */
    .sidebar .nav-item i {
        margin-right: 10px;
        font-size: 16px;
        color: #ffffff !important;
    }

    /* Section title */
    .sidebar .text-section {
        font-size: 12px;
        letter-spacing: 1px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 10px;
        color: #ffffff !important;
    }

    /* Badge */
    .sidebar .badge {
        font-size: 10px;
        padding: 3px 6px;
        background: #ffffff;
        color: #000;
    }
</style>
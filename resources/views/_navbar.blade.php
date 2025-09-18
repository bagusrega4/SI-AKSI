<div class="main-header">
    <div class="main-header-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
                <img src="{{ asset('assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
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
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                        aria-expanded="false">
                        <div class="avatar-sm">
                            @if (Auth::user())
                            <img src="{{ !Auth::user()->photo ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y' : asset('/storage/' . Auth::user()->photo) }}"
                                alt="..." class="avatar-img rounded-circle" />
                            @endif
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            @if (Auth::user())
                            <span class="fw-bold">{{ Auth::user()->username }}</span>
                            @endif
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg">
                                        @if (Auth::user())
                                        <img src="{{ !Auth::user()->photo ? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y' : asset('/storage/' . Auth::user()->photo) }}"
                                            alt="..." class="avatar-img rounded-circle" />
                                        @endif
                                    </div>
                                    <div class="u-text">
                                        @if (Auth::user())
                                        <h4 class="text-capitalize">{{ Auth::user()->username }}</h4>
                                        <p class="text-muted">{{ Auth::user()->email }}</p>
                                        <p class="text-muted text-capitalize">as {{ Auth::user()->role->role }}</p>
                                        @endif
                                        <a href="{{ route('profile.edit') }}" class="btn btn-xs btn-primary btn-sm">
                                            {{ __('View Profile') }}
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item logout-btn cursor-pointer" :href="route('logout')"
                                        onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                        {{ __('Log Out') }}
                                    </button>
                                </form>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>

<style>
    .logout-btn:hover {
        background-color: #dc3545 !important;
        color: #fff !important;
    }

    .clickable:hover {
        background-color: #f8f9fa !important;
        transform: translateX(3px);
    }
</style>

<script>
    $(document).on('click', '.dropdown-menu .clickable', function() {
        const formId = $(this).data('id');
        if (formId) {
            $.ajax({
                url: `/notifications/${formId}/mark-as-read`,
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        // Update angka badge
                        if (response.newNotifCount > 0) {
                            $('#notifCount').text(response.newNotifCount);
                        } else {
                            $('#notifCount').remove(); // kalau sudah 0, hapus badge
                        }

                        // Hapus notifikasi dari dropdown
                        $(`a[data-id="${formId}"]`).remove();
                    }
                },
                error: function(error) {
                    console.error('Error updating status:', error);
                }
            });
        }
    });
</script>
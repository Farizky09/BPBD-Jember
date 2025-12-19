<div class="nk-header is-light nk-header-fixed is-light">
    <div class="container-xl wide-xl">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ms-n1 me-3">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em
                        class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none flex items-center">
                <dotlottie-player src="https://lottie.host/4b99d19e-2342-4e8f-a06d-7a30b666a96b/OSJesewnTf.lottie"
                    background="transparent" speed="1" style="width: 50px; height: 50px" loop
                    autoplay></dotlottie-player>
                <h1 class="ml-2 font-bold text-lg">BumiKita</h1>
            </div>
            
            <div class="nk-header-menu is-light mx-4">
                <div class="nk-header-menu-inner">
                    <!-- Menu -->
                    <div class="nk-menu nk-menu-main">
                        <di class="nk-menu-item has-sub">
                            <a href="{{ route('dashboard') }}" class="">
                                <div class="nk-block-head-content">
                                    <h6 class="nk-block-title page-title text-center">Selamat Datang
                                        {{ Auth::user()->name }}</h6>
                                    <div class="nk-block-des text-soft mt-1">
                                        Di Website resmi <strong>BumiKita</strong>
                                    </div>
                                </div>
                            </a>
                        </di><!-- .nk-menu-item -->
                    </div>
                    <!-- Menu -->
                </div>
            </div>
            
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-card">
                                <div class="user-avatar">
                                    <img src="{{ Auth::user()->image_avatar ? asset('storage/' . Auth::user()->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                                        alt="User Avatar" class="w-10 h-10 rounded-full object-cover">
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-end">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <img src="{{ Auth::user()->image_avatar ? asset('storage/' . Auth::user()->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                                            alt="User Avatar" class="w-10 h-10 rounded-full object-cover">
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ Auth::user()->name }}</span>
                                        <span class="sub-text">{{ Auth::user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                            @canany(['read_profile', 'update_profile'])
                            @endcanany
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    @can('read_profile')
                                        <li><a href="{{ route('profile.index') }}"><em
                                                    class="icon ni ni-user-alt"></em><span>Lihat Profile</span></a></li>
                                    @endcan
                                    @can('update_profile')
                                        <li><a href="{{ route('profile.edit') }}"><em
                                                    class="icon ni ni-edit-alt"></em><span>Edit Profile</span></a></li>
                                    @endcan
                                    <li><a href="{{ route('page.home') }}"><em
                                                class="icon ni ni-home"></em><span>Halaman Awal</span></a></li>

                                    <li> <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <em class="icon ni ni-signout"></em>
                                            <span>Keluar</span>
                                        </a></li>
                                </ul>
                            </div>

                        </div>
                    </li>
                </ul>
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
<form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
    @csrf
</form>
{{-- </body>

</html> --}}

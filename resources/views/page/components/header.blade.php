<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
        <a href="/" class="flex items-center space-x-2">
            <img src="{{ asset('assets/img/icons/bpbd.png') }}" alt="Logo" class="w-10 h-10 object-contain" />
            <h1 class="mt-2 text-xl font-bold sitename">BPBD-Jember</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul id="menu-list">
                {{-- <li><a href="#hero" class="active">Home</a></li> --}}
                <li><a href="#Infografis">Infografis</a></li>
                <li><a href="{{ route('page.mountainstatus') }}">Status Gunung</a></li>
                <li><a href="{{ route('page.gempastatus') }}">Monitoring Gempa</a></li>
                <li><a href="#map">Maps Bencana</a></li>
                <li><a href="#berita">Berita</a></li>
                <li><a href="#edukasi">Edukasi Bencana</a></li>

                @auth

                    <li class="relative hidden lg:block" x-data="{ open: false }">
                        <a href="#" class="flex items-center cursor-pointer" @click.prevent="open = !open">
                            Akun Saya
                            <i class="bi bi-chevron-down ml-1 text-xs transition-transform duration-200"
                                :class="{ 'rotate-180': open }"></i>
                        </a>

                        <ul x-show="open" x-transition
                            class="absolute right-0 flex flex-col space-y-2 rounded-md py-2 px-2 w-48 z-10 mt-2
bg-white/20 backdrop-blur-md shadow-lg border border-white/20 text-white
">
                            @if (auth()->user()->hasRole('user'))
                                <li>
                                    <a href="{{ route('reports.index') }}" class="block px-4 py-2 rounded-md">
                                        Dashboard Laporan
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                                <li>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md">
                                        Dashboard Admin
                                    </a>
                                </li>
                            @endif

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-600">
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @if (auth()->user()->hasRole('user'))
                        <li class="lg:hidden">
                            <a href="{{ route('reports.index') }}">
                                Dashboard Laporan
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                        <li class="lg:hidden">
                            <a href="{{ route('dashboard') }}">
                                Dashboard Admin
                            </a>
                        </li>
                    @endif

                    <li class="lg:hidden">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-red-600">
                                Keluar
                            </button>
                        </form>
                    </li>
                @endauth

                @guest
                    <li><a href="./login">Login</a></li>
                @endguest
            </ul>
            <i class="mobile-nav-toggle bi bi-list  text-2xl cursor-pointer lg:hidden"></i>
        </nav>
    </div>
</header>

<script>
    const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const path = window.location.pathname;

        const menuList = document.getElementById('menu-list');
        if (!menuList) return;

        if (path === '/lapor') {
            menuList.innerHTML = '';
            const laporItem = document.createElement('li');
            laporItem.innerHTML = '<a href="" >Lapor Bencana</a>';
            menuList.appendChild(laporItem);

            const dashboardItem = document.createElement('li');
            const homeItem = document.createElement('li');
            homeItem.innerHTML = `<a href="{{ route('page.home') }}" >Kembali ke Home</a>`;
            if (isLoggedIn == true || isLoggedIn == 'true') {
                dashboardItem.innerHTML = '<a href="{{ route('reports.index') }}" >Dashboard</a>';
            } else {
                dashboardItem.innerHTML = '<a href="{{ route('login') }}" >Login</a>';
            }

            menuList.appendChild(homeItem);
            menuList.appendChild(dashboardItem);
        }

        if (path === '/detaildisaster') {
            menuList.innerHTML = '';
            const detailItem = document.createElement('li');
            detailItem.innerHTML =
                '<a href="" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Detail Disaster</a>';
            menuList.appendChild(detailItem);

            const dashboardItem = document.createElement('li');
            const homeItem = document.createElement('li');
            homeItem.innerHTML =
                `<a href="{{ route('page.home') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Kembali ke Home</a>`;
            if (isLoggedIn == true || isLoggedIn == 'true') {
                dashboardItem.innerHTML =
                    '<a href="{{ route('reports.index') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Dashboard</a>';
            } else {
                dashboardItem.innerHTML =
                    '<a href="{{ route('login') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Login</a>';
            }

            menuList.appendChild(homeItem);
            menuList.appendChild(dashboardItem);
        }

        if (path === '/edukasi-bencana') {
            menuList.innerHTML = '';
            const konsultasiItem = document.createElement('li');
            konsultasiItem.innerHTML =
                '<a href="" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Edukasi Bencana</a>';
            menuList.appendChild(konsultasiItem);
            const dashboardItem = document.createElement('li');
            const homeItem = document.createElement('li');
            homeItem.innerHTML =
                `<a href="{{ route('page.home') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Kembali ke Home</a>`;
            if (isLoggedIn == true || isLoggedIn == 'true') {
                dashboardItem.innerHTML =
                    '<a href="{{ route('reports.index') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Dashboard</a>';
            } else {
                dashboardItem.innerHTML =
                    '<a href="{{ route('login') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Login</a>';
            }

            menuList.appendChild(homeItem);
            menuList.appendChild(dashboardItem);
        }

        if (path === '/mountain-status') {
            menuList.innerHTML = '';
            const konsultasiItem = document.createElement('li');
            konsultasiItem.innerHTML =
                '<a href="" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Status Gunung Raung</a>';
            menuList.appendChild(konsultasiItem);
            const dashboardItem = document.createElement('li');
            const homeItem = document.createElement('li');
            homeItem.innerHTML =
                `<a href="{{ route('page.home') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Kembali ke Home</a>`;
            if (isLoggedIn == true || isLoggedIn == 'true') {
                dashboardItem.innerHTML =
                    '<a href="{{ route('reports.index') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Dashboard</a>';
            } else {
                dashboardItem.innerHTML =
                    '<a href="{{ route('login') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Login</a>';
            }

            menuList.appendChild(homeItem);
            menuList.appendChild(dashboardItem);
        }

        if (path === '/gempa-status') {
            menuList.innerHTML = '';
            const konsultasiItem = document.createElement('li');
            konsultasiItem.innerHTML =
                '<a href="" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Monitoring Gempa</a>';
            menuList.appendChild(konsultasiItem);
            const dashboardItem = document.createElement('li');
            const homeItem = document.createElement('li');
            homeItem.innerHTML =
                `<a href="{{ route('page.home') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Kembali ke Home</a>`;
            if (isLoggedIn == true || isLoggedIn == 'true') {
                dashboardItem.innerHTML =
                    '<a href="{{ route('reports.index') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Dashboard</a>';
            } else {
                dashboardItem.innerHTML =
                    '<a href="{{ route('login') }}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Login</a>';
            }

            menuList.appendChild(homeItem);
            menuList.appendChild(dashboardItem);
        }

        if (path.startsWith('/berita/')) {
            menuList.innerHTML = '';

            const slug = path.split('/').pop();
            const beritaLink = window.routes.beritaDetail.replace('__slug__', slug);

            const berita = document.createElement('li');
            berita.innerHTML =
                `<a href="${beritaLink}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Berita Terkini</a>`;
            menuList.appendChild(berita);

            const homeItem = document.createElement('li');
            homeItem.innerHTML =
                `<a href="${window.routes.home}" class="text-white hover:text-blue-200 transition-colors duration-200 py-2 px-3 rounded-md">Kembali ke Home</a>`;
            menuList.appendChild(homeItem);
        }
    });
</script>

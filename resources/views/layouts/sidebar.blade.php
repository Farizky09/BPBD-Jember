<div class="nk-sidebar is-light nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand flex items-center mt-4">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('assets/img/icons/bpbd.png') }}" alt="Logo" class="w-10 h-10 object-contain" />
                <h1 class="text-xl font-bold sitename">BPBD-Jember</h1>
            </a>
        </div>

        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em>
            </a>
        </div>
    </div>
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    @if (auth()->user()->hasRole('super_admin'))
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle position-relative">
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-presentation"></em>
                                </span>
                                <span class="nk-menu-text">
                                    Dashboard
                                    <span id="pending-count-wrapper"
                                        class="badge bg-danger ms-2 p-1 d-inline-flex align-items-center"
                                        style="font-size: 0.85em; vertical-align: middle;">
                                        <i class="fas fa-bell me-1"></i>
                                        <span id="pending-count">{{ $initialPending ?? 0 }}</span>
                                    </span>

                                </span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="{{ route('dashboard') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Dashboard Utama</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('dashboard.infografis') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Infografis Dashboard</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('monitoring.index') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Infografis Dashboard</span></a>
                                </li>

                            </ul><!-- .nk-menu-sub -->
                        </li>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle position-relative">
                                <span class="nk-menu-icon">
                                    <em class="icon ni ni-presentation"></em>
                                </span>
                                <span class="nk-menu-text">
                                    Dashboard
                                    <span id="pending-count-wrapper"
                                        class="badge bg-danger ms-2 p-1 d-inline-flex align-items-center"
                                        style="font-size: 0.85em; vertical-align: middle;">
                                        <i class="fas fa-bell me-1"></i>
                                        <span id="pending-count">{{ $initialPending ?? 0 }}</span>
                                    </span>

                                </span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="{{ route('dashboard') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Dashboard Utama</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{ route('dashboard.infografis') }}" class="nk-menu-link"><span
                                            class="nk-menu-text">Infografis Dashboard</span></a>
                                </li>

                            </ul><!-- .nk-menu-sub -->
                        </li>
                    @endif
                    @if (auth()->user()->hasRole('user'))
                        @canany(['read_report', 'read_news'])
                            <li class="nk-menu-item has-sub"
                                active="{{ request()->routeIs('reports.index') || request()->routeIs('confirm-reports.index') || request()->routeIs('news.index') }}">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                    <span class="nk-menu-text">Laporan</span>
                                </a>

                                <ul class="nk-menu-sub">

                                    @can('read_report')
                                        <li class="nk-menu-item">
                                            <a href="{{ route('reports.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Kelola Laporan</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('confirm-reports.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Riwayat Laporan</span></a>
                                        </li>
                                    @endcan
                                    @can('read_news')
                                        <li class="nk-menu-item">
                                            <a href="{{ route('news.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Berita</span></a>
                                        </li>
                                    @endcan
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endcanany
                    @endif
                    <!-- .nk-menu-item -->
                    @canany(['read_user_management', 'read_role', 'read_permission'])
                        <li class="nk-menu-item has-sub"
                            active="{{ request()->routeIs('permission.index') || request()->routeIs('role.index') || request()->routeIs('user-management.index') }}">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">Manajemen Pengguna</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @can('read_permission')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('permission.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Permission</span></a>
                                    </li>
                                @endcan
                                @can('read_role')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('role.index') }}" class="nk-menu-link"><span class="nk-menu-text">Hak
                                                Akses</span></a>
                                    </li>
                                @endcan
                                @can('read_user_management')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('user-management.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Pengguna</span></a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))

                        @canany(['read_report', 'read_news'])
                            <li class="nk-menu-item has-sub"
                                active="{{ request()->routeIs('reports.index') || request()->routeIs('confirm-reports.index') || request()->routeIs('news.index') }}">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                    <span class="nk-menu-text">Laporan</span>
                                </a>

                                <ul class="nk-menu-sub">

                                    @can('read_report')
                                        <li class="nk-menu-item">
                                            <a href="{{ route('reports.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Kelola Laporan</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{ route('confirm-reports.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Tindak Lanjut</span></a>
                                        </li>
                                    @endcan
                                    @can('read_news')
                                        <li class="nk-menu-item">
                                            <a href="{{ route('news.index') }}" class="nk-menu-link"><span
                                                    class="nk-menu-text">Berita</span></a>
                                        </li>
                                    @endcan
                                </ul><!-- .nk-menu-sub -->
                            </li><!-- .nk-menu-item -->
                        @endcanany
                    @endif
                    @canany(['read_disaster_impacts', 'read_disaster_victims', 'read_recap'])
                        <li class="nk-menu-item has-sub"
                            active="{{ request()->routeIs('disaster_impacts.index') || request()->routeIs('disaster_victims.index') || request()->routeIs('recap.index') }}">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                <span class="nk-menu-text">Informasi</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @can('read_disaster_impacts')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('disaster_impacts.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Penanganan</span></a>
                                    </li>
                                @endcan
                                @can('read_disaster_victims')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('disaster_victims.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Data
                                                Korban</span></a>
                                    </li>
                                @endcan
                                @can('read_recap')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('recap.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Rekap</span></a>
                                    </li>
                                @endcan
                                @can('read_disaster_report_documentations')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('disaster_report_documentations.index') }}"
                                            class="nk-menu-link"><span class="nk-menu-text">Dokumen Laporan Bencana</span></a>
                                    </li>
                                @endcan
                            </ul><!-- .nk-menu-sub -->
                        </li><!-- .nk-menu-item -->
                    @endcanany
                    @canany(['read_disaster_category', 'read_consultation'])
                        <li class="nk-menu-item has-sub"
                            active="{{ request()->routeIs('disaster.index') || request()->routeIs('consultation.index') || request()->routeIs('infografis.index') }}">

                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                <span class="nk-menu-text">Kategori</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @can('read_disaster_category')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('disaster.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Jenis Bencana</span></a>
                                    </li>
                                @endcan
                                @can('read_consultation')
                                    <li class="nk-menu-item">
                                        <a href="{{ route('consultation.index') }}" class="nk-menu-link"><span
                                                class="nk-menu-text">Edukasi</span></a>
                                    </li>
                                @endcan

                            </ul>

                        </li>
                    @endcanany
                    @canany('read_infografis')
                        @can('read_infografis')
                            <li class="nk-menu-item" active="{{ request()->routeIs('infografis.index') }}">
                                <a href="{{ route('infografis.index') }}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-info"></em></span>
                                    <span class="nk-menu-text">Informasi dan Infografis</span>
                                </a>
                            </li>
                        @endcan
                    @endcanany
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>

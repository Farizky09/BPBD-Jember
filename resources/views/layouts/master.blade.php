<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="../">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
        content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/icons/bpbd.png') }}">
    <!-- Page Title  -->
    <title>Bumi Kita</title>
    <!-- StyleSheets  -->
    <style>
        ::-webkit-scrollbar {
            width: 0;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/css/dashlite.css?ver=3.2.2') }}">
    <link id="skin-default" rel="stylesheet" href="{{ asset('assets/css/theme.css?ver=3.2.2') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('style')
</head>

<body>
    <div class="nk-body ui-rounder has-sidebar">
        <div class="nk-app-root">
            <!-- main @s -->
            <div class="nk-main ">

                @include('layouts.sidebar')
                <!-- sidebar @e -->
                <!-- wrap @s -->
                <div class="nk-wrap ">
                    <!-- main header @s -->
                    @include('layouts.navbar')
                    <!-- main header @e -->
                    <!-- content @s -->
                    <div class="nk-content nk-content-fluid bg-white mx-8">
                        @yield('breadcrumb')
                        <div class="container-xl wide-xl">
                            <div class="nk-content-body">
                                @if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'))
                                    <div id="report-toast"
                                        class="fixed top-4 right-4 bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg z-[9999] hidden transition-opacity duration-500">
                                        <strong class="font-semibold">✅ Ada laporan masuk!</strong>
                                    </div>
                                @endif
                                @yield('main')
                            </div>
                        </div>
                    </div>
                    <!-- content @e -->
                    <!-- footer @s -->
                    @include('layouts.footer')
                    <!-- footer @e -->
                </div>
                <!-- wrap @e -->
            </div>
            <!-- main @e -->
        </div>
    </div>

    <!-- JavaScript -->

    <script src="{{ asset('assets/js/charts/gd-campaign.js?ver=3.2.2') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs @1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs @1/plugin/isoweek/index.min.js"></script>
    <script async src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <script src="https://unpkg.com/dayjs @1.11.10/dayjs.min.js"></script>
    <script src="{{ asset('assets/js/bundle.js?ver=3.2.2') }}"></script>
    <script src="{{ asset('assets/js/scripts.js?ver=3.2.2') }}"></script>
    <script src="{{ asset('assets/js/charts/gd-campaign.js?ver=3.2.2') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Di dalam layouts/master.blade.php -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.3.3/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.jqueryui.min.js"></script>
    <audio id="notification-sound" src="{{ asset('sounds/report-notif.wav') }}"></audio>
    @php
        $initialPending = \App\Models\Reports::where('status', 'pending')->count();
    @endphp

    {{-- <script>
        let lastPending = {{ $initialPending }};

        function fetchPendingReports() {
            fetch("{{ route('api.reports.pending') }}")
                .then(response => response.json())
                .then(data => {
                    const currentPending = data.total;
                    const badgeCount = document.getElementById('pending-count');
                    const audio = document.getElementById('notification-sound');
                    const toast = document.getElementById('report-toast');
                    const countEl = document.getElementById('pending-count');
                    if (countEl) countEl.textContent = currentPending;

                    if (currentPending > lastPending) {
                        if (audio) audio.play().catch(() => {});

                        if (toast) {
                            toast.classList.remove('hidden');
                            toast.classList.add('opacity-100');
                            setTimeout(() => {
                                toast.classList.add('opacity-0');
                                setTimeout(() => toast.classList.add('hidden'), 500);
                            }, 4000);
                        }
                    }

                    lastPending = currentPending;
                });
        }


        setTimeout(fetchPendingReports, 200);
        setInterval(fetchPendingReports, 5000);
    </script> --}}


    @stack('scripts')
</body>

</html>

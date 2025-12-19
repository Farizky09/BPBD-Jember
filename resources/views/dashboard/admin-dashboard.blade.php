@extends('layouts.master')

@section('main')
    <div class="nk-content nk-content-fluid">
        {{-- <h3 class="nk-content-title ">Halo {{ Auth::user()->getRoleNames()->first() }}</h3> --}}
        <div class="container-xl wide-xl">
            <div class="nk-content-body">
                <div class="nk-block">
                    @if ($totalReportPending > 0)
                        <div class="flex items-center p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50"
                            role="alert">
                            <i class="fas fa-info-circle me-2 w-4 h-4"></i>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-medium" id="pending-badge">
                                    Menunggu
                                </span> - Terdapat <span id="pending-count">{{ $totalReportPending }}</span> laporan
                                menunggu diproses!
                                <a href="{{ route('reports.index') }}" class="underline text-yellow-800 font-medium">Lihat
                                    Daftar
                                    Antrian</a>
                            </div>
                        </div>
                    @endif

                    {{-- @if (auth()->user()->hasRole('super_admin') && $totalReportPending > 0)
                        <audio id="notification-sound" autoplay>
                            <source src="{{ asset('sounds/alert.wav') }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>

                        <script>
                            // Delay sedikit agar tidak langsung pop di awal load
                            setTimeout(() => {
                                alert("🚨 Terdapat laporan bencana baru (pending)!");
                            }, 1000);
                        </script>
                    @endif --}}

                    <div class="row g-gs">
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-primary">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $proses }}</span>

                                                </div>
                                                <h6 class="text-white">Laporan dalam proses</h6>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                        <div class="nk-cmwg1-ck">

                                        </div>
                                    </div>
                                </div><!-- .nk-cmwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-info">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $accept }}</span>

                                                </div>
                                                <h6 class="text-white">Laporan diterima</h6>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="nk-cmwg1-ck mt-auto">
                                    </div>
                                </div><!-- .nk-cmwg -->
                            </div><!-- .card -->
                        </div><!-- .col -->
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-warning">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $reject }}</span>
                                                </div>
                                                <h6 class="text-white">Laporan ditolak</h6>
                                            </div>

                                        </div>
                                    </div><!-- .card-inner -->
                                    <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                        <div class="nk-cmwg1-ck">
                                        </div>
                                    </div>
                                </div><!-- .nk-cmwg -->
                            </div><!-- .card -->
                        </div>
                        <div class="col-xxl-12 col-lg-12">
                            <div class="card bg-white border rounded-2xl mb-2">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Peta Laporan Bencana</h6>
                                        </div>
                                    </div>
                                    <div class="card-inner">
                                        <div class="map-container">
                                            <div id="petamap" class="w-full" style="height: 400px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ route('dashboard.mapload') }}" async defer></script>
        <script>
            window.ASSET_URLS = {
                pinMapIcon: "{{ asset('assets/img/icons/pin-map.png') }}",
                informationPointIcon: "{{ asset('assets/img/icons/information-point.png') }}",
            };
        </script>
        <script>
            let map;
            let userMarker;

            function initMap() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const userPos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            map = new google.maps.Map(document.getElementById("petamap"), {
                                center: userPos,
                                zoom: 13,
                            });

                            userMarker = new google.maps.Marker({
                                position: userPos,
                                map: map,
                                icon: {
                                    url: window.ASSET_URLS.pinMapIcon,
                                    scaledSize: new google.maps.Size(30, 30),
                                },
                                title: "Lokasi Anda",
                            });

                            const userInfo = new google.maps.InfoWindow({
                                content: "Lokasi Anda",
                            });
                            userMarker.addListener("click", () => {
                                userInfo.open(map, userMarker);
                            });

                            fetch("/get-disaster-marker")
                                .then((response) => response.json())
                                .then((data) => {
                                    if (
                                        data.status === "success" &&
                                        Array.isArray(data.data)
                                    ) {
                                        data.data.forEach((item) => {
                                            // console.log(item);
                                            const reports = item.reports;
                                            const disaster_impacts = item.disaster_impacts;
                                            // console.log("disini ", disaster_impacts);
                                            // console.log("disini ", reports);
                                            if (!item.reports) return;
                                            const pos = {
                                                lat: parseFloat(reports.latitude),
                                                lng: parseFloat(reports.longitude),
                                            };

                                            const marker = new google.maps.Marker({
                                                position: pos,
                                                map: map,
                                                icon: {
                                                    url: window.ASSET_URLS.informationPointIcon,
                                                    scaledSize: new google.maps.Size(
                                                        25,
                                                        25
                                                    ),
                                                },
                                                title: reports.disaster_category.name ||
                                                    "Lokasi Bencana",
                                            });

                                            const infoWindow = new google.maps.InfoWindow({
                                                content: `
                                                    <div style="min-width:250px; font-family:inherit;">
                                                        <div style="font-size:1.1rem; font-weight:bold; margin-bottom:6px;">
                                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                                            ${
                                                                reports.disaster_category
                                                                    .name
                                                            }
                                                        </div>
                                                        <div style="margin-bottom:4px;">
                                                            <i class="fas fa-calendar-alt text-primary"></i>
                                                            <strong>Tanggal:</strong> ${
                                                                reports.created_at_formatted
                                                            }
                                                        </div>
                                                        <div style="margin-bottom:4px;">
                                                            <i class="fas fa-map-marker-alt text-danger"></i>
                                                            <span>${reports.address}</span>
                                                        </div>
                                                        <hr style="margin:8px 0;">
                                                        <div style="margin-bottom:2px;">
                                                            <i class="fas fa-users text-info"></i>
                                                            <strong>Orang Terdampak:</strong> ${
                                                                disaster_impacts.affected_people ??
                                                                0
                                                            }
                                                        </div>
                                                        <div style="margin-bottom:2px;">
                                                            <i class="fas fa-skull-crossbones text-danger"></i>
                                                            <strong>Meninggal:</strong> ${
                                                                disaster_impacts.deceased_people ??
                                                                0
                                                            }
                                                        </div>
                                                        <div style="margin-bottom:2px;">
                                                            <i class="fas fa-user-injured text-warning"></i>
                                                            <strong>Terluka:</strong> ${
                                                                disaster_impacts.injured_people ??
                                                                0
                                                            }
                                                        </div>
                                                        <div>
                                                            <i class="fas fa-user-secret text-secondary"></i>
                                                            <strong>Hilang:</strong> ${
                                                                disaster_impacts.missing_people ??
                                                                0
                                                            }
                                                        </div>
                                                        @if (auth()->user()->hasRole('admin'))
                                                            @if (auth()->user()->id == '${item.admin_id}')
                                                                <a href="/confirm-reports/detail/${item.id}"
                                                                    class="text-blue-500 hover:underline mt-2 block">
                                                                    Lihat Detail Laporan
                                                                </a>
                                                            @endif
                                                        @endif


                                                    </div>
                                                `,
                                            });

                                            marker.addListener("click", () => {
                                                infoWindow.open(map, marker);
                                            });
                                        });
                                    } else {
                                        console.warn(
                                            "Data marker tidak tersedia atau format salah."
                                        );
                                    }
                                })
                                .catch((error) => {
                                    console.error("Gagal mengambil data marker:", error);
                                });
                        },
                        function(error) {
                            document.getElementById("status").textContent =
                                "Gagal mendapatkan lokasi Anda.";
                            console.error("Geolocation error:", error);
                        }
                    );
                } else {
                    document.getElementById("status").textContent =
                        "Browser tidak mendukung geolocation.";
                }
            }

            $(document).ready(function() {
                function fetchPendingCount() {
                    $.get('/api/pending-reports-count', function(data) {
                        const currentCount = parseInt($('#pending-count').text());
                        if (currentCount !== data.count) {
                            $('#pending-count').text(data.count);
                            $('.alert-div-class').toggle(data.count > 0);
                        }
                    }).fail(function(error) {

                    });
                }

                setInterval(fetchPendingCount, 5000);
                fetchPendingCount();
            });
        </script>
    @endpush
@endsection

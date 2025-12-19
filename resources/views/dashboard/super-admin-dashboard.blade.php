@extends('layouts.master')

@section('main')
    <div class="nk-content nk-content-fluid">
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
                    <div class="row g-gs">
                        <!-- Statistik Cards -->
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-primary">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $totalReportAccepted }}</span>
                                                </div>
                                                <h6 class="text-white">Total Laporan Diterima</h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>



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



                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-info">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $totalReportRejected }}</span>
                                                </div>
                                                <h6 class="text-white">Total Laporan Ditolak</h6>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-warning">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1">{{ $totalReportDone }}</span>
                                                </div>
                                                <h6 class="text-white">Total Laporan Selesai</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="nk-ck-wrap mt-auto overflow-hidden rounded-bottom">
                                        <div class="nk-cmwg1-ck">
                                            <canvas class="campaign-bar-chart-s1 rounded-bottom" id="avgRating"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-12 col-lg-12">
                            <div class="card  bg-white rounded-2xl border">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Grafik Bencana yang Telah Diterima</h6>
                                        </div>
                                        <ul class="card-tools-nav">
                                            <li><a onclick="updateChart('day')">7 Hari Terakhir</a></li>
                                            <li><a onclick="updateChart('week')">4 Minggu Terakhir</a></li>
                                            <li><a onclick="updateChart('month')">12 Bulan Terakhir</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-inner py-4">
                                    {{-- <ul class="d-flex justify-content-center flex-wrap gx-3 mb-2">
                                        @foreach ($chartData['categories'] as $category)
                                            <li class="align-center">
                                                <span class="dot"
                                                    style="background-color: {{ $category['color'] }}"></span>
                                                <span class="ms-1">{{ $category['name'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul> --}}
                                    <div class="nk-cmwg2-ck">
                                        <canvas class="campaign-line-chart-s2" id="performanceOverview"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-8 col-lg-7">
                            <div class="card h-[420px] bg-white border rounded-2xl mb-2">
                                <p class="mt-2 mx-2">Peta Map Bencana</p>
                                <div class="map-container mx-3 my-1">
                                    <div id="petamap" class="w-full items-center justify-center" style="height: 360px;">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-xxl-4 col-lg-5">
                            <div class="card h-[420px] bg-white rounded-2xl border mb-4">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Daftar Point Pengguna Tertinggi</h6>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-inner pt-0  overflow-y-auto custom-scrollbar">
                                    <ul class="gy-4">
                                        @foreach ($leaderboard as $user)
                                            <li
                                                class="d-flex justify-content-between align-items-center border-bottom border-0 border-dashed pb-3 bg-gray-50 p-3 rounded-xl border my-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="w-12 h-12 rounded-full overflow-hidden bg-light">
                                                        <img src="{{ $user->image_avatar ? asset('storage/' . $user->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                                                            alt="" class="w-full h-full object-cover rounded-full">
                                                    </div>

                                                    <div class="ms-2 max-w-[80px] break-words whitespace-normal">
                                                        <div class="lead-text break-words whitespace-normal">
                                                            {{ $user->name }}
                                                        </div>
                                                        <div class="sub-text break-words whitespace-normal">
                                                            {{ $user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column align-items-end text-end">
                                                    <div class="sub-text mb-1">{{ $user->poin }} poin</div>
                                                    <span
                                                        class="badge
                                @if ($user->poin >= 101) bg-warning
                                @elseif($user->poin >= 51) bg-primary
                                @else bg-secondary @endif
                                text-white px-2 rounded-xl py-1 text-center">
                                                        @if ($user->poin >= 101)
                                                            Gold
                                                        @elseif($user->poin >= 51)
                                                            Silver
                                                        @else
                                                            Bronze
                                                        @endif
                                                    </span>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>



                        <div class=" card bg-white border rounded-2xl p-6">
                            <div class="card-title mb-4">
                                <h6 class="text-lg font-semibold text-gray-800">Tabel Berita Terbaru</h6>
                            </div>
                            <div class="overflow-x-auto">
                                <table id="newsDataTable" class="min-w-full text-sm text-left border rounded-md">
                                    <thead class="bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 border-b">No</th>
                                            <th class="px-4 py-3 border-b">Judul Berita</th>
                                            <th class="px-4 py-3 border-b">Alamat Bencana</th>
                                            <th class="px-4 py-3 border-b">Jenis Bencana</th>
                                            <th class="px-4 py-3 border-b">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-gray-700">
                                        @foreach ($latestNews as $news)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                                <td class="px-4 py-2">{{ $news->title }}</td>
                                                <td class="px-4 py-2">{{ $news->confirmReports->report->address }}</td>
                                                <td class="px-4 py-2">
                                                    {{ $news->confirmReports->report->disasterCategory->name }}</td>
                                                <td class="px-4 py-2">
                                                    @if ($news->status == 'published')
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">Published</span>
                                                    @elseif ($news->status == 'takedown')
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">Takedown</span>
                                                    @else
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-700 rounded">{{ $news->status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
                                                        @if (auth()->user()->hasRole('super_admin'))
                                                            <div>
                                                                <a href="/confirm-reports/detail/${item.id}"
                                                                    class="text-blue-500 hover:underline mt-2 block">
                                                                    Lihat Detail Laporan
                                                                </a>
                                                            </div>
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


            let chart;
            const chartData = @json($chartData);

            function prepareData(type) {
                const typeCapitalized = type.charAt(0).toUpperCase() + type.slice(1);
                const labels = chartData[`labels${typeCapitalized}`].map(l => l.display);
                const rawLabels = chartData[`labels${typeCapitalized}`].map(l => l.raw);
                const datasets = [];

                Object.values(chartData.categories).forEach(category => {
                    const categoryData = category[`data${typeCapitalized}`];
                    const data = rawLabels.map(rawLabel => {
                        return categoryData[rawLabel] ? categoryData[rawLabel].length : 0;
                    });

                    datasets.push({
                        label: category.name,
                        data: data,
                        borderColor: category.color,
                        backgroundColor: `${category.color}33`,
                        borderWidth: 2,
                        pointStyle: category.pointStyle || 'circle',
                        pointBackgroundColor: category.pointBackgroundColor || category.color,
                        pointRadius: category.pointRadius,
                        pointHoverRadius: category.pointHoverRadius,
                        tension: category.tension,
                        fill: category.fill
                    });
                });

                return {
                    labels,
                    datasets
                };
            }

            function updateChart(type) {
                const {
                    labels,
                    datasets
                } = prepareData(type);

                document.querySelectorAll('.card-tools-nav a').forEach(el => {
                    el.classList.remove('active');
                });

                document.querySelector(`.card-tools-nav a[onclick="updateChart('${type}')"]`)?.classList.add('active');

                if (chart) {
                    chart.data.labels = labels;
                    chart.data.datasets = datasets;
                    chart.options.scales.x.title.text = getAxisTitle(type);
                    chart.update();
                } else {
                    initChart(labels, datasets, type);
                }
            }

            function initChart(labels, datasets, type) {
                const ctx = document.getElementById('performanceOverview').getContext('2d');

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels,
                        datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 20,
                                    font: {
                                        size: 13
                                    },
                                    usePointStyle: true,
                                    pointStyle: 'circle'
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.parsed.y}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: getAxisTitle(type)
                                },
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: type === 'month' ? 12 : (type === 'week' ? 4 : 30)
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Laporan Diterima'
                                },
                                ticks: {
                                    precision: 0,
                                    callback: function(value) {
                                        return Number.isInteger(value) ? value : '';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            function getAxisTitle(filter) {
                switch (filter) {
                    case 'day':
                        return '7 Hari Terakhir';
                    case 'week':
                        return '4 Minggu Terakhir';
                    case 'month':
                        return '12 Bulan Terakhir';
                    default:
                        return '';
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                updateChart('day');
            });

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

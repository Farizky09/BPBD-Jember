@extends('layouts.master')

@push('style')
    <style>
        /* Gaya untuk DataTables dan filter */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            float: none;
            text-align: left;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_length label select {
            display: flex;
            align-items: center;
        }

        .dataTables_length label select {
            margin: 5px;
        }

        /* Gaya untuk kartu statistik */
        .card-stats .card-inner {
            text-align: center;
            padding: 1rem;
        }

        .card-stats .card-title {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .card-stats .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
        }
    </style>
@endpush

@section('breadcrumb')
    @php
        $links = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Infografis', 'url' => '']];
    @endphp
    <x-breadcrumb :links="$links" title="Infografis Laporan" class="text-center" />
@endsection

@section('main')
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Data Rekapitulasi Laporan Bencana</h4>
                </div>
            </div>

            {{-- Filter Section --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="startDate" class="form-label">Tanggal Awal</label>
                            <input type="date" id="startDate" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-5">
                            <label for="endDate" class="form-label">Tanggal Akhir</label>
                            <input type="date" id="endDate" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="buttonFilter" class="btn btn-success d-block w-100"><i
                                    class="fas fa-filter me-2"></i> Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New: Stats Cards Section --}}
            <div class="nk-block">
                <div class="row g-gs">
                    <div class="my-4 w-full">
                        <div class="bg-gradient-to-r from-indigo-900 to-blue-500 rounded-2xl shadow-lg p-6 min-h-[150px]">
                            <h5 class="text-white text-lg font-semibold mb-4">
                                Total Terdampak: <span class="font-bold text-white text-2xl"
                                    id="affected_people">{{ $stats['affected_people'] ?? 0 }} Korban</span>
                            </h5>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4 text-white">
                                <div class="flex flex-col items-start">
                                    <span class="text-sm">👶 Bayi</span>
                                    <div class="text-lg font-bold" id="affected_babies">{{ $stats['affected_babies'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm">👴 Lansia</span>
                                    <div class="text-lg font-bold" id="affected_elderly">
                                        {{ $stats['affected_elderly'] ?? 0 }}</div>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm">♿ Disabilitas</span>
                                    <div class="text-lg font-bold" id="affected_disabled">
                                        {{ $stats['affected_disabled'] ?? 0 }}</div>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm">🤰 Ibu Hamil</span>
                                    <div class="text-lg font-bold" id="affected_pregnant_women">
                                        {{ $stats['affected_pregnant_women'] ?? 0 }}</div>
                                </div>
                                <div class="flex flex-col items-start">
                                    <span class="text-sm">👥 Umum</span>
                                    <div class="text-lg font-bold" id="affected_general">
                                        {{ $stats['affected_general'] ?? 0 }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-gray-500 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Total Kejadian</h5>
                                <div class="stat-value text-white" id="total_kejadian">{{ $stats['total_kejadian'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-gray-600 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Fasilitas Umum Rusak</h5>
                                <div class="stat-value text-white" id="damaged_public_facilities">
                                    {{ $stats['damaged_public_facilities'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Card: Rumah Rusak Ringan --}}
                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-gray-800 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Rumah Rusak Ringan</h5>
                                <div class="stat-value text-white" id="lightly_damaged_houses">
                                    {{ $stats['lightly_damaged_houses'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-gray-950 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Rumah Rusak Sedang</h5>
                                <div class="stat-value text-white" id="moderately_damaged_houses">
                                    {{ $stats['moderately_damaged_houses'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-red-500 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Rumah Rusak Berat</h5>
                                <div class="stat-value text-white" id="heavily_damaged_houses">
                                    {{ $stats['heavily_damaged_houses'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-red-700 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Korban Luka</h5>
                                <div class="stat-value text-white" id="injured_people">
                                    {{ $stats['injured_people'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-red-800 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Korban Meninggal Dunia</h5>
                                <div class="stat-value text-white" id="deceased_people">
                                    {{ $stats['deceased_people'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 col-sm-6 col-md-3">
                        <div class="card bg-red-950 card-stats card-full">
                            <div class="card-inner">
                                <h5 class="card-title text-white">Korban Hilang</h5>
                                <div class="stat-value text-white" id="missing_people">
                                    {{ $stats['missing_people'] ?? 0 }}</div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="col-xxl-12 col-lg-12">
                <div class="card bg-white rounded-2xl border">
                    <div class="card-inner">
                        <div class="card-title-group">
                            <div class="card-title">
                                <h6 class="title">Grafik Kejadian Bencana Tahun {{ now()->year }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-inner py-4">
                        <div class="nk-cmwg2-ck">
                            <canvas class="campaign-line-chart-s2" id="disasterOccurrenceChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Tables Section --}}
            <div class="nk-block mt-4">
                <div class="row g-gs">
                    {{-- Disaster Category Table --}}
                    <div class="col-md-6">
                        <div class="card card-full">
                            <div class="card-inner">
                                <h5 class="card-title">Total Kejadian Berdasarkan Kategori Bencana</h5>
                                <div class="table-responsive">
                                    <table id="categoryDataTable" class="table table-bordered table-striped align-middle"
                                        style="width: 100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Bencana</th>
                                                <th>Total Kejadian</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Subdistrict Table --}}
                    <div class="col-md-6">
                        <div class="card card-full">
                            <div class="card-inner">
                                <h5 class="card-title">Total Kejadian Berdasarkan Kecamatan</h5>
                                <div class="table-responsive">
                                    <table id="subdistrictDataTable"
                                        class="table table-bordered table-striped align-middle" style="width: 100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Kecamatan</th>
                                                <th>Total Kejadian</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                createDataTable('category');
                createDataTable('subdistrict');


                $("#buttonFilter").click(function() {

                    reloadDataTable('category');
                    reloadDataTable('subdistrict');

                    updateStatsCards();
                });


                const startDateInput = document.getElementById('startDate');
                const endDateInput = document.getElementById('endDate');
                startDateInput.addEventListener('change', function() {
                    endDateInput.min = this.value;
                });
            });


            function reloadDataTable(type) {
                $(`#${type}DataTable`).DataTable().ajax.reload();
            }

            function createDataTable(type) {
                const url = '{{ route('dashboard.infografis.data') }}';
                let columns = [];

                if (type === 'category') {
                    columns = [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'category_name',
                            name: 'category_name'
                        },
                        {
                            data: 'total_incidents',
                            name: 'total_incidents'
                        }
                    ];
                } else if (type === 'subdistrict') {
                    columns = [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'subdistrict',
                            name: 'subdistrict'
                        },
                        {
                            data: 'total_incidents',
                            name: 'total_incidents'
                        }
                    ];
                }

                $(`#${type}DataTable`).DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    responsive: true,
                    ajax: {
                        url: url,
                        data: function(d) {
                            d.start_date = $('#startDate').val();
                            d.end_date = $('#endDate').val();
                            d.data_type = type;
                        }
                    },
                    language: {
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "<i class='fas fa-chevron-right'></i>",
                            "previous": "<i class='fas fa-chevron-left'></i>"
                        }
                    },
                    columns: columns,
                });
            }

            function updateStatsCards() {
                const startDate = $('#startDate').val();
                const endDate = $('#endDate').val();

                $.ajax({
                    url: '{{ route('dashboard.infografis.data') }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        data_type: 'stats'
                    },

                    success: function(response) {
                        $('#total_kejadian').text(response.total_kejadian);
                        $('#lightly_damaged_houses').text(response.lightly_damaged_houses);
                        $('#moderately_damaged_houses').text(response.moderately_damaged_houses);
                        $('#heavily_damaged_houses').text(response.heavily_damaged_houses);
                        $('#damaged_public_facilities').text(response.damaged_public_facilities);
                        $('#affected_people').text(response.affected_people);
                        $('#injured_people').text(response.injured_people);
                        $('#deceased_people').text(response.deceased_people);
                        $('#missing_people').text(response.missing_people);
                        $('#affected_babies').text(response.affected_babies);
                        $('#affected_elderly').text(response.affected_elderly);
                        $('#affected_disabled').text(response.affected_disabled);
                        $('#affected_pregnant_women').text(response.affected_pregnant_women);
                        $('#affected_general').text(response.affected_general);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching stats:", error);
                    }
                });
            }

            const ctx = document.getElementById('disasterOccurrenceChart').getContext('2d');
            const chartData = {
                labels: @json($chartData['labels']),
                datasets: @json($chartData['datasets'])
            };

            const chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
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
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                const label = context.dataset.label || '';
                                const value = context.parsed.y;
                                return `${label}: ${value} kejadian`;
                            }
                        },
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            },
                            precision: 0,
                            stepSize: 1
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Kejadian'
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            };

            new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: chartOptions
            });
        </script>
        @include('components.flash-message')
    @endpush
@endsection

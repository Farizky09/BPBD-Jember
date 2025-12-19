@extends('layouts.master')

@section('main')
    <p class="text-3xl font-bold text-center mb-4">Informasi Kejadian Bencana</p>
    <div class="nk-content nk-content-fluid">
        <div class="container-xl wide-xl">
            <div class="nk-content-body">
                <div class="nk-block">
                    <div class="row align-items-end justify-content-center mb-4">
                        <form id="filterForm" class="row w-100">
                            <div class="col-md-5">
                                <x-input.input type="date" name="start_date" id="start_date" label="Tanggal Awal"
                                    value="{{ $startDate }}"></x-input.input>
                            </div>
                            <div class="col-md-5">
                                <x-input.input type="date" name="end_date" id="end_date" label="Tanggal Akhir"
                                    value="{{ $endDate }}"></x-input.input>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit"
                                    class="focus:outline-none text-white bg-green-500 font-medium rounded-lg text-sm px-5 py-2.5 w-100 ">
                                    Cari
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="row g-gs">
                        <!-- Statistik Utama -->
                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-gray-500">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="totalKejadian">0</span>
                                                </div>
                                                <h6 class="text-white">Total Kejadian Bencana</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-gray-700">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="affectedPeople">0</span>
                                                </div>
                                                <h6 class="text-white">Orang/Keluarga Terdampak</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-gray-900">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="damagedPublicFacilities">0</span>
                                                </div>
                                                <h6 class="text-white">Fasilitas Umum Rusak</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-blue-500">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="lightlyDamagedHouses">0</span>
                                                </div>
                                                <h6 class="text-white">Rumah Rusak Ringan</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-blue-700">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="moderatelyDamagedHouses">0</span>
                                                </div>
                                                <h6 class="text-white">Rumah Rusak Sedang</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-blue-900">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="heavilyDamagedHouses">0</span>
                                                </div>
                                                <h6 class="text-white">Rumah Rusak Berat</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-red-500">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="injuredPeople">0</span>
                                                </div>
                                                <h6 class="text-white">Korban Luka</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-red-700">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="deceasedPeople">0</span>
                                                </div>
                                                <h6 class="text-white">Korban Meninggal Dunia</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-4">
                            <div class="card h-100 bg-red-900">
                                <div class="nk-cmwg nk-cmwg1">
                                    <div class="card-inner pt-3">
                                        <div class="d-flex justify-content-between">
                                            <div class="flex-item">
                                                <div class="text-white d-flex flex-wrap">
                                                    <span class="fs-2 me-1" id="missingPeople">0</span>
                                                </div>
                                                <h6 class="text-white">Korban Hilang</h6>
                                            </div>
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
                                        <canvas class="campaign-line-chart-s2" id="disasterOccurrenceChart"
                                            height="300"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Kejadian per Jenis Bencana -->
                        <div class="col-12 mt-4">
                            <div class="card bg-white border rounded-2xl p-6 h-full">
                                <div class="card-title">
                                    <h6 class="text-lg font-semibold text-gray-800">Tabel Kejadian Bencana</h6>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm text-left border rounded-md" id="disasterTypesTable">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 border-b">No</th>
                                                <th class="px-4 py-3 border-b">Jenis Bencana</th>
                                                <th class="px-4 py-3 border-b">Total Kejadian</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            @foreach ($disasterCategories as $index => $category)
                                                <tr>
                                                    <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 border-b">{{ $category->name }}</td>
                                                    <td class="px-4 py-3 border-b category-total"
                                                        data-category="{{ $category->name }}">0</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Kejadian per Kecamatan -->
                        <div class="col-12 mt-4">
                            <div class="card bg-white border rounded-2xl p-6 h-full">
                                <div class="card-title">
                                    <h6 class="text-lg font-semibold text-gray-800">Tabel Bencana tiap Kecamatan</h6>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm text-left border rounded-md" id="subdistrictsTable">
                                        <thead class="bg-gray-100 text-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 border-b">No</th>
                                                <th class="px-4 py-3 border-b">Nama Kecamatan</th>
                                                <th class="px-4 py-3 border-b">Total Kejadian</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            @foreach ($subdistricts as $index => $subdistrict)
                                                @php
                                                    $subdistrictKey = strtoupper(trim($subdistrict));
                                                @endphp
                                                <tr>
                                                    <td class="px-4 py-3 border-b">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 border-b">{{ ucwords(strtolower($subdistrict)) }}
                                                    </td>
                                                    <td class="px-4 py-3 border-b subdistrict-total"
                                                        data-subdistrict="{{ $subdistrictKey }}">
                                                        {{ $subdistrictData[$subdistrictKey] ?? 0 }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables dengan menyimpan instance-nya
            let disasterTypesTable = null;
            let subdistrictsTable = null;

            function initDataTables() {
                // Hancurkan instance DataTables jika sudah ada
                if (disasterTypesTable) {
                    disasterTypesTable.destroy();
                    $('#disasterTypesTable').empty();
                }
                if (subdistrictsTable) {
                    subdistrictsTable.destroy();
                    $('#subdistrictsTable').empty();
                }

                // Rebuild headers
                const disasterHeader = `
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 border-b">No</th>
                            <th class="px-4 py-3 border-b">Jenis Bencana</th>
                            <th class="px-4 py-3 border-b">Total Kejadian</th>
                        </tr>
                    </thead>
                `;

                const subdistrictHeader = `
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3 border-b">No</th>
                            <th class="px-4 py-3 border-b">Nama Kecamatan</th>
                            <th class="px-4 py-3 border-b">Total Kejadian</th>
                        </tr>
                    </thead>
                `;

                $('#disasterTypesTable').html(disasterHeader);
                $('#subdistrictsTable').html(subdistrictHeader);

                // Inisialisasi ulang DataTables
                disasterTypesTable = $('#disasterTypesTable').DataTable({
                    paging: true,
                    searching: true,
                    info: true,
                    lengthChange: false,
                    pageLength: 5,
                    ordering: true,
                    language: {
                        search: "Cari:",
                        paginate: {
                            previous: "Sebelumnya ( ",
                            next: " ) Berikutnya"
                        },
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                        emptyTable: "Tidak ada data",
                        infoEmpty: "Menampilkan 0 hingga 0 dari 0 data"
                    },
                    data: [],
                    columns: [{
                            data: null,
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'category'
                        },
                        {
                            data: 'total'
                        }
                    ]
                });

                subdistrictsTable = $('#subdistrictsTable').DataTable({
                    paging: true,
                    searching: true,
                    info: true,
                    lengthChange: false,
                    pageLength: 5,
                    ordering: true,
                    language: {
                        search: "Cari:",
                        paginate: {
                            previous: "Sebelumnya ( ",
                            next: " ) Berikutnya"
                        },
                        info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                        emptyTable: "Tidak ada data",
                        infoEmpty: "Menampilkan 0 hingga 0 dari 0 data"
                    },
                    data: [],
                    columns: [{
                            data: null,
                            render: (data, type, row, meta) => meta.row + 1
                        },
                        {
                            data: 'subdistrict'
                        },
                        {
                            data: 'total'
                        }
                    ]
                });
            }

            // Inisialisasi pertama
            initDataTables();

            function loadInfographicData() {
                $.ajax({
                    url: "{{ route('dashboard.infografis.data') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#filterForm').serialize(),
                    success: function(response) {
                        updateInfographicContent(response);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat memuat data';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg += ': ' + xhr.responseJSON.error;
                        } else if (xhr.responseText) {
                            try {
                                const res = JSON.parse(xhr.responseText);
                                errorMsg += ': ' + res.error;
                            } catch {
                                errorMsg += ': ' + xhr.responseText;
                            }
                        }
                        alert(errorMsg);
                    }
                });
            }

            function updateInfographicContent(data) {
                try {
                    // Update statistik utama
                    $('#totalKejadian').text(data.stats.total_kejadian || 0);
                    $('#affectedPeople').text(data.stats.affected_people || 0);
                    $('#lightlyDamagedHouses').text(data.stats.lightly_damaged_houses || 0);
                    $('#moderatelyDamagedHouses').text(data.stats.moderately_damaged_houses || 0);
                    $('#heavilyDamagedHouses').text(data.stats.heavily_damaged_houses || 0);
                    $('#damagedPublicFacilities').text(data.stats.damaged_public_facilities || 0);
                    $('#injuredPeople').text(data.stats.injured_people || 0);
                    $('#deceasedPeople').text(data.stats.deceased_people || 0);
                    $('#missingPeople').text(data.stats.missing_people || 0);

                    // Siapkan data untuk DataTables
                    const disasterData = [];
                    for (const [category, total] of Object.entries(data.disasterTypes)) {
                        disasterData.push({
                            category: category,
                            total: total
                        });
                    }

                    const subdistrictData = [];
                    for (const [subdistrict, total] of Object.entries(data.subdistrictData)) {
                        subdistrictData.push({
                            subdistrict: subdistrict,
                            total: total
                        });
                    }

                    // Perbarui DataTables
                    disasterTypesTable.clear();
                    disasterTypesTable.rows.add(disasterData);
                    disasterTypesTable.draw();

                    subdistrictsTable.clear();
                    subdistrictsTable.rows.add(subdistrictData);
                    subdistrictsTable.draw();

                } catch (e) {
                    console.error('Error updating content:', e);
                    alert('Terjadi kesalahan saat memperbarui tampilan');
                }
            }

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadInfographicData();
            });

            loadInfographicData();

            // Chart - kode tetap sama
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
        });
    </script>
@endpush

{{-- @push('scripts')
    <script>
        $(document).ready(function() {
            $('#disasterTypesTable').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false,
                pageLength: 5,
                ordering: true,
                language: {
                    search: "Cari:",

                    paginate: {
                        previous: " Sebelumnya (",
                        next: ") Berikutnya "
                    },
                    emptyTable: "Tidak ada data"
                }
            });

            $('#subdistrictsTable').DataTable({
                paging: true,
                searching: true,
                info: false,
                lengthChange: false,
                pageLength: 5,
                ordering: true,
                language: {
                    search: "Cari:",
                    paginate: {
                        previous: " Sebelumnya (",
                        next: ") Berikutnya "
                    },
                    emptyTable: "Tidak ada data"
                }
            });

            function loadInfographicData() {
                $.ajax({
                    url: "{{ route('dashboard.infografis.data') }}",
                    type: "GET",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: $('#filterForm').serialize(),
                    success: function(response) {
                        updateInfographicContent(response);
                    },
                    error: function(xhr) {
                        let errorMsg = 'Terjadi kesalahan saat memuat data';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMsg += ': ' + xhr.responseJSON.error;
                        } else if (xhr.responseText) {
                            try {
                                const res = JSON.parse(xhr.responseText);
                                errorMsg += ': ' + res.error;
                            } catch {
                                errorMsg += ': ' + xhr.responseText;
                            }
                        }
                        alert(errorMsg);
                    }
                });
            }


            function updateInfographicContent(data) {
                try {

                    $('#totalKejadian').text(data.stats.total_kejadian);
                    $('#affectedPeople').text(data.stats.affected_people);
                    $('#lightlyDamagedHouses').text(data.stats.lightly_damaged_houses);
                    $('#moderatelyDamagedHouses').text(data.stats.moderately_damaged_houses);
                    $('#heavilyDamagedHouses').text(data.stats.heavily_damaged_houses);
                    $('#damagedPublicFacilities').text(data.stats.damaged_public_facilities);
                    $('#injuredPeople').text(data.stats.injured_people);
                    $('#deceasedPeople').text(data.stats.deceased_people);
                    $('#missingPeople').text(data.stats.missing_people);



                    $('.category-total').each(function() {
                        const category = $(this).data('category');
                        const total = data.disasterTypes[category] || 0;
                        $(this).text(total);
                    });
                    console.log("Subdistrict data from server:", data.subdistrictData); // Debug

                    $('.subdistrict-total').each(function() {
                        const subdistrict = $(this).data('subdistrict');
                        const total = data.subdistrictData[subdistrict] || 0;
                        $(this).text(total);
                    });
                } catch (e) {
                    console.error('Error updating content:', e);
                    alert('Terjadi kesalahan saat memperbarui tampilan');
                }
            }


            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                loadInfographicData();
            });
            loadInfographicData();
            console.log(loadInfographicData, 'p');


            // chart
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


        });
    </script>
@endpush --}}

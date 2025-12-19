@extends('layouts.master')
@push('style')
    <style>
        .dataTables_wrapper .dataTables_lenght {
            float: left;
            margin-right: 10px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        .dataTables_lenght label {
            display: flex;
            align-items: center;
        }

        .dataTables_lenght label select {
            margin: 5px;
        }
    </style>
@endpush
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Dokumen Laporan Bencana', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Dokumen Laporan Bencana" class="text-center" />
@endsection
@section('main')
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <x-select id="category" name="category" label="Kategori Bencana">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        <div class="col-md-3">
                            <x-select id="subdistrict" name="subdistrict" label="Kecamatan">
                                <option value="">Semua Kecamatan</option>
                                @foreach ($normalizedSubdistricts as $dataKecamatan)
                                    <option value="{{ strtolower($dataKecamatan) }}">{{ $dataKecamatan }}</option>
                                @endforeach
                            </x-select>
                        </div>
                        {{-- <div class="col-md-3">
                            <x-select id="status" name="status" label="Status">
                                <option value="">Semua Status</option>
                                @foreach ($status as $item)
                                    <option value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endforeach
                            </x-select>
                        </div> --}}
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" id="buttonFilter"
                                class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-md hover:bg-green-00 focus:outline-none focus:ring-2 focus:ring-green-500">
                                <i class="fas fa-filter "></i>Filter Data
                            </button>
                        </div>
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Tanggal Awal</label>
                            <input type="date" id="startDate" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">Tanggal Akhir</label>
                            <input type="date" id="endDate" class="form-control">
                        </div>
                        <div class="col-md-6 d-flex gap-2 w-full">
                            <form id="form-export-pdf" action="{{ route('disaster_report_documentations.export-pdf') }}"
                                method="POST">
                                @csrf

                                <input type="hidden" name="start_date" id="export_pdf_start_date">
                                <input type="hidden" name="end_date" id="export_pdf_end_date">
                                <input type="hidden" name="subdistrict" id="export_pdf_subdistrict">
                                <input type="hidden" name="category" id="export_pdf_category">
                                <button type="submit" id="btn-export-pdf"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <i class="fa fa-file-pdf mr-2"></i> Download
                                </button>
                            </form>
                            <form id="form-export-excel" action="{{ route('disaster_report_documentations.export-excel') }}"
                                method="POST">
                                @csrf
                            
                                <input type="hidden" name="start_date" id="export_excel_start_date">
                                <input type="hidden" name="end_date" id="export_excel_end_date">
                                <input type="hidden" name="subdistrict" id="export_excel_subdistrict">
                                <input type="hidden" name="category" id="export_excel_category">
                                <button type="submit" id="btn-export-excel"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    <i class="fa fa-file-excel mr-2"></i> Download
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nk-block-head nk-block-head-sm">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                            class="icon ni ni-more-v"></em>
                    </a>
                    <div class="toggle-expand-content" data-contet="pageMenu">
                        <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                            <li>
                                <a href="{{ route('disaster_report_documentations.create') }}">
                                    <x-button.button-success>Tambah</x-button.button-success>

                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="nk-block">
                <div class="row g-gas">
                    <table id="disasterReportDocumentationsDataTables" class="table table-bordered" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Laporan</th>
                                <th>Waktu</th>
                                <th>Kecamatan</th>
                                <th>Alamat</th>
                                <th>Jenis Bencana</th>
                                <th>Kronologi Bencana</th>
                                <th>Dampak Bencana</th>
                                <th>Penanganan Bencana</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function btnDelete(id, kd_report) {
                let url = '{{ route('disaster_report_documentations.delete', ':id') }}'.replace(':id', id);
                Swal.fire({
                    title: 'apakah anda yakin?',
                    text: 'Data Laporan ' + kd_report + ' akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    customClass: {
                        popup: 'swal2-border-radius',
                        confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                    },

                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "Delete",
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Berhasil!',
                                    'Data Laporan Bencana ' + kd_report + ' berhasil dihapus',
                                    'success'
                                ).then(() => {
                                    $('#disasterReportDocumentationsDataTables').DataTable().ajax
                                        .reload(null, false);
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagall!',
                                    'Data Laporan Bencana' + kd_report + 'gagal dihapus',
                                    'error'
                                );
                            }
                        })
                    }
                })
            }
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            startDateInput.addEventListener('change', function() {
                const startDate = this.value;
                endDateInput.min = startDate;
            });

            function reloadDataTable() {
                $('#disasterReportDocumentationsDataTables').DataTable().ajax.reload();
            }

            function createDataTable() {
                let url = '{{ route('disaster_report_documentations.index') }}';
                let table = $('#disasterReportDocumentationsDataTables').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: false,
                    responsive: true,
                    scrollX: true,
                    ajax: {
                        url: url,
                        data: function(d) {
                            d.category = $('#category').val();
                            d.subdistrict = $('#subdistrict').val();
                            d.start_date = $('#startDate').val();
                            d.end_date = $('#endDate').val();

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
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kd_report',
                            name: 'kd_report'
                        },
                        {
                            data: 'time',
                            name: 'time'
                        },
                        {
                            data: 'subdistrict',
                            name: 'subdistrict',
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'disaster_category',
                            name: 'disaster_category'
                        },
                        {
                            data: 'disaster_chronology',
                            name: 'disaster_chronology'
                        },
                        {
                            data: 'disaster_impact',
                            name: 'disaster_impact'
                        },
                        {
                            data: 'disaster_response',
                            name: 'disaster_response'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]

                });
            }
            createDataTable();
            $("#buttonFilter").click(function() {
                reloadDataTable();
            });
            $("#btn-export-pdf").click(function(e) {
                e.preventDefault();

                $("#export_pdf_start_date").val($("#start_date").val());
                $("#export_pdf_end_date").val($("#end_date").val());
                $("#export_pdf_subdistrict").val($("#subdistrict").val());
                $("#export_pdf_category").val($("#category").val());
                $("#form-export-pdf").submit();
            });

            $("#btn-export-excel").click(function(e) {
                e.preventDefault();

                $("#export_excel_start_date").val($("#start_date").val());
                $("#export_excel_end_date").val($("#end_date").val());
                $("#export_excel_subdistrict").val($("#subdistrict").val());
                $("#export_excel_category").val($("#category").val());
                $("#form-export-excel").submit();
            });


            @include('components.flash-message')
        </script>
    @endpush
@endsection

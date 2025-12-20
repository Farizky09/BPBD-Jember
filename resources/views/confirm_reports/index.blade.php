@extends('layouts.master')
@push('style')
    <style>
        .dataTables_wrapper .dataTables_length {
            float: left;
            margin-right: 10px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        .dataTables_length label {
            display: flex;
            align-items: center;
        }

        .dataTables_length label select {
            margin: 5px;
        }
    </style>
@endpush
@section('main')
@section('breadcrumb')
    @php
        $links = [
            auth()->user()->hasRole('user')
                ? ['name' => 'Home', 'url' => route('dashboard')]
                : ['name' => 'Dashboard', 'url' => route('dashboard')],
            // ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Tindak Lanjut', 'url' => route('confirm-reports.index')],
        ];
    @endphp
    @php
        $title = auth()->user()->hasRole('user') ? 'Manajemen Riwayat' : 'Manajemen Tindak Lanjut';
    @endphp

    <x-breadcrumb :links="$links" :title="$title" />
@endsection
@if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div class="col-md-3">
            <x-input.input-label for="status" class="mb-3" value="Status Tingkat Laporan" />
            <select name="status" id="status"
                class="select2 w-48 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua</option>
                @foreach ($status as $statusdisaster)
                    <option value="{{ $statusdisaster }}">{{ ucfirst($statusdisaster) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <x-input.input type="date" name="start_date" id="start_date" label="Tanggal Awal"></x-input.input>
            {{-- <input type="date" class="form-control" name="start_date" id="start_date"> --}}
        </div>
        <div class="col-md-3">
            <x-input.input type="date" name="end_date" id="end_date" label="Tanggal Akhir"></x-input.input>
            {{-- <input type="date" class="form-control" name="end_date" id="end_date"> --}}
        </div>
        <button type="button" id="buttonFilter"
            class="focus:outline-none text-white bg-green-500  font-medium rounded-lg text-sm px-5 py-2.5 me-2">
            Filter Data
        </button>
    </div>
    <div class="flex flex-wrap items-end gap-3 my-8 mt-20">
        @if (auth()->user()->hasRole('super_admin'))
            <form id="form-export-pdf" action="{{ route('confirm-reports.export-pdf') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <input type="text" name="status" id="export_pdf_status" hidden>
                    <input type="hidden" name="start_date" id="export_pdf_start_date">
                    <input type="hidden" name="end_date" id="export_pdf_end_date">
                    <div class="col-md-2">
                        <button type="submit" id="btn-export-pdf"
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <i class="fa fa-file-pdf mr-2"></i> Download
                        </button>
                    </div>
                </div>
            </form>
            <form id="form-export-excel" action="{{ route('confirm-reports.export-excel') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <input type="text" name="status" id="export_excel_status" hidden>
                    <input type="hidden" name="start_date" id="export_excel_start_date">
                    <input type="hidden" name="end_date" id="export_excel_end_date">
                    <div class="col-md-2">
                        <button type="submit" id="btn-export-excel"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <i class="fa fa-file-excel mr-2"></i> Download
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endif
<div class="container-xl wide-xl">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-content">
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                            data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="nk-block">
        <div class="row g-gs">
            <table id="confirmReportsDataTable" class="table table-bordered" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Laporan</th>
                        <th>Alamat Bencana</th>
                        {{-- <th>Jenis Bencana</th> --}}
                        <th>Status Laporan</th>
                        <th>Tingkat Bencana</th>
                        <th>Catatan Bencana</th>
                        {{-- <th>Admin</th> --}}

                        {{-- @dd(Auth::user()->role) --}}
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            <th>Pengirim</th>
                        @else
                            <th>Admin</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        function btnDelete(_id, _name) {
            let url = '{{ route('confirm-reports.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Laporan " + _name + " akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Data Laporan ' + _name + ' berhasil dihapus.',
                                'success'
                            ).then(() => {
                                $('#confirmReportsDataTable').DataTable().ajax.reload(null,
                                    false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data user ' + _name + ' gagal dihapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        @if (auth()->user()->hasRole('super_admin'))
            {
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');


                startDateInput.addEventListener('change', function() {
                    const startDate = this.value;
                    endDateInput.min = startDate;
                });

            }
        @endif


        function reloadDataTable() {
            $('#confirmReportsDataTable').DataTable().ajax.reload();
        }

        function createDataTable() {
            let url = "{{ route('confirm-reports.index') }}";
            $('#confirmReportsDataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,

                responsive: true,
                scrollX: true,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.status = $('#status').val();
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                    },
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
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'disaster_level',
                        name: 'disaster_level'
                    },
                    {
                        data: 'notes',
                        name: 'notes'
                    },



                    {
                        data: 'admin',
                        name: @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            'report.user.name'
                        @else
                            'admin.name'
                        @endif
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        }
        createDataTable();
        $("#buttonFilter").click(function() {
            reloadDataTable();
        });


        // $('#status').on('change', function() {
        //     $('#confirmReportsDataTable').DataTable().ajax.reload(null, false);
        // });
        $("#btn-export-pdf").click(function(e) {
            e.preventDefault();
            $("#export_pdf_status").val($("#status").val());
            $("#export_pdf_start_date").val($("#start_date").val());
            $("#export_pdf_end_date").val($("#end_date").val());
            $("#form-export-pdf").submit();
        });

        $("#btn-export-excel").click(function(e) {
            e.preventDefault();
            $("#export_excel_status").val($("#status").val());
            $("#export_excel_start_date").val($("#start_date").val());
            $("#export_excel_end_date").val($("#end_date").val());
            $("#form-export-excel").submit();
        });
        @include('components.flash-message')
    </script>
@endpush
@endsection

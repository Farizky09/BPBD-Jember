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
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Berita', 'url' => route('news.index')],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Manajemen Berita" />
@endsection

<div class="container-xl wide-xl">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-content">
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle mb-14">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-wrap items-end gap-3 md:gap-4">
                                <div class="w-full md:w-1/4 lg:w-1/5">
                                    <x-input.input-label for="status" class="mb-3" value="Status Berita" />
                                    <select class="form-control select2 custom-select2-height" name="status"
                                        id="status">
                                        <option value="">Semua</option>
                                        @foreach ($status as $statusnews)
                                            <option value="{{ $statusnews }}">{{ ucfirst($statusnews) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full md:w-1/4 lg:w-1/5">
                                    <x-input.input type="date" name="start_date" id="start_date"
                                        label="Tanggal Awal" />
                                </div>
                                <div class="w-full md:w-1/4 lg:w-1/5">
                                    <x-input.input type="date" name="end_date" id="end_date"
                                        label="Tanggal Akhir" />
                                </div>
                                <div class="flex-shrink-0">
                                    <button type="button" id="buttonFilter"
                                        class="focus:outline-none text-white bg-green-500 hover:bg-green-600 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-150 ease-in-out">
                                        Filter Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end w-full gap-3 my-8 mt-20">
                        <a href="{{ route('news.create') }}">
                            <x-button.button-success>Tambah</x-button.button-success>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="nk-block">
        <div class="row g-gs">
            <table id="newsDataTable" class="table table-bordered" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Laporan</th>
                        <th>Judul Berita</th>
                        <th>slug</th>
                        <th>Status</th>
                        <th>Tanggal Publish</th>
                        <th>Tanggal Takedown</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        function btnDelete(_id, _tittle) {
            let url = '{{ route('news.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Laporan " + _tittle + " akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
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
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Data Laporan ' + _tittle + ' berhasil dihapus.',
                                'success'
                            ).then(() => {
                                $('#newsDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data user ' + _tittle + ' gagal dihapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function btnPublish(_id, _tittle) {
            let url = '{{ route('news.publish', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Berita " + _tittle + " akan dipublish!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, publish!',
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
                        type: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Data Laporan ' + _tittle + ' berhasil dipublish.',
                                'success'
                            ).then(() => {
                                $('#newsDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data user ' + _tittle + ' gagal dipublish.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function btnTakedown(_id, _tittle) {
            let url = '{{ route('news.takedown', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Berita " + _tittle + " akan ditakedown!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, takedown!',
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
                        type: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Data Laporan ' + _tittle + ' berhasil ditakedown.',
                                'success'
                            ).then(() => {
                                $('#newsDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data user ' + _tittle + ' gagal ditakedown.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');


        startDateInput.addEventListener('change', function() {
            const startDate = this.value;
            endDateInput.min = startDate;
        });

        function reloadDataTable() {
            $('#newsDataTable').DataTable().ajax.reload();
        }

        function createDataTable() {
            let url = "{{ route('news.index') }}";
            $('#newsDataTable').DataTable({
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
                        searchable: false,
                    },
                    {
                        data: 'kd_report',
                        name: 'confirmReports.report.kd_report'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },

                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'published_at',
                        name: 'published_at',
                    },
                    {
                        data: 'takedown_at',
                        name: 'takedown_at'
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
        $('#buttonFilter').on('click', function() {
            reloadDataTable();
        });
        // $('#status').on('change', function() {
        //     $('#newsDataTable').DataTable().ajax.reload(null, false);
        // });
        // console.log(ajax, 'p');
        @include('components.flash-message')
    </script>
@endpush
@endsection

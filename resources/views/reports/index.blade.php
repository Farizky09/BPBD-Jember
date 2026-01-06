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
            ['name' => 'Manajemen Kelola Laporan', 'url' => route('reports.index')],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Manajemen Kelola Laporan" />
@endsection
<div class="container-xl wide-xl">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-content">
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                                class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                                <li>
                                    <a href="{{ route('reports.create') }}">
                                        <x-button.button-success>Tambah</x-button.button-success>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="nk-block">
        <div class="row g-gs">
            <table id="reportsDataTable" class="table table-bordered" style="width: 100%;">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Laporan</th>
                        <th>Kecamatan</th>
                        <th>Alamat Bencana</th>
                        <th>Status</th>

                        {{-- @dd(Auth::user()->role) --}}
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            <th>Pengirim</th>
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
        function btnDelete(_id, _kd_report) {
            let url = '{{ route('reports.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Laporan " + _kd_report + " akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
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
                                'Data Laporan ' + _kd_report + ' berhasil dihapus.',
                                'success'
                            ).then(() => {
                                $('#reportsDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Gagal!',
                                'Data user ' + _kd_report + ' gagal dihapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function btnProcess(_id, _kd_report) {
            let url = '{{ route('reports.process', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Laporan \"" + _kd_report + "\" akan diproses!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, proses!',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
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
                                'Data Laporan ' + _kd_report + ' berhasil diproses.',
                                'success'
                            ).then(() => {
                                $('#reportsDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Gagal!',
                                'Data Laporan ' + _kd_report + ' gagal diproses.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        $(function() {
            let url = "{{ route('reports.index') }}";
            $('#reportsDataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                scrollX: true,
                ajax: url,
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
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'kd_report',
                        name: 'kd_report'
                    },
                    {
                        data: 'subdistrict',
                        name: 'subdistrict'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                        {
                            data: 'pengirim',
                            name: 'user.user_name'
                        },
                    @endif {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
        // console.log(ajax, 'p');

        @include('components.flash-message')
    </script>
    @if (session('incomplete_profile'))
        <script>
            Swal.fire({
                title: 'Data Tidak Lengkap',
                text: 'Lengkapi profil Anda sebelum membuat laporan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ke Profil',
                cancelButtonText: 'Nanti'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('profile.edit') }}";
                }
            });
        </script>
    @endif
@endpush
@endsection

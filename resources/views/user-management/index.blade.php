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

        .filter-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-item {
            min-width: 150px;
        }

        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column;
            }

            .filter-item {
                width: 100%;
            }
        }
    </style>
@endpush
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Pengguna', 'url' => route('user-management.index')],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Manajemen Pengguna" />
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
                            <ul class="nk-block-tools g-3"
                                style="display: flex; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
                                <li>
                                    <a href="{{ route('user-management.create') }}">
                                        <x-button.button-success>Tambah</x-button.button-success>
                                    </a>
                                </li>
                                <li>
                                    <div class="filter-container">
                                        <div class="filter-item">
                                            <label for="start_date" class="form-label">Tanggal Awal</label>
                                            <x-input.input type="date" name="start_date"
                                                id="start_date"></x-input.input>
                                        </div>
                                        <div class="filter-item">
                                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                                            <x-input.input type="date" name="end_date"
                                                id="end_date"></x-input.input>
                                        </div>
                                        <div class="filter-item">
                                            <label for="status" class="form-label">Status</label>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Semua Status</option>
                                                @foreach ($statuses as $statusOption)
                                                    <option value="{{ $statusOption }}">
                                                        {{ ucfirst($statusOption) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <label for="role" class="form-label">Role</label>
                                            <select name="role" id="role" class="form-control">
                                                <option value="">Semua Role</option>
                                                @foreach ($roles as $roleOption)
                                                    <option value="{{ $roleOption }}">
                                                        {{ str_replace('_', ' ', ucwords($roleOption)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="filter-item">
                                            <button type="button" id="buttonFilter" class="btn btn-primary"
                                                style="height: 38px;">
                                                Filter Data
                                            </button>
                                            <button type="button" id="buttonReset" class="btn btn-secondary"
                                                style="height: 38px; margin-left: 5px;">
                                                Reset
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="row g-gs">
                <table id="userDataTable" class="table table-bordered" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Nomor Telepon</th>
                            <th>Role</th>
                            <th>Status</th>
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
        function btnDelete(_id, _name) {
            let url = '{{ route('user-management.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data user " + _name + " akan dihapus!",
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
                                'Data user ' + _name + ' berhasil dihapus.',
                                'success'
                            ).then(() => {
                                $('#userDataTable').DataTable().ajax.reload(null, false);
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

        function btnReset(_id, _name) {
            let url = '{{ route('user-management.reset-password', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Password user " + _name + " akan direset!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, reset!',
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
                        type: "GET",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'Password user ' + _name + ' berhasil direset.',
                                'success'
                            ).then(() => {
                                $('#userDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Password user ' + _name + ' gagal direset.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function btnBanUser(_id, _name) {
            let url = '{{ route('user-management.banUser', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "User " + _name + " akan diblokir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, blokir!',
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
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'User ' + _name + ' berhasil diblokir.',
                                'success'
                            ).then(() => {
                                $('#userDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'User ' + _name + ' gagal diblokir.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        function btnUnbanUser(_id, _name) {
            let url = '{{ route('user-management.unBanUser', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "User " + _name + " akan dibuka blokir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, unban!',
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
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire(
                                'Berhasil!',
                                'User ' + _name + ' berhasil dibuka blokir.',
                                'success'
                            ).then(() => {
                                $('#userDataTable').DataTable().ajax.reload(null, false);
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'User ' + _name + ' gagal dibuka blokir.',
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
            $('#userDataTable').DataTable().ajax.reload();
        }

        function createDataTable() {
            let url = '{{ route('user-management.index') }}';
            $('#userDataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                scrollX: true,
                ajax: {
                    url: url,
                    data: function(d) {
                        d.start_date = $('#start_date').val();
                        d.end_date = $('#end_date').val();
                        d.status = $('#status').val();
                        d.role = $('#role').val();
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
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'role',
                        name: 'role',
                    },
                    {
                        data: 'status',
                        name: 'status'
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

        $('#buttonReset').on('click', function() {
            $('#start_date').val('');
            $('#end_date').val('');
            $('#status').val('');
            $('#role').val('');
            reloadDataTable();
        });

        $('.filter-item input, .filter-item select').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                reloadDataTable();
            }
        });

        @include('components.flash-message')
    </script>
@endpush
@endsection

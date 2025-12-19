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
            ['name' => 'Manajemen Permission', 'url' => route('permission.index')],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Manajemen Permission" />
@endsection

<div class="container-xl wide-xl">
    <div class="nk-content-body ">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-content">
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                                class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                                <li>
                                    <a href="{{ route('permission.create') }}">
                                        <x-button.button-success>Tambah</x-button.button-success>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="row g-gs">
                <table id="permissionDataTable" class="table table-bordered" style="width: 100%;">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
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
            let url = '{{ route('permission.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data Permission " + _name + " akan dihapus!",
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
                                $('#permissionDataTable').DataTable().ajax.reload(null, false);
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

        $(function() {
            let url = '{{ route('permission.index') }}';
            $('#permissionDataTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
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
                        data: 'name',
                        name: 'name'
                    },
                    {
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
@endpush
@endsection

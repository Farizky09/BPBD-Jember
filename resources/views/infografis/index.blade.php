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
        .nav-pills .nav-item .nav-link.active {
            background-color: #007bff;
            color: white;
        }
    </style>
@endpush
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Informasi dan Infografis', 'url' => route('infografis.index')],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Manajemen Informasi dan Infografis" class="text-center" />
@endsection
<div class="container-xl wide-xl">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="toggle-wrap nk-block-tools-toggle">
                <ul class="nav nav-pills " id="recipe-status-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-status="head_image" type="button">Informasi BPBD</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="infografis_jember" type="button">Infografis Bulanan</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-status="infografis_raung" type="button">Infografis Bencana</button>
                    </li>
                </ul>
                <div class="toggle-expand-content" data-contet="pageMenu">
                    <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                        <li>
                            <a href="{{ route('infografis.create') }}">
                                <x-button.button-success>Tambah</x-button.button-success>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="nk-block">
            <div class="row g-gas">
                <table id="infografisTable" class="table table-bordered" style="width: 100%">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Gambar</th>
                            <th>Tipe Informasi</th>
                            <th>Tanggal</th>
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
            let url = '{{ route('infografis.delete', ':id') }}'.replace(':id', _id);
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data " + _name + " akan dihapus!",
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
                                'Data' + _name + ' berhasil dihapus.',
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        },
                        error: function(response) {
                            Swal.fire(
                                'Gagal!',
                                'Data' + _name + ' gagal dihapus.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        let infografisTable; 
        $(function() {
            const defaultCategory = 'head_image';
            $(`button[data-status="${defaultCategory}"]`).addClass('active');

            infografisTable = $('#infografisTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                scrollX: true,
                ajax: {
                    url: '{{ route('infografis.index') }}',
                    data: function(d) {
                        d.category = $('.nav-link.active').data('status'); 
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
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'category_image', 
                        name: 'category_image'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            $('#recipe-status-tab button.nav-link').on('click', function() {
             
                $('#recipe-status-tab button.nav-link').removeClass('active');
               
                $(this).addClass('active');
                infografisTable.ajax.reload();
            });
        });
        @include('components.flash-message')
    </script>
@endpush
@endsection

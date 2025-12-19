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
<div class="nk-content nk-content-fluid">
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
                                    <li>
                                        <a href="{{ route('laporan.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus"></i> Tambah User
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
                    <table id="userDataTable" class="table table-bordered" style="width: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Bencana</th>
                                <th>Gambar</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


    @push('scripts')
        <script>
            function btnDelete(_id, _name) {
                let url = '{{ route('laporan.delete', ':id') }}'.replace(':id', _id);
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data user " + _name + " akan dihapus!",
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


            $(function() {
                let url = '{{ route('laporan.index') }}';
                $('#userDataTable').DataTable({
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
                            data: 'nama_bencana',
                            name: 'nama_bencana'
                        },
                        {
                            data: 'gambar',
                            name: 'gambar'
                        },
                        {
                            data: 'keterangan_bencana',
                            name: 'keterangan_bencana',
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

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
        $links = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Penanganan', 'url' => '']];
    @endphp
    <x-breadcrumb :links="$links" title="Penanganan" class="text-center" />
@endsection
@section('main')
    <div class="container-xl wide-xl">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="toggle-wrap nk-block-tools-toggle">
                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                            class="icon ni ni-more-v"></em>
                    </a>
                    <div class="toggle-expand-content" data-contet="pageMenu">
                        <ul class="nk-block-tools g-3" style="display: flex; justify-content: flex-end;">
                            {{-- <li>
                                <a href="{{ route('disaster_impacts.create') }}">
                                    <x-button.button-success>Tambah</x-button.button-success>

                                </a>
                            </li> --}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="nk-block">
                <div class="row g-gas">
                    <table id="disasterImpactsTable" class="table table-bordered" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode laporan</th>
                                <th>Alamat</th>
                                <th>Jenis Bencana</th>
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
                let url = '{{ route('disaster_impacts.delete', ':id') }}'.replace(':id', id);
                Swal.fire({
                    title: 'apakah anda yakin?',
                    text: 'Data Penanganan ' + kd_report + ' akan dihapus!',
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
                                    'Data Penanganan Bencana ' + kd_report + ' berhasil dihapus',
                                    'success'
                                ).then(() => {
                                    $('#disasterImpactsTable').DataTable().ajax.reload(null, false);
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagall!',
                                    'Data Penanganan Bencana' + kd_report + 'gagal dihapus',
                                    'error'
                                );
                            }
                        })
                    }
                })
            }
            $(function() {
                let url = '{{ route('disaster_impacts.index') }}';
                $('#disasterImpactsTable').DataTable({
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
                            name: 'address',
                        },
                        {
                            data: 'disaster_category',
                            name: 'disaster_category',
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]

                });
            });
            @include('components.flash-message')
        </script>
    @endpush
@endsection

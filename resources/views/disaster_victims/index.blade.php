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
        $links = [['name' => 'Dashboard', 'url' => route('dashboard')], ['name' => 'Data Korban', 'url' => '']];
    @endphp
    <x-breadcrumb :links="$links" title="Data Korban" class="text-center" />
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
                    <table id="disasterVictimsDataTable" class="table table-bordered" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Penanganan</th>
                                <th>Nama Lengkap</th>
                                <th>NIK</th>
                                <th>KK</th>
                                <th>Jenis Kelamin</th>
                                <th>Usia</th>
                                {{-- <th>Status Keluarga</th> --}}
                                {{-- <th>No. Telepon</th> --}}
                                {{-- <th>Kelompok Rentan</th> --}}
                                {{-- <th>Tempat Lahir</th>
                                <th>Tanggal Lahir</th> --}}
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
            function btnDelete(id, name) {
                let url = '{{ route('disaster_victims.delete', ':id') }}'.replace(':id', id);
                Swal.fire({
                    title: 'apakah anda yakin?',
                    text: 'Data Korban ' + name + ' akan dihapus!',
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
                                    'Data Korban Bencana ' + name + ' berhasil dihapus',
                                    'success'
                                ).then(() => {
                                    $('#disasterVictimsDataTable').DataTable().ajax.reload(null,
                                        false);
                                });
                            },
                            error: function(response) {
                                Swal.fire(
                                    'Gagall!',
                                    'Data Korban Bencana' + name + 'gagal dihapus',
                                    'error'
                                );
                            }
                        })
                    }
                })
            }
            $(function() {
                let url = '{{ route('disaster_victims.index') }}';
                $('#disasterVictimsDataTable').DataTable({
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
                            data: 'kd_disaster_impacts',
                            name: 'kd_disaster_impacts'
                        },

                        {
                            data: 'fullname',
                            name: 'fullname'
                        },
                        {
                            data: 'nik',
                            name: 'nik'
                        },
                        {
                            data: 'kk',
                            name: 'kk'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'age',
                            name: 'age'
                        },
                        // {
                        //     data: 'family_status',
                        //     name: 'family_status'
                        // },
                        // {
                        //     data: 'phone_number',
                        //     name: 'phone_number'
                        // },
                        // {
                        //     data: 'vulnerable_group',
                        //     name: 'vulnerable_group'
                        // },

                        // {
                        //     data: 'birth_place',
                        //     name: 'birth_place'
                        // },
                        // {
                        //     data: 'birth_date',
                        //     name: 'birth_date'
                        // },
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

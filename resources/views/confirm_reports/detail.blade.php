@extends('layouts.master')
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Tindak Lanjut', 'url' => route('confirm-reports.index')],
            ['name' => 'Detail', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Detail Tindak Lanjut" class="text-center" />
@endsection
<div class="flex flex-wrap -mx-8 justify-start">
    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
            <x-input.input label="Kode Laporan" name="kd_report" id="kd_report" placeholder="Contoh: Banjir"
                value="{{ $data->report->kd_report }}" disabled="true"></x-input.input>

        </div>

        <div class="form-group mb-4">
            <x-input.input label="Nama Pelapor" name="reporter_name" id="reporter_name"
                value="{{ $data->report->user->name }}" disabled="true"></x-input.input>
            <x-button.button-submit class="mt-2 px-5 py-2.5" data-bs-toggle="modal"
                data-bs-target="#modalPelapor">Detail Pelapor</x-button.button-submit>
        </div>

        {{-- @if (Auth::user()->hasRole(['admin', 'super_admin']))
            <div class="form-group mb-4">
                <x-input.input label="Point" value="{{ $data->report->user->poin }}" disabled="true"></x-input.input>

            </div>
        @endif --}}

        <div class="form-group mb-4">
            <x-input.input label="Jenis Bencana" value="{{ $data->report->disasterCategory->name }}"
                readonly></x-input.input>

        </div>
        <div class="form-group mb-4">
            <x-input.input-textarea label="Deskripsi" name="description" id="description" rows="5"
                placeholder="Jelaskan secara detail" value="{{ $data->report->description }}" disabled="true" />
        </div>

    </div>
    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
            <x-input.input label="Status" value="{{ $data->status }}" disabled="true"></x-input.input>

        </div>
        <div class="form-group mb-4">
            <x-input.input label="Tanggal Laporan di Proses" name="date" id="date"
                value="{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}" disabled="true">
            </x-input.input>
        </div>
        <div class="form-group mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold">Alamat</label>
            <p>{{ $data->report->address }}</p>
            <iframe
                src="https://maps.google.com/maps?q={{ $data->report->latitude }},{{ $data->report->longitude }}&hl=en&z=14&amp;output=embed"
                width="100%" height="230" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
    </div>
    <div class="w-full md:w-1/2 px-4">

    </div>

    <div class="w-full px-4">
        <div class="form-group mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Gambar
                Bencana:</label>
            <div class="flex flex-wrap">
                @foreach ($data->report->images as $image)
                    <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="bukti-pembayaran"
                        data-title="Gambar Bencana" class="h-auto">
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Bencana"
                            class="mt-2 mr-4 mb-4 rounded-lg object-cover w-[200px] h-[200px]">
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if ($data->status == 'proses')
    @hasrole(['admin', 'super_admin'])
        <div class="flex justify-center items-center gap-x-4 mt-8 mb-4 px-5">
            <button id="btn_accept" onclick="showAcceptModal()" type="button"
                class="bg-blue-500 w-32 rounded-md px-5 py-2.5 text-white font-bold focus:outline-none">
                Terima
            </button>

            <button id="btn_reject" onclick="showRejectModal()" type="button"
                class="bg-red-500 w-32 rounded-md px-5 py-2.5 text-white font-bold focus:outline-none">
                Tolak
            </button>
        </div>

    @endrole
@endif



<div class="hidden modal fade" id="acceptModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="{{ route('confirm-reports.accept', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Tingkat
                            Bencana:</label>
                        <select name="disaster_level" class="w-full select2 border rounded py-2 px-3" required>
                            @foreach ($disaster_level as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alasan:</label>
                        <textarea name="notes" class="w-full border rounded py-2 px-3 h-32" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeAcceptModal()"
                            class="mr-2 px-4 py-2 bg-red-500 text-white rounded-md">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Konfirmasi
                            Penerimaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="hidden modal fade" id="rejectModal" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <form method="POST" action="{{ route('confirm-reports.reject', $data->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alasan Penolakan:</label>
                        <textarea name="notes" class="w-full border rounded py-2 px-3 h-32" required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" onclick="closeRejectModal()"
                            class="mr-2 px-4 py-2 bg-gray-500 text-white rounded-md">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md">Konfirmasi
                            Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="modalPelapor" tabindex="-1" aria-labelledby="modalPelaporLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-xl shadow-lg">
            <div class="modal-header bg-primary text-white rounded-t-xl">
                <h5 class="modal-title font-semibold" id="modalPelaporLabel">Detail Pelapor</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Tutup"></button>
            </div>
            <div class="modal-body flex flex-col md:flex-row gap-6 items-center md:items-start p-6">
                <div class="flex flex-col items-center w-full md:w-1/3">
                    <img src="{{ $data->report->user->image_avatar ? asset('storage/' . $data->report->user->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                        alt="Foto Pelapor"
                        class="w-32 h-32 object-cover rounded-full border-4 border-blue-500 shadow-md">
                    <p class="mt-3 fon<t-semibold text-gray-800">{{ $data->report->user->name }}</p>
                </div>
                <div class="flex-grow w-full md:w-2/3">
                    <ul class="list-group list-group-flush space-y-3">
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Nama Lengkap:</span>
                            <span class="ml-2 text-gray-900">{{ $data->report->user->name }}</span>
                        </li>
                        {{-- <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Username:</span>
                            <span class="ml-2 text-gray-900">{{ $data->report->user->username }}</span>
                        </li> --}}
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Email:</span>
                            <span class="ml-2 text-gray-900">{{ $data->report->user->email }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">No Telp:</span>
                            <span class="ml-2 text-gray-900">{{ $data->report->user->phone_number }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">NIK:</span>
                            <span class="ml-2 text-gray-900">
                                {{ $data->report->user->nik ? $data->report->user->nik : 'Tidak ada data' }}
                            </span>
                        </li>
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                                <span class="font-bold text-gray-700 w-40">Poin:</span>
                                <span class="ml-2 text-gray-900">
                                    {{ $data->report->user->poin ? $data->report->user->poin : 'Tidak ada data' }}
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row items-start">
                            <span class="font-bold text-gray-700 w-40">Foto Identitas:</span>
                            <a href="{{ $data->report->user->photo_identity_path ? asset('storage/' . $data->report->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                                target="_blank">
                                <img src="{{ $data->report->user->photo_identity_path ? asset('storage/' . $data->report->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                                    alt="Foto Identitas"
                                    class="rounded-lg object-fill w-[400px] h-[150px] ml-2 hover:opacity-80 transition">
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Fungsi untuk modal Accept
        function showAcceptModal() {
            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                text: "Apakah Anda yakin ingin menerima laporan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#acceptModal').modal('show');
                }
            }).catch((error) => {
                console.error('SweetAlert error:', error);
            });
        }

        function closeAcceptModal() {
            $('#acceptModal').modal('hide');
        }

        // Fungsi untuk modal Reject
        function showRejectModal() {
            Swal.fire({
                title: 'Konfirmasi Penolakan',
                text: "Apakah Anda yakin ingin menolak laporan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#rejectModal').modal('show');
                }
            }).catch((error) => {
                console.error('SweetAlert error:', error);
            });
        }

        function closeRejectModal() {
            // Menutup modal menggunakan Bootstrap
            $('#rejectModal').modal('hide');
        }

        function showNetralModal() {
            Swal.fire({
                title: 'Konfirmasi Penetralan',
                text: "Apakah Anda yakin ingin Netralkan laporan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
                },
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#netralModal').modal('show');
                }
            }).catch((error) => {
                console.error('SweetAlert error:', error);
            });
        }

        function closeNetralModal() {
            // Menutup modal menggunakan Bootstrap
            $('#netralModal').modal('hide');
        }
    </script>
@endpush
@endsection

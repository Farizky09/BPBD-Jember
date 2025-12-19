@extends('layouts.master')
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Laporan', 'url' => route('reports.index')],
            ['name' => 'Detail', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Detail Laporan" class="text-center" />
@endsection

<div class="flex flex-wrap -mx-8 justify-start">
    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
            <x-input.input label="Kode Laporan" name="kd_report" id="kd_report" placeholder="Contoh: Banjir"
                value="{{ $data->kd_report }}" disabled="true"></x-input.input>
        </div>

        <div class="form-group mb-4">
            <x-input.input label="Nama Pelapor" name="reporter_name" id="reporter_name" value="{{ $data->user->name }}"
                disabled="true"></x-input.input>
            @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                <x-button.button-submit class="mt-2 px-5 py-2.5 " onclick="$('#modalPelapor').modal('show')">Lihat Detail
                    Pelapor</x-button.button-submit>
            @endif
        </div>

        <div class="form-group mb-4">
            <x-input.input label="Jenis Bencana" name="ida_category" id="ida_category"
                value="{{ $data->disasterCategory->name }}" disabled="true"></x-input.input>

        </div>
        <div class="form-group mb-4">
            <x-input.input-textarea label="Deskripsi" name="description" id="description" rows="5"
                placeholder="Jelaskan secara detail" value="{{ $data->description }}" disabled="true" />
        </div>

    </div>

    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
            <x-input.input label="Status" name="status" id="status" value="{{ $data->status }}"
                disabled="true"></x-input.input>

        </div>

        <div class="form-group mb-4">
            <x-input.input label="Tanggal Laporan dibuat" name="date" id="date"
                value="{{ \Carbon\Carbon::parse($data->created_at)->translatedFormat('l, d F Y') }}" disabled="true">
            </x-input.input>
        </div>

        <div class="form-group mb-4">
            <x-input.input-label value="Alamat" class="mb"></x-input.input-label>
            <p>{{$data->address}}</p>
            <iframe
                src="https://maps.google.com/maps?q={{ $data->latitude }},{{ $data->longitude }}&hl=en&z=14&amp;output=embed"
                width="100%" height="230" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

        </div>
    </div>
    <div class="w-full px-4">
        <div class="form-group mb-4">
            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Gambar
                Bencana:</label>
            <div class="flex flex-wrap">
                @foreach ($data->images as $image)
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
<!-- Modal -->
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
                    <img src="{{ $data->user->image_avatar ? asset('storage/' . $data->user->image_avatar) : asset('assets/img/avatar/a-sm.jpg') }}"
                        alt="Foto Pelapor"
                        class="w-32 h-32 object-cover rounded-full border-4 border-blue-500 shadow-md">
                    <p class="mt-3 fon<t-semibold text-gray-800">{{ $data->user->name }}</p>
                </div>
                <div class="flex-grow w-full md:w-2/3">
                    <ul class="list-group list-group-flush space-y-3">
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Nama Lengkap:</span>
                            <span class="ml-2 text-gray-900">{{ $data->user->name }}</span>
                        </li>
                        {{-- <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Username:</span>
                            <span class="ml-2 text-gray-900">{{ $data->user->username }}</span>
                        </li> --}}
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">Email:</span>
                            <span class="ml-2 text-gray-900">{{ $data->user->email }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">No Telp:</span>
                            <span class="ml-2 text-gray-900">{{ $data->user->phone_number }}</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                            <span class="font-bold text-gray-700 w-40">NIK:</span>
                            <span class="ml-2 text-gray-900">
                                {{ $data->user->nik ? $data->user->nik : 'Tidak ada data' }}
                            </span>
                        </li>
                        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin'))
                            <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row">
                                <span class="font-bold text-gray-700 w-40">Poin:</span>
                                <span class="ml-2 text-gray-900">
                                    {{ $data->user->poin ? $data->user->poin : 'Tidak ada data' }}
                                </span>
                            </li>
                        @endif
                        <li class="list-group-item bg-transparent px-0 flex flex-col md:flex-row items-start">
                            <span class="font-bold text-gray-700 w-40">Foto Identitas:</span>
                            <a href="{{ $data->user->photo_identity_path ? asset('storage/' . $data->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
                                target="_blank">
                                <img src="{{ $data->user->photo_identity_path ? asset('storage/' . $data->user->photo_identity_path) : asset('assets/img/avatar/dok.png') }}"
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
@endpush
@endsection

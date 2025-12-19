@extends('layouts.master')
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Dokumen Laporan Bencana', 'url' => route('disaster_report_documentations.index')],
            ['name' => 'Detail', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Detail Laporan Bencana" class="text-center" />
@endsection

<div class="flex flex-wrap -mx-8 justify-start">
    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
                                              <div class="form-group mb-4">
                            <label for="confirm_report_id" class="block text-gray-700 text-sm font-bold mb-2">Kode Laporan</label>
                            <input
                                type="text"
                                class="bg-gray-50 px-4 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-primary focus:border-primary block w-full p-2"`
                                id="confirm_report_id"
                                name="confirm_report_id"
                                value="{{ optional($confirmReports->where('id', $data->confirm_report_id)->first())->report->kd_report ?? '-' }}"
                                disabled>
                        </div>
        </div>


        <div class="form-group mb-4">
            <x-input.input-textarea label="Kronologi Bencana" name="disaster_chronology" id="disaster_chronology"
                rows="9" value="{{ strip_tags($data->disaster_chronology) }}" disabled="true" />

        </div>
    </div>

    <div class="w-full md:w-1/2 px-4">
        <div class="form-group mb-4">
            <x-input.input-textarea label="Dampak Bencana" name="disaster_impact" id="disaster_impact" rows="5"
                value="{{ strip_tags($data->disaster_impact) }}" disabled="true" />

        </div>
        <div class="form-group mb-4">
            <x-input.input-textarea label="Respon Bencana" name="disaster_response" id="disaster_response"
                rows="5" value="{{ strip_tags($data->disaster_response) }} " disabled="true" />
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

@push('scripts')
@endpush
@endsection

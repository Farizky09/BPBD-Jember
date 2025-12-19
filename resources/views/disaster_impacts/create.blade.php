@extends('layouts.master')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Tindak Lanjut', 'url' => route('confirm-reports.index')],
            ['name' => 'Tambah Penanganan', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Penanganan" class="text-center" />
@endsection
@section('main')
    <div class=" mx-auto">
        <form id="form-disasterImpacts" action="{{ route('disaster_impacts.store') }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="confirm_report_id" value="{{ $confirmReports->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input.input label="Rumah Rusak Ringan" name="lightly_damaged_houses" id="lightly_damaged_houses"
                    value="{{ old('lightly_damaged_houses') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Rumah Rusak Sedang" name="moderately_damaged_houses" id="moderately_damaged_houses"
                    value="{{ old('moderately_damaged_houses') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Rumah Rusak Berat" name="heavily_damaged_houses" id="heavily_damaged_houses"
                    value="{{ old('heavily_damaged_houses') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Fasilitas Umum Rusak" name="damaged_public_facilities"
                    id="damaged_public_facilities" value="{{ old('damaged_public_facilities') }}" type="number"
                    min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Orang Hilang" name="missing_people" id="missing_people"
                    value="{{ old('missing_people') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Orang Terluka" name="injured_people" id="injured_people"
                    value="{{ old('injured_people') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                {{-- <x-input.input label="Total Orang Terdampak" name="affected_people" id="affected_people"
                    value="{{ old('affected_people') }}" type="number" min="0" /> --}}

                <x-input.input label="Orang Meninggal" name="deceased_people" id="deceased_people"
                    value="{{ old('deceased_people') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>
                <x-input.input label="Balita" name="affected_babies" id="affected_babies"
                    value="{{ old('affected_babies') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

                <x-input.input label="Lansia" name="affected_elderly" id="affected_elderly"
                    value="{{ old('affected_elderly') }}" type="number" min="0" required="true"  placeholder="Harap isi 0 jika tidak ada data"/>
                <x-input.input label="Disabilitas" name="affected_disabled" id="affected_disabled"
                    value="{{ old('affected_disabled') }}" type="number" min="0" required="true"  placeholder="Harap isi 0 jika tidak ada data"/>
                <x-input.input label="Ibu Hamil" name="affected_pregnant_women" id="affected_pregnant_women"
                    value="{{ old('affected_pregnant_women') }}" type="number" min="0" required="true"  placeholder="Harap isi 0 jika tidak ada data"/>
                <x-input.input label="Umum" name="affected_general" id="affected_general"
                    value="{{ old('affected_general') }}" type="number" min="0" required="true" placeholder="Harap isi 0 jika tidak ada data"/>

            </div>

            <div class="mt-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Penanganan
                </label>
                <textarea name="description" id="description" class="summernote shadow border rounded w-full text-gray-700"
                    placeholder="Kronologi penanganan">{{ old('description') }}</textarea>
            </div>
            <div class="mt-6">
                <label for="logistic_aid_description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi Bantuan Logistik
                </label>
                <textarea name="logistic_aid_description" id="logistic_aid_description"
                    class="summernote shadow border rounded w-full text-gray-700"
                    placeholder="Jenis dan jumlah bantuan logistik yang diberikan">{{ old('logistic_aid_description') }}</textarea>
            </div>



            <div class="flex justify-end mt-8">
                <x-button.button-submit type="submit" class="px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700">
                    Simpan Penanganan
                </x-button.button-submit>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
        <script>
            $('.summernote').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            document.getElementById('form-disasterImpacts').addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Data Penanganan akan disimpan',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Simpan',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    customClass: {
                        popup: 'swal2-border-radius',
                        confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                    },
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection

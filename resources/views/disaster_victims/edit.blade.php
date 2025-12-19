@extends('layouts.master')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Penanganan', 'url' => route('disaster_impacts.index')],
            ['name' => 'Edit Korban', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Edit Korban" class="text-center" />
@endsection
@section('main')
    <div class=" mx-auto">
        <form id="form-disasterVictims" action="{{ route('disaster_victims.update', $data->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="disaster_impact_id" value="{{ $data->disaster_impact_id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-input.input label="Nama Lengkap" name="fullname" id="fullname"
                    value="{{ old('fullname', $data->fullname) }}" type="text" required />

                <x-input.input label="NIK" name="nik" id="nik" value="{{ old('nik', $data->nik) }}"
                    type="text" maxlength="16" required />

                <x-input.input label="No. KK" name="kk" id="kk" value="{{ old('kk', $data->kk) }}"
                    type="text" maxlength="16" required />

                <div class="mb-4">
                    <label for="gender" class="block text-gray-700 font-bold mb-2">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="select2 form-select w-full rounded border-gray-300"
                        required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender', $data->gender) == 'male' ? 'selected' : '' }}>Laki-laki
                        </option>
                        <option value="female" {{ old('gender', $data->gender) == 'female' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                </div>

                <x-input.input label="Usia" name="age" id="age" value="{{ old('age', $data->age) }}"
                    type="number" min="0" required />

                <div class="mb-4">
                    <label for="family_status" class="block text-gray-700 font-bold mb-2">Status Keluarga</label>
                    <select name="family_status" id="family_status"
                        class="select2 form-select w-full rounded border-gray-300" required>
                        <option value="">Pilih Status Keluarga</option>
                        <option value="ayah" {{ old('family_status', $data->family_status) == 'ayah' ? 'selected' : '' }}>
                            Ayah</option>
                        <option value="ibu" {{ old('family_status', $data->family_status) == 'ibu' ? 'selected' : '' }}>
                            Ibu</option>
                        <option value="anak"
                            {{ old('family_status', $data->family_status) == 'anak' ? 'selected' : '' }}>Anak</option>
                    </select>
                </div>

                <x-input.input label="No. Telepon" name="phone_number" id="phone_number"
                    value="{{ old('phone_number', $data->phone_number) }}" type="text" />

                <x-input.input label="Tempat Lahir" name="birth_place" id="birth_place"
                    value="{{ old('birth_place', $data->birth_place) }}" type="text" required />

                <x-input.input label="Tanggal Lahir" name="birth_date" id="birth_date"
                    value="{{ old('birth_date', $data->birth_date) }}" type="date" required />

                <div class="mb-4">
                    <label for="vulnerable_group" class="block text-gray-700 font-bold mb-2">Status Keluarga</label>
                    <select name="vulnerable_group" id="vulnerable_group"
                        class="select2 form-select w-full rounded border-gray-300" required>
                        <option value="">Pilih Status Keluarga</option>
                        <option value="general"
                            {{ old('vulnerable_group', $data->vulnerable_group) == 'general' ? 'selected' : '' }}>
                            Umum</option>
                        <option value="babies"
                            {{ old('vulnerable_group', $data->vulnerable_group) == 'babies' ? 'selected' : '' }}>
                            Bayi/Balita</option>
                        <option value="elderly"
                            {{ old('vulnerable_group', $data->vulnerable_group) == 'elderly' ? 'selected' : '' }}>
                            Lansia</option>
                        <option value="disabled"
                            {{ old('vulnerable_group', $data->vulnerable_group) == 'disabled' ? 'selected' : '' }}>
                            Disabilitas</option>
                        <option value="pregnant_women"
                            {{ old('vulnerable_group', $data->vulnerable_group) == 'pregnant_women' ? 'selected' : '' }}>
                            Ibu Hamil</option>
                    </select>
                </div>
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">Jenis Dampak</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @php

                            $selectedTypes = old('impact_types', $selectedImpactTypes ?? []);

                            if (is_string($selectedTypes)) {
                                $selectedTypes = json_decode($selectedTypes, true) ?? [];
                            }
                        @endphp

                        @foreach ($disasterImpactType as $type)
                            @php
                                $label = $disasterImpactsTypelabel[$type->name] ?? $type->name;
                                $isChecked = in_array($type->name, $selectedTypes);
                            @endphp
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="impact_types[]" value="{{ $type->name }}"
                                    class="form-checkbox rounded" {{ $isChecked ? 'checked' : '' }}>
                                <span class="ml-2">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('impact_types')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
    </div>

    <div class="flex justify-end mt-28">
        <x-button.button-submit type="submit" class="px-6 py-2.5 text-white bg-blue-600 hover:bg-blue-700">
            Ubah Penanganan
        </x-button.button-submit>
    </div>
    </form>
    </div>

    @push('scripts')
        <script>
            document.getElementById('form-disastervictims').addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Perubahan data korban akan disimpan',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Update',
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

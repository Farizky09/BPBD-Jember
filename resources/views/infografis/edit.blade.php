@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Informasi dan Infografis', 'url' => route('infografis.index')],
            ['name' => 'Ubah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Ubah Informasi atau Infografis" class="text-center" />
@endsection
<form action="{{ route('infografis.update', $infografis->id) }}" method="POST" id="form-edit" enctype="multipart/form-data"
    class="space-y-6">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <x-input.input label="Nama Informasi" name="name" id="name"
                    placeholder="Contoh: Infografis Data Jember 2025" required="true"
                    value="{{ $infografis->name }}"></x-input.input>
            </div>

            <div>
                <label for="category_image" class="block text-sm font-bold text-black mb-2">Kategori Informasi:<span
                        class="text-red-500">*</span></label>
                <select name="category_image" id="category_image"
                    class="w-full px-4 py-2 border border-gray-300 select2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out @error('category_image')  @enderror"
                    required>
                    <option value="" disabled selected>Pilih Kategori Informasi</option>
                    <option value="head_image" @if ($infografis->category_image == 'head_image') selected @endif>Informasi BPBD</option>
                    <option value="infografis_jember" @if ($infografis->category_image == 'infografis_jember') selected @endif>Infografis
                        Bulanan</option>
                    <option value="infografis_raung" @if ($infografis->category_image == 'infografis_raung') selected @endif>Infografis
                        Bencana</option>
                </select>
                @error('category_image')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mb-4">
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
                    <div class="flex items-center text-slate-800 text-lg font-semibold mb-5">
                        <i class="fas fa-images text-blue-500 mr-2"></i>
                        <span>Upload Gambar</span>
                    </div>

                    <p class="text-sm text-slate-600 mb-4">
                        Unggah gambar dokumentasi bencana (format: JPG/PNG)
                        <span class="text-red-600">*</span>
                    </p>

                    <div id="image-upload-container" class="mt-4">
                        <div
                            class="flex items-center mb-4 p-4 bg-white border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition">
                            <input type="file" name="image" accept="image/*" onchange="validateImage(this)"
                                required
                                class="flex-grow px-3 py-2 border border-slate-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">

                        </div>
                    </div>

                    @error('image.*')
                        <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>


        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Gambar Informasi
                </label>
                <div class="mt-2">
                    <a href="{{ $infografis->image ? asset('storage/' . $infografis->image) : asset('assets/img/avatar/dok.png') }}"
                        data-lightbox="gambar-infografis" data-title="Gambar Informasi">
                        <img src="{{ $infografis->image ? asset('storage/' . $infografis->image) : asset('assets/img/avatar/dok.png') }}"
                            alt="Gambar Informasi"
                            class="rounded-lg object-cover w-[600px] h-[450px] hover:opacity-90 transition cursor-zoom-in">
                    </a>
                </div>
            </div>
        </div>

    </div>


    <div class="flex items-center justify-center mt-4">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan</x-button.button-submit>

    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="file"][name="image"]')) {
                const files = Array.from(e.target.files);
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];

                for (const file of files) {
                    if (!validTypes.includes(file.type)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Format Tidak Didukung',
                            text: `"${file.name}" bukan file gambar valid (JPEG, PNG, GIF).`,
                        });
                        e.target.value = '';
                        return;
                    }

                    if (file.size > 3 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ukuran Terlalu Besar',
                            text: `"${file.name}" melebihi batas ukuran 3MB.`,
                        });
                        e.target.value = '';
                        return;
                    }
                }
            }
        });
    </script>
@endpush
@endsection

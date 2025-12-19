@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen consultation', 'url' => route('consultation.index')],
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah edukasi" class="text-center" />
@endsection
<form action="{{ route('consultation.store') }}" method="POST" id="form-create" enctype="multipart/form-data"
    class="space-y-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div>
                <label for="typekategori_id" class="block text-sm font-medium text-gray-700 mb-2">Tipe Kategori Bencana<span
                        class="text-red-500">*</span></label>
                <select name="typekategori_id" id="typekategori_id"
                    class="w-full px-4 py-2 border border-gray-300 select2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out @error('typekategori_id')  @enderror"
                    required>
                    <option value="" disabled selected>Pilih Kategori Bencana</option>
                    @foreach ($typekategori as $typekategoris)
                        <option value="{{ $typekategoris->id }}">{{ $typekategoris->name }}</option>
                    @endforeach
                </select>
                @error('typekategori_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Konsultasi<span
                        class="text-red-500">*</span></label>
                <select name="type" id="type"
                    class="w-full px-4 py-2 border border-gray-300 select2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out @error('type')  @enderror"
                    required>
                    <option value="" disabled selected>Pilih Tipe</option>
                    <option value="before">Before</option>
                    <option value="during">During</option>
                    <option value="after">After</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

        </div>
        <div class="space-y-4">

            <div class="form-group mb-4">
                <label for="video_path" class="block text-sm font-medium text-gray-700 mb-2">
                    Tautan Embed Video YouTube <span class="text-red-500">*</span>
                </label>
                <input type="text" id="video_path" name="video_path"
                    placeholder="Contoh: https://www.youtube.com/embed/abc123 "
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out"
                    required>

                <div id="video-preview-container" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview Video:</p>
                    <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-lg shadow-md">
                        <iframe id="video-preview" class="absolute top-0 left-0 w-full h-full" src=""
                            frameborder="0" allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center pt-4">
        <x-button.button-submit type="submit"
            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition duration-200">
            Simpan Konsultasi
        </x-button.button-submit>
    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('video_path').addEventListener('input', function() {
        const url = this.value;
        const container = document.getElementById('video-preview-container');
        const iframe = document.getElementById('video-preview');
        if (url.includes('youtube.com/embed')) {
            iframe.src = url;
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            iframe.src = '';
            Swal.fire({
                icon: 'warning',
                title: 'Format Video Tidak Didukung',
                text: 'Hanya tautan YouTube yang didukung untuk preview.',
                confirmButtonColor: '#3085d6',
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                }
            });
        }
    });
</script>
<script>
    const formCreate = document.getElementById('form-create');
    const VideoInput = document.getElementById('video_path');
    formCreate.addEventListener('submit', function(event) {
        event.preventDefault();

        const VideoUrl = VideoInput.value.trim();

        if (VideoUrl && !VideoUrl.includes('youtube.com/embed')) {
            Swal.fire({
                icon: 'error',
                title: 'Format Video Tidak Didukung',
                text: 'Hanya tautan YouTube format /embed/ yang didukung untuk preview.',
                confirmButtonColor: '#3085d6',
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                }
            });
            return;
        }
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan data ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Tambahkan!',
            focusConfirm: false,
            customClass: {
                popup: 'swal2-border-radius',
                confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none',
                cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
            },
        }).then((result) => {
            if (result.isConfirmed) {
                formCreate.submit();
            }
        });

    });
</script>
@if ($errors->has('name'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Nama sudah terdaftar',
            text: 'Silakan gunakan nama lain.',
            confirmButtonColor: '#3085d6'
        });
    </script>
@endif
@endsection

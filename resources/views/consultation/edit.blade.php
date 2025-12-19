@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen consultation', 'url' => route('consultation.index')],
            ['name' => 'Ubah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Ubah edukasi" class="text-center" />
@endsection
<form action="{{ route('consultation.update', $data->id) }}" method="POST" id="form-edit" enctype="multipart/form-data"
    class="space-y-6">
    @csrf
    @method('PUT')
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
                        <option value="{{ $typekategoris->id }}" @if ($data->typekategori_id == $typekategoris->id) selected @endif>
                            {{ $typekategoris->name }}</option>
                    @endforeach
                </select>
                @error('typekategori_id')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Tipe Konsultasi<span
                        class="text-red-500">*</span></label>
                <select label="Pilih Tipe" name="type" id="type"
                    class="w-full px-4 py-2 border border-gray-300 select2 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out @error('type')  @enderror"
                    required="true">
                    <option value="">Pilih Tipe</option>
                    <option value="before" @if ($data->type == 'before') selected @endif>before</option>
                    <option value="during" @if ($data->type == 'during') selected @endif>during</option>
                    <option value="after" @if ($data->type == 'after') selected @endif>after</option>
                </select>
            </div>
        </div>
        <div class="space-y-4">
            <div class="form-group mb-4">
                <label for="video_path" class="block text-sm font-medium text-gray-700 mb-2">
                    Tautan Embed Video YouTube <span class="text-red-500">*</span>
                </label>
                <input type="text" id="video_path" name="video_path" value="{{ $data->video_path }}"
                    placeholder="Contoh: https://www.youtube.com/embed/abc123 "
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition duration-150 ease-in-out"
                    required>
                @error('video_path')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror

            </div>

            <div class="form-group mb-4">
                <div id="video-preview-container"
                    class="{{ Str::contains($data->video_path, 'youtube.com/embed') ? '' : 'hidden' }} mt-4">
                    <div class="relative pb-[56.25%] h-0 overflow-hidden rounded-lg shadow-md">
                        <iframe id="video-preview" class="absolute top-0 left-0 w-full h-full"
                            src="{{ Str::contains($data->video_path, 'youtube.com/embed') ? $data->video_path : '' }}"
                            frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                @if ($data->video_path && !Str::contains($data->video_path, 'youtube.com/embed'))
                    <p class="text-yellow-600 text-sm mt-2">Format video tidak didukung untuk preview. Masukkan format
                        <code>youtube.com/embed</code>.
                    </p>
                @elseif (!$data->video_path)
                    <p class="text-sm text-gray-500 italic mt-2">Belum ada video ditambahkan.</p>
                @endif
            </div>

        </div>
    </div>


    <div class="flex items-center justify-center mt-4">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Simpan Konsultasi</x-button.button-submit>

    </div>
</form>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('video_path').addEventListener('input', function() {
        const url = this.value.trim();
        const previewContainer = document.getElementById('video-preview-container');
        const iframe = document.getElementById('video-preview');

        if (url.includes('youtube.com/embed')) {
            iframe.src = url;
            previewContainer.classList.remove('hidden');
        } else {
            iframe.src = '';
            previewContainer.classList.add('hidden');

            if (url.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Video Tidak Didukung',
                    text: 'Hanya tautan YouTube format /embed/ yang didukung untuk preview.',
                    confirmButtonColor: '#3085d6',
                    customClass: {
                        popup: 'swal2-border-radius',
                        confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                    }
                });
            }
        }
    });
</script>

<script>
    const formCreate = document.getElementById('form-edit');
    const videoInput = document.getElementById('video_path');

    formCreate.addEventListener('submit', function(event) {
        event.preventDefault();

        const videoUrl = videoInput.value.trim();


        if (videoUrl && !videoUrl.includes('youtube.com/embed')) {
            Swal.fire({
                icon: 'error',
                title: 'Link Video Tidak Valid',
                text: 'Pastikan Anda menggunakan link YouTube embed yang valid, seperti: https://www.youtube.com/embed/VIDEO_ID',
                confirmButtonColor: '#d33',
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
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
            confirmButtonText: 'Ya, Ubah!',
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

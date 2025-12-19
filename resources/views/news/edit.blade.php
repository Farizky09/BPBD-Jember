@extends('layouts.master')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
@endpush
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Berita', 'url' => route('news.index')],
            ['name' => 'Edit', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Edit Berita" class="text-center" />
@endsection
<form id="form-news" action="{{ route('news.update', $data->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <label for="id_confirm_reports" class="block text-gray-700 text-sm font-bold mb-2">Laporan:</label>
                <select
                    class="appearance-none select2 border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('id_confirm_reports') border-red-500 @enderror"
                    id="id_confirm_reports" name="id_confirm_reports">
                    <option value="">Pilih Laporan</option>
                    @foreach ($confirmReport as $confirm)
                        <option value="{{ $confirm->id }}"
                            {{ old('id_confirm_reports', $data->id_confirm_reports) == $confirm->id ? 'selected' : '' }}>
                            {{ $confirm->report->kd_report }}
                        </option>
                    @endforeach
                </select>
                @error('id_confirm_reports')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <x-input.input label="Judul" name="title" id="title" value="{{ $data->title }}"></x-input.input>
                {{-- <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Judul:</label>
                <input type="text"
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('title') border-red-500 @enderror"
                    id="title" name="title" value="{{ old('title', $data->title) }}" placeholder="Masukkan judul">
                @error('title')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror --}}
            </div>
        </div>
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <label for="published_at" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Publish:</label>
                <input type="datetime-local"
                    class="appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('published_at') border-red-500 @enderror"
                    id="published_at" name="published_at" value="{{ old('published_at', $data->published_at) }}">
                @error('published_at')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <label for="takedown_at" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Takedown:</label>
                <input type="datetime-local"
                    class="appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('takedown_at') border-red-500 @enderror"
                    id="takedown_at" name="takedown_at" value="{{ old('takedown_at', $data->takedown_at) }}">
                @error('takedown_at')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>


    <div class="form-group mb-4 px-1">

    </div>

    <div class="form-group mb-4 px-1">
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
            <div class="flex items-center text-slate-800 text-lg font-semibold mb-5">
                <i class="fas fa-images text-blue-500 mr-2"></i>
                <span>Upload Gambar <span class="text-red-600">*</span></span>
            </div>

            <p class="text-sm text-slate-600 mb-4">
                Unggah gambar dokumentasi bencana (maksimal 3 file, format: JPG/PNG, maks 2MB per file)
                <span class="text-red-600">*</span>
            </p>

            <div id="image-upload-container" class="mt-4">
                <div
                    class="flex items-center mb-4 p-4 bg-white border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition">
                    <input type="file" name="image[]" accept="image/*" onchange="validateImage(this)"
                        class="flex-grow px-3 py-2 border border-slate-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
                    <button type="button" onclick="removeImageInput(this)"
                        class="ml-4 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition transform hover:scale-105"
                        title="Hapus Gambar">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="button" id="add-image-btn"
                class="inline-flex items-center px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-medium rounded-lg transition mt-3 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                <i class="fas fa-plus mr-2"></i>
                Tambah Gambar
            </button>

            @error('image.*')
                <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="image" class="block text-gray-700 text-sm font-bold mb-2">
                List Gambar
            </label>
            <div class="flex space-x-4 overflow-x-auto pb-4">
                @foreach ($data->imageNews as $image)
                    <div class="relative flex-shrink-0 w-[200px] h-[200px] rounded-lg overflow-hidden shadow-md"
                        id="image-container-{{ $image->id }}">
                        <div class="absolute top-1 left-1 z-10">
                            <button type="button" onclick="deleteImage({{ $image->id }})"
                                class="text-red-600 bg-white bg-opacity-75 rounded-full p-1 hover:bg-opacity-100">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </div>
                        <a href="{{ asset('storage/' . $image->image_path) }}" data-lightbox="bukti-pembayaran"
                            data-title="Gambar Bencana">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Bencana"
                                class="w-full h-full object-cover hover:opacity-90 transition-opacity">
                        </a>
                        <input type="hidden" name="image[]" value="{{ $image->id }}">
                    </div>
                @endforeach
            </div>
        </div>

        <input type="hidden" name="deleted_images" id="deleted-images-input" value="">
    </div>

    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Konten:</label>
    <textarea
        class="appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('content') border-red-500 @enderror"
        id="summernote" name="content" rows="10">{!! old('content', $data->content) !!}</textarea>
    @error('content')
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
    </div>



    </div>

    <div class="flex items-center justify-center mt-6">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Update Berita</x-button.button-submit>
    </div>
</form>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
        $('#summernote').summernote({
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
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    </script>
    <script>
        const publishedAt = document.getElementById('published_at');
        const takedownAt = document.getElementById('takedown_at');

        function getDayName(dateString) {
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const date = new Date(dateString);
            return days[date.getDay()];
        }
        publishedAt.addEventListener('change', function() {
            const value = this.value;
            takedownAt.min = value;

            const selectedDay = new Date(value).getDay();
            if (selectedDay === 0 || selectedDay === 6) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Hari tidak valid',
                    text: 'Tanggal publish tidak boleh di hari ' + getDayName(value),
                });
                this.value = '';
            }
        });


        takedownAt.addEventListener('change', function() {
            const publishVal = publishedAt.value;
            const takedownVal = this.value;

            if (publishVal && takedownVal < publishVal) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tanggal tidak valid',
                    text: 'Tanggal takedown tidak boleh lebih awal dari tanggal publish.',
                });
                this.value = '';
                return;
            }

            // const selectedDay = new Date(takedownVal).getDay();
            // if (selectedDay === 0 || selectedDay === 6) {
            //     Swal.fire({
            //         icon: 'warning',
            //         title: 'Hari tidak valid',
            //         text: 'Tanggal takedown tidak boleh di hari ' + getDayName(takedownVal),
            //     });
            //     this.value = '';
            // }
        });
    </script>

    <script>
        function validateImage(input) {
            const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            const file = input.files[0];

            if (!file) return;

            if (!validImageTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format file tidak valid',
                    text: 'Hanya menerima file gambar (JPEG, PNG, GIF)',
                });
                input.value = '';
            }
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File terlalu besar',
                    text: 'Ukuran file maksimal 2MB',
                });
                input.value = '';
            }
        }

        const deletedImages = [];

        function deleteImage(imageId) {
            const visibleImageCount = document.querySelectorAll('[id^="image-container-"]:not([style*="display: none"])')
                .length;

            if (visibleImageCount <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Minimal harus ada satu gambar!',
                });
                return;
            }
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Gambar ini akan dihapus saat Anda menyimpan perubahan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`image-container-${imageId}`).style.display = 'none';

                    deletedImages.push(imageId);

                    document.getElementById('deleted-images-input').value = JSON.stringify(deletedImages);

                    Swal.fire(
                        'Dihapus!',
                        'Gambar akan dihapus saat Anda menyimpan perubahan.',
                        'success'
                    );
                }
            });
        }
        const existingImagesCount = {{ count($data->imageNews) }};
        const maxAllowedImages = 3;
        document.getElementById('add-image-btn').addEventListener('click', function() {
            const container = document.getElementById('image-upload-container');
            const remainingSlots = maxAllowedImages - existingImagesCount + deletedImages.length - container
                .children.length;
            if (remainingSlots <= 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maksimal Gambar',
                    text: `Anda hanya dapat menambahkan maksimal ${maxAllowedImages - existingImagesCount + deletedImages.length} gambar baru.`,
                });
                return;
            }

            const newItem = document.createElement('div');
            newItem.className =
                "flex items-center mb-4 p-4 bg-white border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition";
            newItem.innerHTML = `
                <input 
                    type="file" 
                    name="image[]" 
                    accept="image/*" 
                    onchange="validateImage(this)"
                    class="flex-grow px-3 py-2 border border-slate-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button 
                    type="button" 
                    onclick="removeImageInput(this)"
                    class="ml-4 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition transform hover:scale-105"
                    title="Hapus Gambar">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 
                            4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 
                            00-1 1v3M4 7h16" />
                    </svg>
                </button>
            `;
            container.appendChild(newItem);
        });

        function removeImageInput(button) {
            const container = document.getElementById('image-upload-container');
            const item = button.parentElement;

            if (container.children.length > 1) {
                container.removeChild(item);
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Minimal harus ada satu gambar!',
                });
            }
        }
    </script>
    <script>
        document.getElementById('form-news').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data Berita akan diperbarui',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@endpush
@endsection

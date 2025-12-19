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
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Berita" class="text-center" />
@endsection
<form id="form-news" action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <label for="id_confirm_reports" class="block text-gray-700 text-sm font-bold mb-2">Laporan: <span class="text-red-600">*</span></label>
                <select
                    class="appearance-none select2 border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('id_confirm_reports') border-red-500 @enderror"
                    id="id_confirm_reports" name="id_confirm_reports" required>
                    <option value="">Pilih Laporan</option>
                    @foreach ($data as $confirmReport)
                        <option value="{{ $confirmReport->id }}"
                            {{ old('id_confirm_reports') == $confirmReport->id ? 'selected' : '' }}>
                            {{ $confirmReport->report->kd_report }}
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
                <x-input.input label="Judul Berita" name="title" id="title"
                    value="{{ old('title') }}" required="true"></x-input.input>

            </div>
        </div>
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <x-input.input label="Tanggal Publish" id="published_at" name="published_at"
                    value="{{ old('published_at') }}" type="datetime-local" ></x-input.input>
            </div>
        </div>
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <x-input.input label="Tanggal Takedown" id="takedown_at" name="takedown_at"
                    value="{{ old('takedown_at') }}" type="datetime-local"></x-input.input>
            </div>
        </div>
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
                    <input type="file" name="image[]" accept="image/*" onchange="validateImage(this)" required
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

        <label for="content" class="block text-gray-700 text-sm font-bold mt-3 mb-2">Konten:<span class="text-red-600">*</span></label>
        <textarea
            class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('content') border-red-500 @enderror"
            id="summernote" name="content" rows="10" required>{{ old('content') }}</textarea>
        @error('content')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>




    <div class="flex items-center justify-center mt-6">
        <x-button.button-submit type="submit" class="px-5 py-2.5">Unggah Berita</x-button.button-submit>

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
        // publishedAt.addEventListener('change', function() {
        //     const value = this.value;
        //     takedownAt.min = value;

        //     const selectedDay = new Date(value).getDay();
        //     if (selectedDay === 0 || selectedDay === 6) {
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Hari tidak valid',
        //             text: 'Tanggal publish tidak boleh di hari ' + getDayName(value),
        //         });
        //         this.value = '';
        //     }
        // });


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


        document.getElementById('add-image-btn').addEventListener('click', function() {
            const container = document.getElementById('image-upload-container');
            if (container.children.length >= 3) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Maksimal 3 Gambar',
                    text: 'Anda hanya dapat menambahkan maksimal 3 gambar.',
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



        document.getElementById('form-news').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Data Berita akan disimpan',
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

@extends('layouts.master')
@push('style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        /* Layout improvements */
        .form-container {
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .form-header {
            margin-bottom: 2rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #eef2f7;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-description {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Form groups */
        .form-section {
            margin-bottom: 2.5rem;
            padding: 1.5rem;
            border-radius: 10px;
            background-color: #f8fafc;
            border: 1px solid #eef2f7;
        }

        .section-title {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: #3b82f6;
        }

        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #334155;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .form-select,
        .form-input {
            width: 100%;
            padding: 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: white;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-select:focus,
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        /* Summernote improvements */
        .note-editor {
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
        }

        .note-editor .note-toolbar {
            background-color: #f1f5f9;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 8px 8px 0 0;
            padding: 8px 10px;
        }

        .note-editor .note-statusbar {
            background-color: #f1f5f9;
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 8px 8px;
        }

        .note-editor.note-frame {
            margin-bottom: 0;
        }

        /* Image upload section */
        .image-upload-section {
            background-color: #f8fafc;
            border: 1px solid #eef2f7;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .image-upload-container {
            margin-top: 1rem;
        }

        .image-upload-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 1rem;
            background-color: white;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
            transition: all 0.3s;
        }

        .image-upload-item:hover {
            border-color: #3b82f6;
            background-color: #f0f7ff;
        }

        .image-upload-item input {
            flex-grow: 1;
            padding: 0.75rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            background-color: white;
        }

        .remove-btn {
            margin-left: 1rem;
            color: #ef4444;
            cursor: pointer;
            background: #fee2e2;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .remove-btn:hover {
            background: #fecaca;
            transform: scale(1.05);
        }

        .add-image-btn {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            background-color: #10b981;
            color: white;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }

        .add-image-btn i {
            margin-right: 8px;
        }

        /* Button section */
        .button-section {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #eef2f7;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.9rem 2.5rem;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn i {
            margin-right: 10px;
        }



        .error-message {
            color: #ef4444;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .form-container {
                padding: 1.25rem;
            }

            .form-section {
                padding: 1rem;
            }
        }
    </style>
@endpush
@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Dokumen Laporan Bencana', 'url' => route('disaster_report_documentations.index')],
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Laporan" class="text-center" />
@endsection

<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Formulir Dokumentasi Laporan Bencana</h1>
        <p class="form-description">Lengkapi informasi di bawah ini untuk membuat dokumentasi laporan bencana baru</p>
    </div>

    <form id="form-disasterReportDocumentations" action="{{ route('disaster_report_documentations.store') }}"
        method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Informasi Utama Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-info-circle"></i>
                <span>Informasi Utama</span>
            </div>

            <div class="form-group">
                <label for="confirm_report_id" class="form-label">Laporan Terkait</label>
                <select class="select2 form-select @error('confirm_report_id') border-red-500 @enderror"
                    id="confirm_report_id" name="confirm_report_id">
                    <option value="">Pilih Laporan</option>
                    @foreach ($confirmReports as $confirmReport)
                        <option value="{{ $confirmReport->id }}"
                            {{ old('confirm_report_id') == $confirmReport->id ? 'selected' : '' }}>
                            {{ $confirmReport->report->kd_report }}
                        </option>
                    @endforeach
                </select>
                @error('confirm_report_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Detail Bencana Section -->
        <div class="form-section">
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                <span>Detail Bencana</span>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Kronologi Bencana -->
                <div class="form-group">
                    <label for="disaster_chronology" class="form-label">Kronologi Bencana</label>
                    <textarea class="summernote @error('disaster_chronology') border-red-500 @enderror" id="disaster_chronology"
                        name="disaster_chronology">{{ old('disaster_chronology') }}</textarea>
                    @error('disaster_chronology')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Dampak Bencana -->
                <div class="form-group">
                    <label for="disaster_impact" class="form-label">Dampak Bencana</label>
                    <textarea class="summernote @error('disaster_impact') border-red-500 @enderror" id="disaster_impact"
                        name="disaster_impact">{{ old('disaster_impact') }}</textarea>
                    @error('disaster_impact')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Respon Bencana -->
                <div class="form-group">
                    <label for="disaster_response" class="form-label">Respon Bencana</label>
                    <textarea class="summernote @error('disaster_response') border-red-500 @enderror" id="disaster_response"
                        name="disaster_response">{{ old('disaster_response') }}</textarea>
                    @error('disaster_response')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Upload Gambar Section -->
        <div class="image-upload-section">
            <div class="section-title">
                <i class="fas fa-images"></i>
                <span>Dokumentasi Gambar</span>
            </div>

            <p class="text-sm text-slate-600 mb-4">Unggah gambar dokumentasi bencana (maksimal 3 file, format: JPG/PNG,
                maks 2MB per file)</p>

            <div class="image-upload-container" id="image-upload-container">
                <div class="image-upload-item">
                    <input type="file" class="form-input @error('image.*') border-red-500 @enderror" name="image[]"
                        accept="image/*" onchange="validateImage(this)" required>
                    <div class="remove-btn" onclick="removeImageInput(this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </div>
                </div>
            </div>

            <button type="button" id="add-image-btn" class="add-image-btn mt-3">
                <i class="fas fa-plus"></i>
                Tambah Gambar
            </button>

            @error('image.*')
                <span class="error-message mt-2">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="button-section">
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i>
                Simpan Laporan
            </button>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
    <script>
        $('.summernote').summernote({
            height: 250,
            minHeight: 180,
            maxHeight: 350,
            focus: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Ketikkan detail laporan di sini...'
        });

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

            const newInput = document.createElement('div');
            newInput.classList.add('image-upload-item');
            newInput.innerHTML = `
                <input type="file" class="form-input" name="image[]" accept="image/*" onchange="validateImage(this)">
                <div class="remove-btn" onclick="removeImageInput(this)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                </div>
            `;
            container.appendChild(newInput);
        });

        function removeImageInput(button) {
            const container = document.getElementById('image-upload-container');
            const item = button.closest('.image-upload-item');
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

        document.getElementById('form-disasterReportDocumentations').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Konfirmasi Penyimpanan',
                text: 'Apakah Anda yakin ingin menyimpan data laporan ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'bg-blue-600  text-white font-medium py-2 px-4 rounded-lg focus:outline-none',
                    cancelButton: 'bg-red-600 text-white font-medium py-2 px-4 rounded-lg focus:outline-none ml-3'
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

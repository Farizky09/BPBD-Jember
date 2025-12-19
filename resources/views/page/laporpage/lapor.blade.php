@include('page.components.head')
@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="index-page mt-5">
    @include('page.components.header')
    <section id="lapor" class="lapor section bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h1 class="text-4xl font-bold text-gray-800">Lapor <span class="text-orange-500">Bencana</span></h1>
                <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                    Bantu kami memantau dan mengatasi bencana dengan laporan Anda.
                </p>
            </div>
            <div class="border border-gray-100 rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <form id="form-report" action="{{ route('reports.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-6 md:p-8">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <input type="hidden" name="subdistrict" id="subdistrict">
                        <div>
                            {{-- <div class="mb-6">
                                <x-input.input label="Nama Bencana" name="name" id="name"
                                    placeholder="Contoh: Banjir" required="true"></x-input.input>
                            </div> --}}
                            <div class="mb-6 w-full">
                                <label for="id_category" class="block text-gray-700 text-sm font-bold mb-2">
                                    Jenis Bencana: <span class="text-red-600">*</span>
                                </label>
                                <select name="id_category" id="id_category" required
                                    class="select2 custom-select2-height block w-full rounded-lg border border-gray-300 bg-white py-2 px-3 text-sm text-gray-700 focus:border-orange-500 focus:outline-none focus:ring-1 focus:ring-orange-500">
                                    <option value="" disabled selected>Pilih Jenis Bencana</option>
                                    @foreach ($data as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('id_category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-6">
                                <x-input.input-label value="Lokasi Bencana"></x-input.input-label>
                                <p id="status" class="text-sm text-gray-600 mb-2"></p>
                                <a id="map-link" href="#"
                                    class="text-orange-500 hover:text-orange-700 underline">Lihat Peta</a>
                            </div>
                            <input type="hidden" name="from" value="landingPage">
                            <x-input.input type="hidden" name="latitude" id="latitude"
                                value="{{ old('latitude') }}"></x-input.input>
                            <x-input.input type="hidden" name="longitude" id="longitude"
                                value="{{ old('longitude') }}"></x-input.input>
                            <div class="mb-6">
                                <x-input.input-textarea label="Alamat" name="address" id="address" rows="3"
                                    placeholder="Masukkan alamat lengkap" readonly="true" />

                            </div>
                        </div>
                        <div>
                            <div class="mb-6">
                                <x-input.input-textarea label="Deskripsi" name="description" id="description"
                                    rows="5" placeholder="Jelaskan secara detail" required="true" />

                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar <span
                                        class="text-red-600"> *</span></label>
                                <div id="image-upload-container">
                                    <div class="image-upload-item flex items-center mb-2">
                                        <input type="file" name="image[]" accept="image/*"
                                            class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                                            multiple required>

                                        <button type="button" class="ml-2 text-red-500 hover:text-red-700"
                                            onclick="removeImageInput(this)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <x-button.button-success id="add-image-btn" class="my-3">Tambah
                                    Gambar</x-button.button-success>

                            </div>
                        </div>
                    </div>
                    <div class="flex justify-center mt-6">
                        <x-button.button-submit type="submit" class="py-2.5 px-5">Kirim
                            Laporan</x-button.button-submit>

                    </div>
                </form>
            </div>
        </div>
    </section>
    <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="mapModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalLabel">Peta Lokasi</h5>

                </div>
                <div class="modal-body">
                    <div id="map" class="w-100" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>
    @include('page.components.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('incomplete_profile'))
                Swal.fire({
                    title: 'Data Tidak Lengkap',
                    text: 'Lengkapi profil Anda sebelum membuat laporan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ke Profil',
                    cancelButtonText: 'Nanti'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('profile.edit') }}";
                    }
                });
            @endif
        });
    </script>
    <script src="{{ asset('assets/js/lapor-landingpage.js') }}"></script>
    @include('components.flash-message')

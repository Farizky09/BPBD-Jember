    @extends('layouts.master')
    @section('main')
    @section('breadcrumb')
        @php
            $links = [
                ['name' => 'Dashboard', 'url' => route('dashboard')],
                ['name' => 'Manajemen Laporan', 'url' => route('reports.index')],
                ['name' => 'Ubah', 'url' => ''],
            ];
        @endphp
        <x-breadcrumb :links="$links" title="Ubah Laporan" class="text-center" />
    @endsection
    <form id="form-report" action="{{ route('reports.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-8 justify-start">
            <div class="w-full md:w-1/2 px-4">
                {{-- <div class="form-group mb-4">
                    <x-input.input label="Judul Laporan" name="name" id="name" placeholder="Contoh: Banjir"
                        required="true" value="{{ $data->name }}"></x-input.input>
                </div> --}}

                <div class="form-group mb-4">
                    <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Jenis Bencana:</label>
                    <select
                        class="appearance-none border select2 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('category') border-red-500 @enderror"
                        id="category" name="id_category" required>
                        <option value="" disabled selected>Pilih Jenis</option>
                        @foreach ($category as $categories)
                            <option value="{{ $categories->id }}"
                                {{ $data->id_category == $categories->id ? 'selected' : '' }}>
                                {{ $categories->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group mb-4">
                    <div class="mt-3 mb-4">
                        <x-input.input-label value="Lokasi Bencana"></x-input.input-label>
                        <p id="status" class="text-sm text-gray-600 mb-2"></p>
                        <a id="map-link" href="#" class="text-blue-500 hover:text-blue-700 underline">Lihat
                            Peta</a>
                    </div>
                </div>
                <div class="form-group mb-4">
                    <input type="text"
                        class=" appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none  @error('latitude') border-red-500 @enderror"
                        id="latitude" name="latitude" value="{{ $data->latitude }}" aria-hidden="true" hidden>
                    @error('latitude')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group mb-4">
                    <input type="text"
                        class=" appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none  @error('longitude') border-red-500 @enderror"
                        id="longitude" name="longitude" value="{{ $data->longitude }}" aria-hidden="true" hidden>
                    @error('longitude')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                    <input type="hidden" name="subdistrict" id="subdistrict">
                </div>

                <div class="form-group mb-4">
                    <x-input.input-textarea label="Alamat" name="address" id="address" rows="3"
                        placeholder="Masukkan alamat lengkap" readonly="true" value="{{ $data->address }}" />
                </div>

                <div class="form-group">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">
                        Gambar
                    </label>
                    <div class="flex space-x-4 overflow-x-auto">
                        @foreach ($data->images as $image)
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
            </div>

            <div class="w-full md:w-1/2 px-4">
                <div class="form-group mb-4">
                    <x-input.input label="Status" name="status" value="{{ $data->status }} "
                        disabled="true"></x-input.input>

                </div>

                <div class="form-group ">
                    <x-input.input-textarea label="Deskripsi" name="description" id="description" rows="5"
                        placeholder="Jelaskan secara detail" required="true" value="{{ $data->description }}" />
                </div>

                @php
                    $imageCount = $data->images->count();
                @endphp

                @if ($imageCount < 3)
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-4">
                        <div class="flex items-center text-slate-800 text-lg font-semibold mb-5">
                            <i class="fas fa-images text-blue-500 mr-2"></i>
                            <span>Upload Gambar</span>
                        </div>

                        <p class="text-sm text-slate-600 mb-4">
                            Unggah gambar dokumentasi bencana (maksimal 3 file, format: JPG/PNG, maks 2MB per file)
                            <span class="text-red-600">*</span>
                        </p>

                        <div id="image-upload-container" class="mt-4">
                            <div
                                class="flex items-center mb-4 p-4 bg-white border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition">
                                <input type="file" name="image[]" accept="image/*" onchange="validateImage(this)"
                                    class="flex-grow py-2 px-3 text-sm text-gray-700 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <button type="button" onclick="removeImageInput(this)"
                                    class="ml-4 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition-transform hover:scale-105"
                                    title="Hapus gambar">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
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
                    </div>
                @endif

            </div>


        </div>
        <input type="hidden" name="deleted_images" id="deleted-images-input" value="">
        <div class="flex items-center justify-center mt-4">
            <x-button.button-submit type="submit" class="py-2.5 px-5">Simpan Laporan</x-button.button-submit>
        </div>

    </form>
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
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                geoFindMe();
            });

            function geoFindMe() {
                const status = document.querySelector("#status");
                const mapLink = document.querySelector("#map-link");
                const mapModal = document.getElementById("mapModal");
                const closeModal = document.getElementById("closeModal");
                const hiddenLatitudeField = document.querySelector('input[name="latitude"]');
                const hiddenLongitudeField = document.querySelector('input[name="longitude"]');
                const hiddenAddressField = document.querySelector('input[name="address"]');
                const hiddenSubdistrictField = document.querySelector('input[name="subdistrict"]');
                const visibleAddressField = document.querySelector('textarea[name="address"]');
                const visibleLatitudeField = document.querySelector('input[name="latitude"]');
                const visibleLongitudeField = document.querySelector('input[name="longitude"]');
                const mapContainer = document.getElementById('map');

                let map = null;
                let marker = null;

                mapLink.href = "#";
                mapLink.textContent = "Pilih Lokasi di Peta";

                mapLink.addEventListener("click", function(event) {
                    event.preventDefault();

                    $('#mapModal').modal('show');

                    const latValue = parseFloat(hiddenLatitudeField.value);
                    const lngValue = parseFloat(hiddenLongitudeField.value);

                    // Validasi, kalau kosong pakai default Indonesia
                    const latitude = isNaN(latValue) ? -2.5489 : latValue;
                    const longitude = isNaN(lngValue) ? 118.0149 : lngValue;

                    if (!map) {
                        initializeMap(latitude, longitude, visibleAddressField?.value || '');
                    }
                });

                function initializeMap(latitude, longitude, address = '') {
                    map = new google.maps.Map(mapContainer, {
                        center: {
                            lat: latitude,
                            lng: longitude
                        },
                        zoom: 15
                    });

                    marker = new google.maps.Marker({
                        position: {
                            lat: latitude,
                            lng: longitude
                        },
                        map: map,
                        draggable: true,
                        title: address || 'Pilih Lokasi Bencana'
                    });

                    map.addListener('click', function(event) {
                        placeMarker(event.latLng);
                    });

                    marker.addListener('dragend', function(event) {
                        placeMarker(event.latLng);
                    });
                }

                function placeMarker(location) {
                    marker.setPosition(location);
                    map.panTo(location);

                    const latitude = location.lat();
                    const longitude = location.lng();

                    if (hiddenLatitudeField) hiddenLatitudeField.value = latitude;
                    if (hiddenLongitudeField) hiddenLongitudeField.value = longitude;
                    if (visibleLatitudeField) visibleLatitudeField.value = latitude;
                    if (visibleLongitudeField) visibleLongitudeField.value = longitude;

                    status.textContent = "Mengambil alamat baru...";
                    fetch('/get-location-data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                latitude,
                                longitude
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                const address = data.display_name;
                                if (hiddenAddressField) hiddenAddressField.value = address;
                                if (visibleAddressField) visibleAddressField.value = address;
                                if (hiddenSubdistrictField) hiddenSubdistrictField.value = data.subdistrict || '';
                                status.textContent = "";
                            } else {
                                status.textContent = "Gagal mengambil alamat";
                            }
                        })
                        .catch(() => {
                            status.textContent = "Gagal mengambil alamat";
                        });
                }

                if (closeModal) {
                    closeModal.addEventListener("click", function() {
                        $('#mapModal').modal('hide');
                    });
                }
            }


            function validateImage(input) {
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                const file = input.files[0];

                //     if (!file) return;

                //     if (!validImageTypes.includes(file.type)) {
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'Format file tidak valid',
                //             text: 'Hanya menerima file gambar (JPEG, PNG, GIF)',
                //         });
                //         input.value = '';
                //     }
                //     const maxSize = 2 * 1024 * 1024;
                //     if (file.size > maxSize) {
                //         Swal.fire({
                //             icon: 'error',
                //             title: 'File terlalu besar',
                //             text: 'Ukuran file maksimal 2MB',
                //         });
                //         input.value = '';
                //     }



                // }

                document.addEventListener('change', function(e) {
                    if (e.target.matches('input[type="file"][name="image[]"]')) {
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

                            if (file.size > 2 * 1024 * 1024) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Ukuran Terlalu Besar',
                                    text: `"${file.name}" melebihi batas ukuran 2MB.`,
                                });
                                e.target.value = '';
                                return;
                            }

                        }
                    }
                });

                const deletedImages = [];

                function deleteImage(imageId) {
                    const visibleImageCount = document.querySelectorAll(
                            '[id^="image-container-"]:not([style*="display: none"])')
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
                        cancelButtonText: 'Batal',
                        focusConfirm: false,
                        customClass: {
                            popup: 'swal2-border-radius',
                            confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                            cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
                        },
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


                const existingImagesCount = {{ count($data->images) }};
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
            document.getElementById('form-report').addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Data laporan akan disimpan',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: '<span style="padding:0 16px;">Simpan</span>',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    customClass: {
                        popup: 'swal2-border-radius',
                        confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none'
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

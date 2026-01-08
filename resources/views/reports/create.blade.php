@extends('layouts.master')

@section('main')
@section('breadcrumb')
    @php
        $links = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Manajemen Laporan', 'url' => route('reports.index')],
            ['name' => 'Tambah', 'url' => ''],
        ];
    @endphp
    <x-breadcrumb :links="$links" title="Tambah Laporan" class="text-center" />
@endsection
<form id="form-report" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
    <input type="hidden" name="address" id="address">
    <input type="hidden" name="subdistrict" id="subdistrict">
    <div class="flex flex-wrap -mx-4">
        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <label for="id_category" class="block text-gray-700 text-sm font-bold mb-2">Jenis Bencana: <span
                        class="text-red-600">
                        *</span></label>
                <select id="id_category" name="id_category"
                    class="appearance-none select2 border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none @error('id_category') border-red-500 @enderror"
                    required>
                    <option value="" disabled selected>Pilih Jenis</option>
                    @foreach ($category as $cat)
                        <option value="{{ $cat->id }}" {{ old('id_category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('id_category')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group mb-4">
                <div class="mt-3 mb-4">
                    <x-input.input-label value="Lokasi Bencana"></x-input.input-label>
                    <p id="status" class="text-sm text-gray-600 mb-2"></p>
                    <a id="map-link" href="#" class="text-blue-500 hover:text-blue-700 underline">Lihat Peta</a>
                </div>

            </div>
            <div class="form-group mb-4">
                <input type="text"
                    class=" appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none  @error('latitude') border-red-500 @enderror"
                    id="latitude" name="latitude" value="{{ old('latitude') }}" aria-hidden="" hidden>
                @error('latitude')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror

            </div>

            <div class="form-group mb-4">
                <input type="text"
                    class=" appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none  @error('longitude') border-red-500 @enderror"
                    id="longitude" name="longitude" value="{{ old('longitude') }}" aria-hidden="" hidden>
                @error('longitude')
                    <p class="text-red-500 text-xs italic">{{ $message }}</p>
                @enderror
            </div>
            <input type="hidden" name="from" value="dashboard">

            <div class="form-group mb-4">
                <x-input.input-textarea label="Deskripsi" name="description" id="description" rows="10"
                    placeholder="Jelaskan secara detail" required="true" />
            </div>

        </div>

        <div class="w-full md:w-1/2 px-4">
            <div class="form-group mb-4">
                <x-input.input-textarea label="Alamat" value="{{ old('address') }}" name="address" id="address"
                    rows="3" placeholder="Contoh: Jl. Raya No. 123" required="true"
                    readonly></x-input.input-textarea>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6">
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
                        <input type="file" name="image[]" accept="image/*" onchange="validateImage(this)" required
                            class="flex-grow px-3 py-2 border border-slate-300 rounded-md text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('image.*') border-red-500 @enderror">
                        <button type="button" onclick="removeImageInput(this)"
                            class="ml-4 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition transform hover:scale-105"
                            title="Hapus Gambar">
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

                @error('image.*')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

        </div>
    </div>

    <div class="flex items-center justify-center mt-6">
        <x-button.button-submit type="submit" class="py-2.5 px-5">Kirim Laporan</x-button.button-submit>
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
            const hiddenLatitudeField = document.querySelector('input[type="hidden"][name="latitude"]');
            const hiddenLongitudeField = document.querySelector('input[type="hidden"][name="longitude"]');
            const hiddenAddressField = document.querySelector('input[type="hidden"][name="address"]');
            const hiddenSubdistrictField = document.querySelector('input[type="hidden"][name="subdistrict"]');
            const visibleAddressField = document.querySelector('textarea[name="address"]');
            const visibleLatitudeField = document.querySelector('input[type="text"][name="latitude"]');
            const visibleLongitudeField = document.querySelector('input[type="text"][name="longitude"]');
            const mapContainer = document.getElementById('map');

            let map = null;
            let marker = null;

            mapLink.href = "#";
            mapLink.textContent = "";

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
                            'Content-Type': 'application/json',
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
                            // Set subdistrict if available
                            if (hiddenSubdistrictField) {
                                hiddenSubdistrictField.value = data.subdistrict || '';
                            }
                            status.textContent = "";
                        } else {
                            status.textContent = "Gagal mengambil alamat";
                        }
                    })
                    .catch(error => {
                        status.textContent = "Gagal mengambil alamat";
                    });
            }

            function success(position) {
                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                if (hiddenLatitudeField) hiddenLatitudeField.value = latitude;
                if (hiddenLongitudeField) hiddenLongitudeField.value = longitude;

                status.textContent = "Mengambil alamat...";
                fetch('/get-location-data', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
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
                            if (hiddenLatitudeField) hiddenLatitudeField.value = latitude;
                            if (visibleLatitudeField) visibleLatitudeField.value = latitude;
                            if (hiddenLongitudeField) hiddenLongitudeField.value = longitude;
                            if (visibleLongitudeField) visibleLongitudeField.value = longitude;
                            if (hiddenAddressField) hiddenAddressField.value = address;
                            if (visibleAddressField) visibleAddressField.value = address;
                            // Set subdistrict if available
                            if (hiddenSubdistrictField) {
                                hiddenSubdistrictField.value = data.subdistrict || '';
                            }

                            status.textContent = "";
                            mapLink.textContent = "Pilih Lokasi di Peta";

                            mapLink.addEventListener("click", function(event) {
                                event.preventDefault();
                                if (mapModal) {
                                    $('#mapModal').modal('show');

                                    if (!map) {
                                        initializeMap(latitude, longitude, address);
                                    }
                                }
                            });
                        } else {
                            status.textContent = "Tidak dapat mengambil alamat";
                        }
                    })
                    .catch(error => {
                        status.textContent = "Gagal mengambil alamat";
                    });
            }

            function error() {
                status.textContent = "Tidak dapat mengambil lokasi Anda";
            }

            if (!navigator.geolocation) {
                status.textContent = "Geolokasi tidak didukung oleh browser Anda";
            } else {
                status.textContent = "Mengambil lokasi awal...";
                navigator.geolocation.getCurrentPosition(success, error);
            }

            if (closeModal) {
                closeModal.addEventListener("click", function() {
                    $('#mapModal').modal('hide');
                });
            }
        }
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
            newInput.classList.add('image-upload-item', 'flex', 'items-center', 'mb-2');
            newInput.innerHTML = `
                <div class="flex items-center mb-4 p-4 bg-white border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:bg-blue-50 transition">
                    <input
                        type="file"
                        name="image[]"
                        accept="image/*"
                        onchange="validateImage(this)"
                        class="flex-grow py-2 px-3 text-sm text-gray-700 border border-slate-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    >

                    <button
                        type="button"
                        onclick="removeImageInput(this)"
                        class="ml-4 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center transition-transform hover:scale-105"
                        title="Hapus gambar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21
                                c.342.052.682.107 1.022.166m-1.022-.165L18.16
                                19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25
                                2.25 0 0 1-2.244-2.077L4.772 5.79m14.456
                                0a48.108 48.108 0 0 0-3.478-.397m-12
                                .562c.34-.059.68-.114 1.022-.165m0 0a48.11
                                48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964
                                51.964 0 0 0-3.32 0c-1.18.037-2.09
                                1.022-2.09 2.201v.916m7.5 0a48.667
                                48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
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


        document.getElementById('form-report').addEventListener('submit', function(e) {
            e.preventDefault();

            // @if (is_null(Auth::user()->nik) || is_null(Auth::user()->image_avatar))
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Data Profil Belum Lengkap',
            //         text: 'Harap lengkapi semua data user (NIK dan Foto Profil) sebelum membuat laporan',
            //         confirmButtonText: 'Ke Halaman Profile',
            //         customClass: {
            //             confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
            //         }
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             window.location.href = '{{ route('profile.edit') }}';
            //         }
            //     });
            //     return;
            // @endif

            Swal.fire({
                title: '<span style="color:#eab308;font-size:2rem;"><i class="fas fa-exclamation-triangle"></i> Peringatan</span>',
                html: `
                <div style="text-align:left; font-size:1rem; color:#374151;">
                <div style="background:#fef3c7; border-left:4px solid #f59e42; padding:16px; border-radius:8px;">
                    <p style="margin-bottom:10px;">
                    <strong>Perhatian!</strong><br>
                    Harap pastikan informasi yang Anda laporkan adalah <span style="color:#16a34a;font-weight:600;">benar</span> dan dapat dipertanggungjawabkan.<br>
                    <span style="color:#dc2626;">Penyebaran laporan palsu atau hoaks merupakan tindakan yang melanggar hukum</span> dan dapat dikenai sanksi pidana sesuai peraturan perundang-undangan yang berlaku.
                    </p>
                    <div style="margin-top:15px; display:flex; align-items:center;">
                    <input type="checkbox" id="confirm-checkbox" style="margin-right:8px; width:18px; height:18px;">
                    <label for="confirm-checkbox" style="cursor:pointer;">Saya telah membaca dan memahami peringatan di atas.</label>
                    </div>
                </div>
                </div>
            `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<span style="padding:0 16px;">Lanjutkan</span>',
                cancelButtonText: 'Batal',
                focusConfirm: false,
                customClass: {
                    popup: 'swal2-border-radius',
                    confirmButton: 'bg-blue-600 !bg-[#007bff] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none',
                    cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none'
                },
                preConfirm: () => {
                    if (!document.getElementById('confirm-checkbox').checked) {
                        Swal.showValidationMessage(
                            'Anda harus menyetujui peringatan ini terlebih dahulu.');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        @include('components.flash-message')
    </script>
@endpush
@endsection

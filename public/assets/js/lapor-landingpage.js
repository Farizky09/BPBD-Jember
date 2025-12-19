

document.addEventListener("DOMContentLoaded", function () {
    geoFindMe();
});

function geoFindMe() {
    const status = document.querySelector("#status");
    const mapLink = document.querySelector("#map-link");
    const mapModal = document.getElementById("mapModal");
    const mapContainer = document.getElementById("map");
    const hiddenSubdistrictField = document.querySelector(
        'input[type="hidden"][name="subdistrict"]'
    );
    const hiddenLatitudeField = document.querySelector(
        'input[name="latitude"][type="hidden"]'
    );
    const hiddenLongitudeField = document.querySelector(
        'input[name="longitude"][type="hidden"]'
    );
    const visibleAddressField = document.querySelector(
        'textarea[name="address"]'
    );

    let map = null;
    let marker = null;

    function initializeMap(latitude, longitude, address = "") {
        map = new google.maps.Map(mapContainer, {
            center: {
                lat: latitude,
                lng: longitude,
            },
            zoom: 15,
        });

        marker = new google.maps.Marker({
            position: {
                lat: latitude,
                lng: longitude,
            },
            map: map,
            draggable: true,
            title: address || "Pilih Lokasi Bencana",
        });

        map.addListener("click", (event) => placeMarker(event.latLng));
        marker.addListener("dragend", (event) => placeMarker(event.latLng));
    }

    function placeMarker(location) {
        const latitude = location.lat();
        const longitude = location.lng();

        if (hiddenLatitudeField) hiddenLatitudeField.value = latitude;
        if (hiddenLongitudeField) hiddenLongitudeField.value = longitude;

        if (visibleAddressField)
            visibleAddressField.value = "Mengambil alamat...";

        fetch("/get-location-data", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                latitude,
                longitude,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    const address = data.display_name;
                    if (visibleAddressField)
                        visibleAddressField.value = address;
                    if (hiddenSubdistrictField) {
                        hiddenSubdistrictField.value = data.subdistrict || "";
                    }
                    status.textContent = "";
                } else {
                    status.textContent = "Gagal mengambil alamat";
                }
            })
            .catch(() => {
                status.textContent = "Gagal mengambil alamat";
            });

        marker.setPosition(location);
        map.panTo(location);
    }

    function success(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;

        if (hiddenLatitudeField) hiddenLatitudeField.value = latitude;
        if (hiddenLongitudeField) hiddenLongitudeField.value = longitude;

        fetch("/get-location-data", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                latitude,
                longitude,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === "success") {
                    const address = data.display_name;
                    if (visibleAddressField)
                        visibleAddressField.value = address;
                    if (hiddenSubdistrictField) {
                        hiddenSubdistrictField.value = data.subdistrict || "";
                    }
                    status.textContent = "";

                    mapLink.textContent = "Pilih Lokasi di Peta";
                    mapLink.addEventListener("click", function (e) {
                        e.preventDefault();
                        if (!map) {
                            initializeMap(latitude, longitude, address);
                        }
                        $("#mapModal").modal("show");
                    });
                } else {
                    status.textContent = "Tidak dapat mengambil alamat";
                }
            })
            .catch(() => {
                status.textContent = "Gagal mengambil alamat";
            });
    }

    function error() {
        status.textContent = "Tidak dapat mengambil lokasi Anda";
    }

    if (navigator.geolocation) {
        status.textContent = "Mengambil lokasi awal...";
        navigator.geolocation.getCurrentPosition(success, error);
    } else {
        status.textContent = "Geolokasi tidak didukung oleh browser ini";
    }
}

document.getElementById("add-image-btn").addEventListener("click", function () {
    const container = document.getElementById("image-upload-container");

    if (container.children.length >= 3) {
        Swal.fire({
            icon: "warning",
            title: "Maksimal 3 Gambar",
            text: "Anda hanya dapat menambahkan maksimal 3 gambar.",
        });
        return;
    }

    const newInput = document.createElement("div");
    newInput.classList.add("image-upload-item", "flex", "items-center", "mb-2");
    newInput.innerHTML = `
            <input type="file" name="image[]" accept="image/*"
                   class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                   multiple />
            <button type="button" class="ml-2 text-red-500 hover:text-red-700"
                    onclick="removeImageInput(this)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        `;

    container.appendChild(newInput);
});

function removeImageInput(button) {
    const container = button.closest("#image-upload-container");
    const item = button.closest(".image-upload-item");
    if (container.children.length > 1) {
        item.remove();
    } else {
        Swal.fire({
            icon: "warning",
            title: "Oops...",
            text: "Minimal harus ada satu gambar!",
        });
    }
}
document.addEventListener("change", function (e) {
    if (e.target.matches('input[type="file"][name="image[]"]')) {
        const files = Array.from(e.target.files);
        const validTypes = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/jpg",
        ];

        for (const file of files) {
            if (!validTypes.includes(file.type)) {
                Swal.fire({
                    icon: "error",
                    title: "Format Tidak Didukung",
                    text: `"${file.name}" bukan file gambar valid (JPEG, PNG, GIF).`,
                });
                e.target.value = "";
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire({
                    icon: "error",
                    title: "Ukuran Terlalu Besar",
                    text: `"${file.name}" melebihi batas ukuran 2MB.`,
                });
                e.target.value = "";
                return;
            }
        }
    }
});
document.getElementById("form-report").addEventListener("submit", function (e) {
    e.preventDefault();

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
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: '<span style="padding:0 16px;">Lanjutkan</span>',
        cancelButtonText: "Batal",
        focusConfirm: false,
        customClass: {
            popup: "swal2-border-radius",
            confirmButton:
                "bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none",
            cancelButton:
                "bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none",
        },
        preConfirm: () => {
            if (!document.getElementById("confirm-checkbox").checked) {
                Swal.showValidationMessage(
                    "Anda harus menyetujui peringatan ini terlebih dahulu."
                );
                return false;
            }
            return true;
        },
    }).then((result) => {
        if (result.isConfirmed) {
            this.submit();
        }
    });
});


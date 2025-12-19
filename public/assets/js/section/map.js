let map;
let userMarker;

function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const userPos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude,
                };

                map = new google.maps.Map(document.getElementById("petamap"), {
                    center: userPos,
                    zoom: 13,
                });

                userMarker = new google.maps.Marker({
                    position: userPos,
                    map: map,
                    icon: {
                        url: window.ASSET_URLS.pinMapIcon,
                        scaledSize: new google.maps.Size(30, 30),
                    },
                    title: "Lokasi Anda",
                });

                const userInfo = new google.maps.InfoWindow({
                    content: "Lokasi Anda",
                });
                userMarker.addListener("click", () => {
                    userInfo.open(map, userMarker);
                });

                fetch("/get-disaster-marker")
                    .then((response) => response.json())
                    .then((data) => {
                        // console.log("Data marker:", data);
                        if (
                            data.status === "success" &&
                            Array.isArray(data.data)
                        ) {
                            data.data.forEach((item) => {
                                // console.log(item);
                                const reports = item.reports;
                                const disaster_impacts = item.disaster_impacts;
                                // console.log("disini ", disaster_impacts);
                                // console.log("disini ", reports);
                                if (!item.reports) return;
                                const pos = {
                                    lat: parseFloat(reports.latitude),
                                    lng: parseFloat(reports.longitude),
                                };

                                const marker = new google.maps.Marker({
                                    position: pos,
                                    map: map,
                                    icon: {
                                        url: window.ASSET_URLS
                                            .informationPointIcon,
                                        scaledSize: new google.maps.Size(
                                            25,
                                            25
                                        ),
                                    },
                                    title:
                                        reports.disaster_category.name ||
                                        "Lokasi Bencana",
                                });
                                let detailLink = "";
                                if (
                                    window.USER_ROLE.includes("super_admin") ||
                                    (window.USER_ROLE.includes("admin") &&
                                        window.USER_ID == item.admin_id)
                                ) {
                                    detailLink = `
                                        <a href="/confirm-reports/detail/${item.id}"
                                            class="text-blue-500 hover:underline mt-2 block">
                                            Lihat Detail Laporan
                                        </a>
                                    `;
                                }

                                const infoWindow = new google.maps.InfoWindow({
                                    content: `
                                        <div style="min-width:220px; font-family:inherit;">
                                            <div style="font-size:1.1rem; font-weight:bold; margin-bottom:6px;">
                                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                                ${
                                                    reports.disaster_category
                                                        .name
                                                }
                                            </div>
                                            <div style="margin-bottom:4px;">
                                                <i class="fas fa-calendar-alt text-primary"></i>
                                                <strong>Tanggal:</strong> ${
                                                    reports.created_at_formatted
                                                }
                                            </div>
                                            <div style="margin-bottom:4px;">
                                                <i class="fas fa-map-marker-alt text-danger"></i>
                                                <span>${reports.address}</span>
                                            </div>
                                            <hr style="margin:8px 0;">
                                            
                                            <div style="margin-bottom:2px;">
                                                <i class="fas fa-skull-crossbones text-danger"></i>
                                                <strong>Meninggal:</strong> ${
                                                    disaster_impacts.deceased_people ??
                                                    0
                                                }
                                            </div>
                                            <div style="margin-bottom:2px;">
                                                <i class="fas fa-user-injured text-warning"></i>
                                                <strong>Terluka:</strong> ${
                                                    disaster_impacts.injured_people ??
                                                    0
                                                }
                                            </div>
                                            <div>
                                                <i class="fas fa-user-secret text-secondary"></i>
                                                <strong>Hilang:</strong> ${
                                                    disaster_impacts.missing_people ??
                                                    0
                                                }
                                            </div>
                                            <div>${detailLink}</div>
                                        </div>
                                    `,
                                });

                                marker.addListener("click", () => {
                                    infoWindow.open(map, marker);
                                });
                            });
                        } else {
                            console.warn(
                                "Data marker tidak tersedia atau format salah."
                            );
                        }
                    })
                    .catch((error) => {
                        console.error("Gagal mengambil data marker:", error);
                    });
            },
            function (error) {
                document.getElementById("status").textContent =
                    "Gagal mendapatkan lokasi Anda.";
                console.error("Geolocation error:", error);
            }
        );
    } else {
        document.getElementById("status").textContent =
            "Browser tidak mendukung geolocation.";
    }
}

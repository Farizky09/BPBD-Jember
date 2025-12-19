const sebelumBencanaBtn = document.getElementById("sebelumBencanaBtn");
const saatBencanaBtn = document.getElementById("saatBencanaBtn");
const setelahBencanaBtn = document.getElementById("setelahBencanaBtn");
const konsultasiAiBtn = document.getElementById("konsultasiAiBtn");
const searchContainer = document.getElementById("search-container");
const sebelumBencanaForm = document.getElementById("sebelumBencanaForm");
const saatBencanaForm = document.getElementById("saatBencanaForm");
const setelahBencanaForm = document.getElementById("setelahBencanaForm");
const konsultasiAi = document.getElementById("konsultasiAi");

const jenisSearchInput = document.getElementById("jenis-search");

function setActiveTab(activeBtn) {
    document.querySelectorAll(".tab-btn").forEach((btn) => {
        btn.classList.remove("border-blue-600", "font-semibold");
        btn.classList.add("border-transparent");
    });
    activeBtn.classList.remove("border-transparent");
    activeBtn.classList.add("border-blue-600", "font-semibold");
}

async function fetchConsultations(type, searchTerm = "") {
    const url = `/konsultasi/fetch?type=${type}&search=${searchTerm}`;
    const targetContainer = document.getElementById(`${type}-bencana-results`);
    const notFoundDiv = document.getElementById(`not-found-${type}`);

    targetContainer.innerHTML =
        '<p class="text-center text-gray-500 mt-4">Loading...</p>';
    notFoundDiv.classList.add("hidden");

    try {
        const response = await fetch(url);
        const data = await response.json();
        if (data.consultations.length > 0) {
            let html =
                '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">';
            data.consultations.forEach((consul) => {
                const videoPath = consul.video_path || "";
                const isYoutubeLink = videoPath.includes("youtube.com/embed");
                const videoEmbedSrc = isYoutubeLink ? videoPath : "";
                const videoHiddenClass = isYoutubeLink ? "" : "hidden";

                html += `
                    <div class="flex flex-col">
                        <div id="video-preview-container"
                            class="konsultasi-item-${type} ${videoHiddenClass} mt-4"
                            data-type="${consul.type}" data-name="${
                    consul.consultations?.name || ""
                }">
                            <div class="relative h-48 w-full overflow-hidden rounded-lg shadow-md">
                                <iframe id="video-preview" class="absolute top-0 left-0 w-full h-full"
                                    src="${videoEmbedSrc}"
                                    frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                        ${
                            videoPath && !isYoutubeLink
                                ? `
                                            <p class="text-yellow-600 text-sm mt-2 text-center">
                                                Format video tidak didukung untuk preview.<br>
                                                Masukkan format <code>youtube.com/embed</code>.
                                            </p>
                                        `
                                : ""
                        }
                        ${
                            !videoPath
                                ? `
                                            <p class="text-sm text-gray-500 italic mt-2 text-center">
                                                Belum ada video ditambahkan.
                                            </p>
                                        `
                                : ""
                        }
                        <h6 class="text-sm font-semibold mt-2 text-center hidden">${
                            consul.consultations?.name || "-"
                        }</h6>
                    </div>
                `;
            });
            html += "</div>";
            targetContainer.innerHTML = html;
            notFoundDiv.classList.add("hidden");
        } else {
            targetContainer.innerHTML = "";
            notFoundDiv.classList.remove("hidden");
        }
    } catch (error) {
        console.error("Error fetching consultations:", error);
        targetContainer.innerHTML =
            '<p class="text-red-500 text-center mt-4">Terjadi kesalahan saat memuat data.</p>';
    }
}

sebelumBencanaBtn.addEventListener("click", function () {
    jenisSearchInput.value = "";
    sebelumBencanaForm.classList.remove("hidden");
    saatBencanaForm.classList.add("hidden");
    setelahBencanaForm.classList.add("hidden");
    konsultasiAi.classList.add("hidden");
    setActiveTab(sebelumBencanaBtn);
    searchContainer.classList.remove("hidden");
    fetchConsultations("before", jenisSearchInput.value);
});

saatBencanaBtn.addEventListener("click", function () {
    jenisSearchInput.value = "";
    saatBencanaForm.classList.remove("hidden");
    sebelumBencanaForm.classList.add("hidden");
    setelahBencanaForm.classList.add("hidden");
    konsultasiAi.classList.add("hidden");
    searchContainer.classList.remove("hidden");
    setActiveTab(saatBencanaBtn);
    fetchConsultations("during", jenisSearchInput.value);
});

setelahBencanaBtn.addEventListener("click", function () {
    jenisSearchInput.value = "";
    setelahBencanaForm.classList.remove("hidden");
    saatBencanaForm.classList.add("hidden");
    sebelumBencanaForm.classList.add("hidden");
    konsultasiAi.classList.add("hidden");
    searchContainer.classList.remove("hidden");
    setActiveTab(setelahBencanaBtn);
    fetchConsultations("after", jenisSearchInput.value);
});

konsultasiAiBtn.addEventListener("click", function () {
    konsultasiAi.classList.remove("hidden");
    saatBencanaForm.classList.add("hidden");
    searchContainer.classList.add("hidden");
    setelahBencanaForm.classList.add("hidden");
    sebelumBencanaForm.classList.add("hidden");
    setActiveTab(konsultasiAiBtn);
});

window.addEventListener("DOMContentLoaded", function () {
    setActiveTab(sebelumBencanaBtn);
    sebelumBencanaForm.classList.remove("hidden");
    fetchConsultations("before", jenisSearchInput.value);
});

jenisSearchInput.addEventListener("input", function (e) {
    const searchTerm = e.target.value.toLowerCase();
    const activeTab = document.querySelector(".tab-btn.border-blue-600");

    let currentType = "";
    if (activeTab.id === "sebelumBencanaBtn") {
        currentType = "before";
    } else if (activeTab.id === "saatBencanaBtn") {
        currentType = "during";
    } else if (activeTab.id === "setelahBencanaBtn") {
        currentType = "after";
    } else if (activeTab.id === "konsultasiAiBtn") {
        return; // Do nothing if AI tab is active
    }
    if (currentType) {
        fetchConsultations(currentType, searchTerm);
    }
});

document
    .getElementById("dropzone-file")
    .addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file && /\.(svg|png|jpeg|jpg|gif)$/i.test(file.name)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const imagePreview = document.getElementById("image-preview");
                const previewImage = document.getElementById("preview-image");
                const dropzoneContent =
                    document.getElementById("dropzone-content");

                if (file.type === "image/svg+xml") {
                    previewImage.src = e.target.result;
                    imagePreview.classList.remove("hidden");
                    dropzoneContent.classList.add("hidden");
                    sendImageToGemini(file);
                } else {
                    const img = new Image();
                    img.onload = function () {
                        const canvas = document.createElement("canvas");
                        const ctx = canvas.getContext("2d");

                        canvas.width = 600;
                        canvas.height = 300;

                        ctx.drawImage(img, 0, 0, 600, 300);

                        canvas.toBlob(
                            function (blob) {
                                const resizedFile = new File(
                                    [blob],
                                    file.name,
                                    {
                                        type: file.type,
                                        lastModified: Date.now(),
                                    }
                                );

                                previewImage.src = URL.createObjectURL(blob);
                                imagePreview.classList.remove("hidden");
                                dropzoneContent.classList.add("hidden");

                                sendImageToGemini(resizedFile);
                            },
                            file.type,
                            0.8
                        );
                    };
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        } else {
            Swal.fire({
                icon: "error",
                title: "File type not supported",
                text: "Please upload a SVG, PNG, JPEG, or GIF file.",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
            });
        }
    });

function removeImage() {
    const imagePreview = document.getElementById("image-preview");
    const previewImage = document.getElementById("preview-image");
    const dropzoneContent = document.getElementById("dropzone-content");

    previewImage.src = "";
    imagePreview.classList.add("hidden");
    dropzoneContent.classList.remove("hidden");
    document.getElementById("dropzone-file").value = "";
    document.getElementById("result-textarea").value = "";
}

async function sendImageToGemini(file) {
    const loadingIcon = document.getElementById("loading-icon");
    const jenisTextarea = document.getElementById("jenis-textarea");
    const dampakTextarea = document.getElementById("dampak-textarea");
    const penangananTextarea = document.getElementById("penanganan-textarea");

    loadingIcon.classList.remove("hidden");
    jenisTextarea.value = "";
    dampakTextarea.value = "";
    penangananTextarea.value = "";

    const formData = new FormData();
    formData.append("image", file);

    try {
        const response = await fetch("/process-gemini-ai", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: formData,
        });

        const data = await response.json();

        if (data.status === "success") {
            const result = data.result;
            const jenisMatch = result.match(/\[JENIS\]([\s\S]*?)\[DAMPAK\]/);
            const dampakMatch = result.match(
                /\[DAMPAK\]([\s\S]*?)\[PENANGANAN\]/
            );
            const penangananMatch = result.match(/\[PENANGANAN\]([\s\S]*)/);

            jenisTextarea.value = jenisMatch
                ? jenisMatch[1].replace(/\*/g, "").trim()
                : "Tidak ada jenis bencana yang dihasilkan.";
            dampakTextarea.value = dampakMatch
                ? dampakMatch[1].replace(/\*/g, "").trim()
                : "Tidak ada dampak bencana yang dihasilkan.";
            penangananTextarea.value = penangananMatch
                ? penangananMatch[1].replace(/\*/g, "").trim()
                : "Tidak ada penanganan bencana yang dihasilkan.";
        } else {
            jenisTextarea.value =
                "Tidak dapat menghasilkan jenis bencana dari gambar ini.";
            dampakTextarea.value =
                "Tidak dapat menghasilkan dampak bencana dari gambar ini.";
            penangananTextarea.value =
                "Tidak dapat menghasilkan penanganan bencana dari gambar ini.";
        }
    } catch (error) {
        console.error(error);
        jenisTextarea.value = "Terjadi kesalahan saat memproses gambar.";
        dampakTextarea.value = "Terjadi kesalahan saat memproses gambar.";
        penangananTextarea.value = "Terjadi kesalahan saat memproses gambar.";
    } finally {
        loadingIcon.classList.add("hidden");
    }
}

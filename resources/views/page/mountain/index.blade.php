@include('page.components.head')
@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="index-page mt-4">
    @include('page.components.header')
    <section id="stream" class="stream section">
        <div class="container mx-auto px-4 max-w-7xl  mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">

                    <div class="industrial-card p-1">
                        <div class="bg-gray-900 rounded-xl overflow-hidden shadow-lg">

                            <!-- Top Bar -->
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-800">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                </div>
                                <span class="text-gray-300 text-sm font-medium tracking-wide">
                                    LIVE STREAM — VOLCANO MONITORING
                                </span>
                                <div class="w-16"></div>
                            </div>

                            <!-- Stream -->
                            <img src="http://127.0.0.1:5001/video-stream" id="volcanoStream"
                                class="w-full aspect-video object-cover" alt="Volcano Live Stream">
                        </div>
                    </div>

                    <div class="industrial-card pl-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-2"></i> Waktu Terkini
                            </h3>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-4xl font-bold text-gray-900 tracking-tight font-mono" id="lastUpdate">
                                    --:--:--
                                </p>
                                <p class="text-sm text-gray-500 mt-2 flex items-center">
                                    <i class="fas fa-sync-alt text-green-500 mr-2 animate-spin"></i>
                                    Real-time update
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT: STATUS PANEL -->
                <div class="space-y-6">

                    <div class="industrial-card p-6 bg-white rounded-xl shadow-lg border border-gray-200">

                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Volcano Status</h2>
                            <i class="fas fa-mountain text-gray-400 text-2xl"></i>
                        </div>

                        <!-- Dynamic Status Badge -->
                        <div class="mb-6">
                            <div id="statusBadge"
                                class="inline-flex items-center px-4 py-3 rounded-lg font-semibold w-full justify-center text-lg 
                             bg-gray-100 text-gray-800 border border-gray-300 shadow-sm">
                                <div class="animate-pulse mr-2 w-3 h-3 rounded-full bg-gray-400"></div>
                                Loading...
                            </div>
                        </div>

                        <!-- Legend -->
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Status Levels
                        </h3>

                        <div class="space-y-3">

                            <!-- NORMAL -->
                            <div class="flex items-center p-3 rounded-lg bg-green-50 border border-green-200">
                                <div class="w-3 h-3 rounded-full bg-green-500 mr-3"></div>
                                <div>
                                    <span class="font-medium text-green-900">NORMAL</span>
                                    <p class="text-xs text-green-700">Aktivitas gunung stabil dan tidak menunjukkan
                                        tanda-tanda erupsi.</p>
                                </div>
                            </div>

                            <!-- waspada -->
                            <div class="flex items-center p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                                <div class="w-3 h-3 rounded-full bg-yellow-500 mr-3"></div>
                                <div>
                                    <span class="font-medium text-yellow-900">WASPADA</span>
                                    <p class="text-xs text-yellow-700">Terpantau munculnya asap, indikasi awal
                                        peningkatan
                                        aktivitas vulkanik.</p>
                                </div>
                            </div>

                            <!-- siaga -->
                            <div class="flex items-center p-3 rounded-lg bg-orange-50 border border-orange-200">
                                <div class="w-3 h-3 rounded-full bg-orange-500 mr-3"></div>
                                <div>
                                    <span class="font-medium text-orange-900">SIAGA</span>
                                    <p class="text-xs text-orange-700">Aktivitas meningkat dengan asap yang lama,
                                        mengarah
                                        pada erupsi.</p>
                                </div>
                            </div>

                            <!-- AWAS / LAVA -->
                            <div class="flex items-center p-3 rounded-lg bg-red-50 border border-red-200">
                                <div class="w-3 h-3 rounded-full bg-red-500 mr-3"></div>
                                <div>
                                    <span class="font-medium text-red-900">AWAS</span>
                                    <p class="text-xs text-red-700">Gunung mengeluarkan lava.</p>
                                </div>
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>
    </section>

    @include('page.components.footer')

    <script>
        async function updateStatus() {
            try {
                const response = await fetch("http://127.0.0.1:5001/get-status");
                const res = await response.json();
                const badge = document.getElementById("statusBadge");

                const styleMap = {
                    NORMAL: "bg-green-100 text-green-800 border border-green-200",
                    WASPADA: "bg-yellow-100 text-yellow-800 border border-yellow-200",
                    SIAGA: "bg-orange-100 text-orange-800 border border-orange-200",
                    AWAS: "bg-red-100 text-red-800 border border-red-200"
                };

                const iconMap = {
                    NORMAL: "fas fa-check-circle text-green-500",
                    WASPADA: "fas fa-exclamation-triangle text-yellow-500",
                    SIAGA: "fas fa-exclamation-circle text-orange-500",
                    AWAS: "fas fa-fire text-red-500"
                };

                badge.className =
                    `inline-flex items-center px-4 py-3 rounded-lg font-semibold w-full justify-center text-lg shadow-sm 
            ${styleMap[res.status]}`;

                badge.innerHTML = `<i class="${iconMap[res.status]} mr-2"></i> ${res.status}`;

                // Update time
                document.getElementById('lastUpdate').textContent =
                    new Date().toLocaleTimeString();

            } catch (e) {
                console.log("Status update error:", e);
            }
        }

        // Initial load
        updateStatus();
        setInterval(updateStatus, 1000);
    </script>

</body>

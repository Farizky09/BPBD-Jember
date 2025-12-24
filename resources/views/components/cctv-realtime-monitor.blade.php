<div id="cctvDashboard" class="cctv-dashboard">
    <!-- Status Monitoring -->
    <div class="status-card mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Status Monitoring</h3>
                <span id="statusBadge" class="badge badge-danger">Offline</span>
            </div>
            <small id="lastUpdate">Terakhir update: -</small>
        </div>
    </div>

    <!-- Water Level Display -->
    <div id="waterLevelCard" class="water-level-card mb-4">
        <div class="level-display">
            <h2>Tingkat Air</h2>
            <div class="level-value">
                <span id="waterLevel">--</span>
                <span class="unit">meter</span>
            </div>
            <div class="level-bar-container">
                <div id="levelBar" class="level-bar" style="height: 0%"></div>
            </div>
        </div>
    </div>

    <!-- Image Display -->
    <div class="image-card mb-4">
        <h3>Tangkapan Terbaru</h3>
        <div id="imageContainer" class="image-container">
            <img id="cctvImage" src="" alt="CCTV Image" class="img-fluid" style="display: none;">
            <p id="noImage" class="text-muted">Menunggu gambar...</p>
        </div>
    </div>



</div>

<style>
    .cctv-dashboard {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .status-card,
    .water-level-card,
    .image-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-size: 0.9rem;
        padding: 5px 10px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .level-display {
        text-align: center;
    }

    .level-value {
        font-size: 48px;
        font-weight: bold;
        color: #0066cc;
        margin: 15px 0;
    }

    .unit {
        font-size: 24px;
        color: #666;
    }

    .level-bar-container {
        width: 100%;
        height: 30px;
        background: #e0e0e0;
        border-radius: 15px;
        overflow: hidden;
        margin-top: 15px;
    }

    .level-bar {
        background: linear-gradient(90deg, #00cc00, #ffcc00, #ff6600, #ff0000);
        width: 100%;
        transition: height 0.3s ease;
    }

    .image-container {
        background: #f5f5f5;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .img-fluid {
        max-height: 500px;
        border-radius: 4px;
    }

    .spinner {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #0066cc;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    class CctvRealTimeMonitor {
        constructor(options = {}) {
            this.apiBaseUrl = options.apiBaseUrl || '/api/cctv';
            this.refreshInterval = options.refreshInterval || 5000;
            this.latestData = null;
            this.statusData = null;
            this.historyData = [];

            this.start();
        }

        start() {
            this.fetchData();
            setInterval(() => this.fetchData(), this.refreshInterval);
        }

        async fetchData() {
            try {
                console.log("📡 Fetching CCTV data...");

                const [latestRes, statusRes, historyRes] = await Promise.all([
                    fetch(`${this.apiBaseUrl}/latest`),
                    fetch(`${this.apiBaseUrl}/status`),
                    fetch(`${this.apiBaseUrl}/all?limit=10`)
                ]);

                const latest = await latestRes.json();
                const status = await statusRes.json();
                const history = await historyRes.json();

                this.latestData = latest?.data ?? null;
                this.statusData = status?.data ?? null;
                this.historyData = history?.data ?? [];

                this.updateStatusUI();
                this.updateContentUI();
                this.updateImageUI();



            } catch (e) {
                console.error("❌ Error:", e);
                this.showOffline();
            }
        }

        // ================= STATUS ==================
        updateStatusUI() {
            const badge = document.getElementById('statusBadge');
            const isOnline = this.statusData && this.statusData.status === "online";

            if (isOnline) {
                badge.className = "badge badge-success";
                badge.textContent = "✓ Online";
            } else {
                badge.className = "badge badge-danger";
                badge.textContent = "✗ Offline";
            }

            if (this.statusData?.time_ago_seconds !== undefined) {
                const s = this.statusData.time_ago_seconds;
                badge.title = s < 60 ? `${s}s ago` : `${Math.floor(s/60)}m ago`;
            }
        }

        // ================= CONTENT ==================
        updateContentUI() {
            const isOnline = this.statusData?.status === "online";

            if (!isOnline) return this.showOffline();
            if (!this.latestData) return;

            // Water Level
            this.updateWaterLevel(this.latestData.level_meter);

            // Last update
            this.updateLastUpdate(this.latestData.timestamp);

            document.getElementById("waterLevelCard").style.display = "block";
        }

        showOffline() {
            document.getElementById("waterLevelCard").style.display = "none";
            document.getElementById("lastUpdate").textContent = "⚠️ System Offline - No Data";
        }

        // ================= WATER LEVEL ==================
        updateWaterLevel(levelMeter) {
            const value = parseFloat(levelMeter || 0);
            document.getElementById("waterLevel").textContent = value.toFixed(3);

            const bar = Math.min((value / 2.0) * 100, 100);
            document.getElementById("levelBar").style.height = bar + "%";

            console.log(`💧 Level: ${value}m`);
        }

        // ================= IMAGE ==================
        updateImageUI() {
            // 1. Validasi jika data / URL kosong
            if (!this.latestData || !this.latestData.image_url) {
                console.warn("⚠️ Data image URL kosong di respon JSON");
                return;
            }

            const img = document.getElementById("cctvImage");
            const noImg = document.getElementById("noImage");

            // 2. Ambil URL langsung dari JSON
            // JANGAN melakukan replace slash (\ ke /) pada full URL http://...
            // karena itu akan merusak encoding URL (%5C) yang sudah benar dari server.
            let url = this.latestData.image_url;

            // 3. Tambahkan Cache Busting dengan Cerdas
            // Cek apakah URL sudah punya tanda '?' (query param)
            // JSON Anda punya parameter '?path=', jadi kita harus pakai '&' untuk sambung timestamp
            const separator = url.includes('?') ? '&' : '?';
            const finalUrl = `${url}${separator}t=${Date.now()}`;

            console.log("📸 Loading URL:", finalUrl);

            // 4. Setup Event Handlers
            img.onload = () => {
                img.style.display = "block";
                noImg.style.display = "none";
                console.log("✅ Gambar berhasil dimuat");
            };

            img.onerror = () => {
                img.style.display = "none";
                noImg.style.display = "block";
                noImg.textContent = "Gagal memuat gambar (404/500)";
                console.error("❌ Gagal load gambar dari:", finalUrl);
            };

            // 5. Set Source
            img.src = finalUrl;
        }

        // ================= TIMESTAMP ==================
        updateLastUpdate(timestamp) {
            if (!timestamp) return;
            const t = new Date(timestamp);
            document.getElementById("lastUpdate").textContent =
                "Terakhir update: " + t.toLocaleString("id-ID");
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        new CctvRealTimeMonitor({
            apiBaseUrl: "/api/cctv",
            refreshInterval: 1000
        });
    });
</script>

{{-- <script>
    /**
     * CCTV Real-Time Monitoring System
     * Flow:
     * 1. Ambil data dari CSV via API
     * 2. Cek status (online/offline)
     * 3. Jika online -> tampilkan semua data
     * 4. Jika offline -> tidak tampilkan data (tapi image tetap)
     */

    class CctvRealTimeMonitor {
        constructor(options = {}) {
            this.apiBaseUrl = options.apiBaseUrl || '/api/cctv';
            this.refreshInterval = options.refreshInterval || 5000;
            this.init();
        }

        // ============================================
        // STEP 1: INITIALIZE & START MONITORING
        // ============================================
        init() {
            console.log('🎥 Initializing CCTV Real-Time Monitor...');
            this.startMonitoring();
            setInterval(() => this.fetchData(), this.refreshInterval);
        }

        async startMonitoring() {
            await this.fetchData();
        }

        // ============================================
        // STEP 2: AMBIL DATA DARI CSV (via API)
        // ============================================
        async fetchData() {
            try {
                console.log('📡 Fetching CCTV data...');

                // Parallel requests untuk efficiency
                const [latestResponse, statusResponse, historyResponse] = await Promise.all([
                    fetch(`${this.apiBaseUrl}/latest`),
                    fetch(`${this.apiBaseUrl}/status`),
                    fetch(`${this.apiBaseUrl}/all?limit=10`)
                ]);

                const latestData = await latestResponse.json();
                const statusData = await statusResponse.json();
                const historyData = await historyResponse.json();

                // Simpan data
                this.latestData = latestData.data || null;
                this.statusData = statusData.data || null;
                this.historyData = historyData.data || [];

                console.log('✅ Data fetched:', {
                    latest: this.latestData,
                    status: this.statusData,
                    history_count: this.historyData.length
                });

                // STEP 3: Proses display berdasarkan status
                this.processDisplay();

            } catch (error) {
                console.error('❌ Error fetching data:', error);
                this.showOfflineState();
            }
        }

        // ============================================
        // STEP 3: PROSES DISPLAY BERDASARKAN STATUS
        // ============================================
        processDisplay() {
            // Cek status
            const isOnline = this.statusData && this.statusData.status === 'online';

            console.log(`📊 Status: ${isOnline ? 'ONLINE ✓' : 'OFFLINE ✗'}`);

            // Update status badge
            this.updateStatusBadge(isOnline);

            if (isOnline) {
                // JIKA ONLINE: Tampilkan semua data
                console.log('✓ System online - Showing all data');
                this.showOnlineState();
            } else {
                // JIKA OFFLINE: Jangan tampilkan data
                console.log('✗ System offline - Hiding data');
                this.showOfflineState();
            }

            // IMAGE SELALU TAMPILKAN
            this.updateImage();
        }

        // ============================================
        // FUNCTION: UPDATE STATUS BADGE
        // ============================================
        updateStatusBadge(isOnline) {
            const badge = document.getElementById('statusBadge');

            if (isOnline) {
                badge.className = 'badge badge-success';
                badge.textContent = '✓ Online';
            } else {
                badge.className = 'badge badge-danger';
                badge.textContent = '✗ Offline';
            }

            // Tooltip: show time ago
            if (this.statusData && this.statusData.time_ago_seconds !== undefined) {
                const seconds = this.statusData.time_ago_seconds;
                const timeText = seconds < 60 ?
                    `${seconds}s ago` :
                    `${Math.floor(seconds / 60)}m ago`;
                badge.title = `Last update: ${timeText}`;
            }
        }

        // ============================================
        // FUNCTION: TAMPILKAN STATE ONLINE
        // ============================================
        showOnlineState() {
            if (!this.latestData) return;

            // 1. Tampilkan water level
            this.updateWaterLevel(this.latestData.level_meter);

            // 2. Tampilkan last update time
            this.updateLastUpdateTime(this.latestData.timestamp);

            // Show data containers
            document.getElementById('waterLevelCard').style.display = 'block';
        }

        // ============================================
        // FUNCTION: TAMPILKAN STATE OFFLINE
        // ============================================
        showOfflineState() {
            // Hide data containers
            document.getElementById('waterLevelCard').style.display = 'none';

            // Show offline message
            document.getElementById('lastUpdate').textContent = '⚠️ System Offline - No Data';
        }

        // ============================================
        // FUNCTION: UPDATE WATER LEVEL
        // ============================================
        updateWaterLevel(levelMeter) {
            const level = parseFloat(levelMeter).toFixed(3);
            document.getElementById('waterLevel').textContent = level;

            // Update visual bar (0-2m)
            const barHeight = Math.min((parseFloat(levelMeter) / 2.0) * 100, 100);
            document.getElementById('levelBar').style.height = barHeight + '%';

            console.log(`💧 Water Level: ${level}m (${barHeight.toFixed(1)}%)`);
        }

        // ============================================
        // FUNCTION: UPDATE IMAGE (SELALU TAMPILKAN)
        // ============================================
        updateImage() {
            // Validasi data kosong
            if (!this.latestData || !this.latestData.image_url) {
                console.log(this.latestData.image_url, "Data image kosong");
                return;
            }

            const img = document.getElementById('cctvImage');
            const noImg = document.getElementById('noImage');

            // Bersihkan URL dari backslash aneh
            let rawUrl = this.latestData.image_url;
            let finalUrl = rawUrl.replace(/\\/g, '/'); // Ubah \ jadi /

            // Tambah timestamp agar tidak cache
            finalUrl = finalUrl + '?t=' + new Date().getTime();

            console.log("Mencoba load gambar dari URL:", finalUrl);

            img.onload = function() {
                console.log("Gambar berhasil dimuat!");
                img.style.display = 'block'; // TAMPILKAN
                noImg.style.display = 'none';
            };

            img.onerror = function() {
                console.error("Gagal memuat gambar.");
                // Tetap tampilkan icon broken image supaya bisa di-inspect
                img.style.display = 'block';
                img.alt = "Gagal memuat: " + finalUrl;
                noImg.textContent = "Error Loading Image";
            };

            img.src = finalUrl;
        }

        // ============================================
        // FUNCTION: UPDATE LAST UPDATE TIME
        // ============================================
        updateLastUpdateTime(timestamp) {
            if (!timestamp) return;

            const date = new Date(timestamp);
            const timeStr = date.toLocaleString('id-ID');
            document.getElementById('lastUpdate').textContent = `Terakhir update: ${timeStr}`;
        }


        // ============================================
        // FUNCTION: VIEW SPECIFIC IMAGE
        // ============================================
        viewImage(imageUrl) {
            if (!imageUrl) return;

            const img = document.getElementById('cctvImage');
            img.src = imageUrl;
            img.style.display = 'block';
            document.getElementById('noImage').style.display = 'none';

            // Scroll to image
            document.getElementById('imageContainer').scrollIntoView({
                behavior: 'smooth'
            });
        }
    }

    // ============================================
    // INITIALIZE MONITORING
    // ============================================
    let cctvMonitor;
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Starting CCTV Monitoring System...');
        cctvMonitor = new CctvRealTimeMonitor({
            apiBaseUrl: '/api/cctv',
            refreshInterval: 5000
        });
    });
</script> --}}

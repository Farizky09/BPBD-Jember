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
            this.refreshInterval = options.refreshInterval || 10000; // Naikkan ke 10 detik
            this.isLoading = false;
            this.retryCount = 0;
            this.maxRetries = 3;

            // Cache untuk gambar
            this.lastImageUrl = null;
            this.imageCacheTime = 0;

            this.start();
        }

        start() {
            this.fetchDashboard();
            // Gunakan setInterval dengan pengecekan loading
            setInterval(() => {
                if (!this.isLoading) {
                    this.fetchDashboard();
                }
            }, this.refreshInterval);
        }

        async fetchDashboard() {
            if (this.isLoading) return;

            this.isLoading = true;

            try {
                console.log("📡 Fetching dashboard data...");

                // Gunakan endpoint dashboard yang baru
                const response = await fetch(`${this.apiBaseUrl}/dashboard`);

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    this.retryCount = 0; // Reset retry count
                    this.updateUI(result.data);
                } else {
                    throw new Error(result.message || 'Failed to fetch data');
                }

            } catch (e) {
                console.error("❌ Error:", e);
                this.handleError(e);
            } finally {
                this.isLoading = false;
            }
        }

        updateUI(data) {
            const {
                status,
                latest,
                history
            } = data;

            // Update status
            this.updateStatus(status);

            // Update content jika online
            if (status.status === "online") {
                this.updateWaterLevel(latest.level_meter);
                this.updateLastUpdate(latest.timestamp);
                this.updateImage(latest.image_url, latest.timestamp);
                document.getElementById("waterLevelCard").style.display = "block";
            } else {
                this.showOffline();
            }

            // Update history jika diperlukan
            this.updateHistory(history);
        }

        updateStatus(status) {
            const badge = document.getElementById('statusBadge');

            if (status.status === "online") {
                badge.className = "badge badge-success";
                badge.textContent = "✓ Online";
            } else {
                badge.className = "badge badge-danger";
                badge.textContent = "Offline";
            }

            // Update time ago
            if (status.time_ago_seconds !== undefined) {
                const timeAgo = this.formatTimeAgo(status.time_ago_seconds);
                badge.title = `${timeAgo} yang lalu`;
            }
        }

        updateWaterLevel(levelMeter) {
            const value = parseFloat(levelMeter || 0);
            const levelElement = document.getElementById("waterLevel");

            if (levelElement) {
                levelElement.textContent = value.toFixed(3);
            }

            // Update progress bar
            const bar = Math.min((value / 2.0) * 100, 100);
            const levelBar = document.getElementById("levelBar");

            if (levelBar) {
                levelBar.style.height = bar + "%";
            }
        }

        updateImage(imageUrl, timestamp) {
            // Jangan reload gambar jika sama dengan sebelumnya dan belum 30 detik
            const now = Date.now();
            if (imageUrl === this.lastImageUrl && (now - this.imageCacheTime) < 30000) {
                return; // Skip, gambar masih fresh
            }

            const img = document.getElementById("cctvImage");
            const noImg = document.getElementById("noImage");

            if (!img) return;

            // Cache gambar
            this.lastImageUrl = imageUrl;
            this.imageCacheTime = now;

            // Setup handlers
            img.onload = () => {
                img.style.display = "block";
                if (noImg) noImg.style.display = "none";
                console.log("✅ Gambar berhasil dimuat");
            };

            img.onerror = () => {
                img.style.display = "none";
                if (noImg) {
                    noImg.style.display = "block";
                    noImg.textContent = "Gambar tidak tersedia";
                }
                console.error("❌ Gagal memuat gambar");
            };

            // Load gambar dengan cache busting hanya jika diperlukan
            if (img.src !== imageUrl) {
                // Gunakan timestamp dari data sebagai version, bukan Date.now()
                const version = timestamp ? new Date(timestamp).getTime() : Date.now();
                const separator = imageUrl.includes('?') ? '&' : '?';
                img.src = `${imageUrl}${separator}v=${version}`;
            }
        }

        updateLastUpdate(timestamp) {
            if (!timestamp) return;

            const lastUpdate = document.getElementById("lastUpdate");
            if (!lastUpdate) return;

            try {
                const date = new Date(timestamp);
                lastUpdate.textContent =
                    "Terakhir update: " + date.toLocaleString("id-ID", {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
            } catch (e) {
                lastUpdate.textContent = "Terakhir update: -";
            }
        }

        showOffline() {
            const waterLevelCard = document.getElementById("waterLevelCard");
            if (waterLevelCard) {
                waterLevelCard.style.display = "none";
            }

            const lastUpdate = document.getElementById("lastUpdate");
            if (lastUpdate) {
                lastUpdate.textContent = "⚠️ Sistem Offline - Menunggu data...";
            }
        }

        handleError(error) {
            this.retryCount++;

            // Implement exponential backoff
            if (this.retryCount <= this.maxRetries) {
                const backoffTime = Math.min(1000 * Math.pow(2, this.retryCount), 30000);
                console.log(`🔄 Retry ${this.retryCount}/${this.maxRetries} in ${backoffTime}ms`);

                setTimeout(() => {
                    this.fetchDashboard();
                }, backoffTime);
            } else {
                this.showOffline();
            }
        }

        formatTimeAgo(seconds) {
            if (seconds < 60) return `${seconds} detik`;
            if (seconds < 3600) return `${Math.floor(seconds/60)} menit`;
            return `${Math.floor(seconds/3600)} jam`;
        }
    }

    // Inisialisasi dengan rate limiting
    document.addEventListener("DOMContentLoaded", () => {
        // Delay startup sedikit untuk menghindari spike awal
        setTimeout(() => {
            new CctvRealTimeMonitor({
                apiBaseUrl: "/api/cctv",
                refreshInterval: 10000 // 10 detik
            });
        }, 1000);
    });
</script>

<!--
    CCTV Real-Time Monitor dengan WebSocket/Broadcasting
    Uncomment line script untuk mengaktifkan
-->

<div id="cctvDashboardWebSocket" class="cctv-dashboard">
    <!-- Status Monitoring -->
    <div class="status-card mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3>Status Monitoring (WebSocket)</h3>
                <span id="connectionBadge" class="badge badge-warning">Connecting...</span>
                <span id="statusBadge" class="badge badge-danger">Offline</span>
            </div>
            <small id="lastUpdate">Terakhir update: -</small>
        </div>
    </div>

    <!-- Water Level Display -->
    <div class="water-level-card mb-4">
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

<script>
    // JANGAN UNCOMMENT SAMPAI WEBSOCKET SUDAH CONFIGURED DI LARAVEL

    /*

    // Require Laravel Echo untuk WebSocket
    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';

    window.Pusher = Pusher;
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: true
    });

    class CctvWebSocketMonitor {
        constructor() {
            this.init();
        }

        init() {
            console.log('🎥 Initializing CCTV WebSocket Monitor...');
            this.setupWebSocket();
            this.setupFallback();
        }

        setupWebSocket() {
            // Listen to cctv-monitoring channel
            Echo.channel('cctv-monitoring')
                .listen('CctvDataUpdated', (event) => {
                    console.log('📡 Received data via WebSocket:', event.data);
                    this.updateDisplay(event.data);
                    this.updateConnectionStatus(true);
                })
                .error((error) => {
                    console.error('❌ WebSocket error:', error);
                    this.updateConnectionStatus(false);
                });
        }

        setupFallback() {
            // Fallback ke polling jika WebSocket gagal
            setInterval(() => {
                fetch('/api/cctv/latest')
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.updateDisplay(data.data);
                        }
                    })
                    .catch(() => this.updateConnectionStatus(false));
            }, 30000); // Fallback setiap 30 detik
        }

        updateDisplay(data) {
            const level = parseFloat(data.level_meter).toFixed(3);
            document.getElementById('waterLevel').textContent = level;

            const barHeight = Math.min((parseFloat(data.level_meter) / 2.0) * 100, 100);
            document.getElementById('levelBar').style.height = barHeight + '%';

            if (data.image_url) {
                const img = document.getElementById('cctvImage');
                img.src = data.image_url;
                img.style.display = 'block';
                document.getElementById('noImage').style.display = 'none';
            }

            if (data.timestamp) {
                const date = new Date(data.timestamp);
                document.getElementById('lastUpdate').textContent =
                    `Terakhir update: ${date.toLocaleString('id-ID')}`;
            }
        }

        updateConnectionStatus(isConnected) {
            const badge = document.getElementById('connectionBadge');
            if (isConnected) {
                badge.className = 'badge badge-success';
                badge.textContent = '✓ Connected';
            } else {
                badge.className = 'badge badge-warning';
                badge.textContent = '⟳ Reconnecting...';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        new CctvWebSocketMonitor();
    });

    */
</script>

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
        margin-right: 8px;
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
</style>

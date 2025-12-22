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

    <!-- Data Table -->
    <div class="data-table-card">
        <h3>Riwayat Data</h3>
        <table class="table table-striped table-hover" id="dataTable">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tingkat (m)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <tr>
                    <td colspan="3" class="text-center text-muted">Menunggu data...</td>
                </tr>
            </tbody>
        </table>
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
    .image-card,
    .data-table-card {
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

    .table {
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        border-top: none;
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
            this.refreshInterval = options.refreshInterval || 5000; // 5 detik
            this.lastTimestamp = null;
            this.init();
        }

        init() {
            console.log('🎥 Initializing CCTV Real-Time Monitor...');
            this.startMonitoring();

            // Refresh data setiap interval
            setInterval(() => this.fetchData(), this.refreshInterval);
        }

        async startMonitoring() {
            await this.fetchData();
        }

        async fetchData() {
            try {
                // Fetch latest data dan status secara bersamaan
                const [latestResponse, statusResponse] = await Promise.all([
                    fetch(`${this.apiBaseUrl}/latest`),
                    fetch(`${this.apiBaseUrl}/status`)
                ]);

                const latestData = await latestResponse.json();
                const statusData = await statusResponse.json();

                if (latestData.success) {
                    this.updateDisplay(latestData.data);
                }

                if (statusData.success) {
                    this.updateStatus(statusData.data);
                }

                // Fetch historical data
                await this.fetchHistoricalData();

            } catch (error) {
                console.error('❌ Error fetching data:', error);
                this.showError(error.message);
            }
        }

        async fetchHistoricalData() {
            try {
                const response = await fetch(`${this.apiBaseUrl}/all?limit=10`);
                const data = await response.json();

                if (data.success) {
                    this.updateTable(data.data);
                }
            } catch (error) {
                console.error('Error fetching historical data:', error);
            }
        }

        updateDisplay(data) {
            // Update water level
            const level = parseFloat(data.level_meter).toFixed(3);
            document.getElementById('waterLevel').textContent = level;

            // Update level bar (assume max 2 meter)
            const barHeight = Math.min((parseFloat(data.level_meter) / 2.0) * 100, 100);
            document.getElementById('levelBar').style.height = barHeight + '%';

            // Update image
            if (data.image_url) {
                const img = document.getElementById('cctvImage');
                img.src = data.image_url;
                img.style.display = 'block';
                document.getElementById('noImage').style.display = 'none';
            }

            // Update last update time
            if (data.timestamp) {
                const date = new Date(data.timestamp);
                document.getElementById('lastUpdate').textContent =
                    `Terakhir update: ${date.toLocaleString('id-ID')}`;
            }

            this.lastTimestamp = data.timestamp;
        }

        updateStatus(status) {
            const badge = document.getElementById('statusBadge');

            if (status.status === 'online') {
                badge.className = 'badge badge-success';
                badge.textContent = '✓ Online';
            } else {
                badge.className = 'badge badge-danger';
                badge.textContent = '✗ Offline';
            }
        }

        updateTable(dataArray) {
            const tbody = document.getElementById('tableBody');

            if (dataArray.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada data</td></tr>';
                return;
            }

            tbody.innerHTML = dataArray.map(item => {
                const date = new Date(item.timestamp);
                const timeStr = date.toLocaleString('id-ID');
                const level = parseFloat(item.level_meter).toFixed(3);

                return `
                <tr>
                    <td>${timeStr}</td>
                    <td><strong>${level}</strong></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="cctvMonitor.viewImage('${item.image_url}')">
                            Lihat Gambar
                        </button>
                    </td>
                </tr>
            `;
            }).reverse().join('');
        }

        viewImage(imageUrl) {
            if (imageUrl) {
                const img = document.getElementById('cctvImage');
                img.src = imageUrl;
                img.style.display = 'block';
                document.getElementById('noImage').style.display = 'none';

                // Scroll ke image container
                document.getElementById('imageContainer').scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        showError(message) {
            const statusBadge = document.getElementById('statusBadge');
            statusBadge.className = 'badge badge-danger';
            statusBadge.textContent = '✗ Error: ' + message;
        }
    }

    // Initialize
    let cctvMonitor;
    document.addEventListener('DOMContentLoaded', function() {
        cctvMonitor = new CctvRealTimeMonitor({
            apiBaseUrl: '/api/cctv',
            refreshInterval: 5000 // Refresh setiap 5 detik
        });
    });
</script>

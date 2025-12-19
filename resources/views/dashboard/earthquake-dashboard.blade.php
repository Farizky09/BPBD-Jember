@extends('layouts.master')

@section('title', 'Monitoring Gempa Real-time')

@push('style')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-card {
            transition: all 0.5s ease;
        }

        .data-value {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .badge-aman {
            background-color: #28a745;
        }

        .badge-waspada {
            background-color: #20c997;
        }

        .badge-siaga {
            background-color: #ffc107;
        }

        .badge-awas {
            background-color: #fd7e14;
        }

        .badge-bahaya {
            background-color: #dc3545;
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulsing {
            animation: pulse 1s infinite;
        }

        .chart-legend {
            font-size: 0.8rem;
            margin-top: 10px;
        }

        .data-point {
            font-size: 0.9rem;
            font-weight: bold;
        }
    </style>
@endpush

@section('main')
    <div class="container-fluid py-4">
        <h2 class="mb-4 text-center">Monitoring Sensor Seismik Real-time</h2>

        <!-- Debug Info -->
        <div class="alert alert-warning mb-3">
            <strong>Status Sistem:</strong>
            <span id="system-status">Menghubungkan ke Firebase...</span>
        </div>

        <!-- Kartu Status -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card text-white text-center status-card" id="card-status" style="background-color: #6c757d;">
                    <div class="card-body">
                        <h3>STATUS GEMPA SAAT INI</h3>
                        <h1 id="status-text" class="display-4 fw-bold">MENUNGGU DATA</h1>
                        <p class="mb-0">Status Level: <span id="status-level">0</span>/5</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-acceleration"></i> Data PGA Akselerasi Sumbu (cm/s²)
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <h5>X (Horizontal)</h5>
                                <div id="val-x" class="data-value text-danger">0.00</div>
                            </div>
                            <div class="col-4">
                                <h5>Y (Horizontal)</h5>
                                <div id="val-y" class="data-value text-warning">0.00</div>
                            </div>
                            <div class="col-4">
                                <h5>Z (Vertikal)</h5>
                                <div id="val-z" class="data-value text-success">0.00</div>
                            </div>
                        </div>
                        <hr>
                        <div class="text-muted small">
                            <strong>PGA Maksimum:</strong> <span id="val-pga">0.00 cm/s²</span> |
                            <strong>Update:</strong> <span id="last-update">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <span>
                            <i class="fas fa-chart-line"></i> Grafik PGA Maksimum Real-time (cm/s²) - Update 1 Detik
                        </span>
                        <div class="chart-legend text-white">
                            <span class="badge bg-danger me-1">PGA Maksimum</span>
                            <span class="badge bg-warning me-1">Level 3+</span>
                            <span class="badge bg-orange me-1">Level 4+</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="pgaChart"></canvas>
                        </div>
                        <div class="mt-2 text-center text-muted small">
                            <i class="fas fa-info-circle"></i> Data diperbarui setiap 1 detik |
                            <span id="chart-data-count">0</span> data points
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Events -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <i class="fas fa-history"></i> Riwayat Kejadian Gempa Signifikan
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Waktu</th>
                                        <th>Status Bahaya</th>
                                        <th>PGA Maks (cm/s²)</th>
                                        <th>PGA Sumbu X (cm/s²)</th>
                                        <th>Level</th>
                                    </tr>
                                </thead>
                                <tbody id="table-event-body">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle"></i> Belum ada event bahaya tercatat.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script>
        console.log("=== EARTHQUAKE MONITOR - 1 SECOND UPDATE ===");

        // Update system status
        function updateSystemStatus(message) {
            document.getElementById('system-status').textContent = message;
            console.log(message);
        }

        updateSystemStatus("Memulai inisialisasi sistem...");

        // Konfigurasi Firebase
        const firebaseConfig = @json($firebaseConfig);

        console.log("Firebase Config:", firebaseConfig);
        updateSystemStatus("Menginisialisasi Firebase...");

        // Inisialisasi Firebase
        let database;
        try {
            const app = firebase.initializeApp(firebaseConfig);
            database = firebase.database();
            updateSystemStatus("✅ Firebase terhubung! Menunggu data...");
            console.log("✅ Firebase berhasil diinisialisasi");
        } catch (error) {
            updateSystemStatus("❌ Gagal terhubung ke Firebase: " + error.message);
            console.error("Firebase error:", error);
        }

        // Setup Chart untuk update 1 detik
        const ctx = document.getElementById('pgaChart').getContext('2d');

        // Plugin untuk threshold lines - DIPERBAIKI SESUAI LEVEL BARU
        const thresholdLinePlugin = {
            id: 'thresholdLine',
            afterDraw(chart) {
                const {
                    ctx,
                    chartArea: {
                        top,
                        bottom,
                        left,
                        right
                    },
                    scales: {
                        y
                    }
                } = chart;

                // Level 3 threshold (89 cm/s²)
                const level3Y = y.getPixelForValue(89);
                ctx.save();
                ctx.strokeStyle = '#ffc107';
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]);
                ctx.beginPath();
                ctx.moveTo(left, level3Y);
                ctx.lineTo(right, level3Y);
                ctx.stroke();

                // Level 4 threshold (168 cm/s²)
                const level4Y = y.getPixelForValue(168);
                ctx.strokeStyle = '#fd7e14';
                ctx.beginPath();
                ctx.moveTo(left, level4Y);
                ctx.lineTo(right, level4Y);
                ctx.stroke();

                // Level 5 threshold (564 cm/s²)
                const level5Y = y.getPixelForValue(564);
                ctx.strokeStyle = '#dc3545';
                ctx.beginPath();
                ctx.moveTo(left, level5Y);
                ctx.lineTo(right, level5Y);
                ctx.stroke();

                // Tambah label untuk threshold - DIPERBAIKI
                ctx.fillStyle = '#ffc107';
                ctx.fillText('Level 3 (89 cm/s²)', right - 120, level3Y - 5);
                ctx.fillStyle = '#fd7e14';
                ctx.fillText('Level 4 (168 cm/s²)', right - 120, level4Y - 5);
                ctx.fillStyle = '#dc3545';
                ctx.fillText('Level 5 (564 cm/s²)', right - 120, level5Y - 5);

                ctx.restore();
            }
        };

        const pgaChart = new Chart(ctx, {
            type: 'line',
            plugins: [thresholdLinePlugin],
            data: {
                labels: [],
                datasets: [{
                    label: 'PGA Maksimum (cm/s²)',
                    data: [],
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    pointRadius: 3,
                    pointBackgroundColor: function(context) {
                        const value = context.dataset.data[context.dataIndex];
                        // DIPERBAIKI: Sesuai dengan level baru
                        if (value >= 564) return '#dc3545'; // Level 5 - Merah
                        if (value >= 168) return '#fd7e14'; // Level 4 - Orange
                        if (value >= 89) return '#ffc107'; // Level 3 - Kuning
                        if (value >= 2.9) return '#20c997'; // Level 2 - Hijau muda
                        return '#28a745'; // Level 1 - Hijau
                    },
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 100
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'PGA (cm/s²)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#495057'
                        },
                        suggestedMax: 1000,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#495057',
                            callback: function(value) {
                                return value + ' cm/s²';
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu (Update 1 Detik)',
                            font: {
                                size: 14,
                                weight: 'bold'
                            },
                            color: '#495057'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#495057',
                            maxTicksLimit: 8,
                            callback: function(value, index, values) {
                                const totalPoints = this.chart.data.labels.length;
                                const interval = Math.max(1, Math.floor(totalPoints / 8));
                                if (index % interval === 0 || index === totalPoints - 1) {
                                    return this.chart.data.labels[index];
                                }
                                return '';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#495057'
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            size: 12
                        },
                        bodyFont: {
                            size: 11
                        },
                        callbacks: {
                            title: function(tooltipItems) {
                                return tooltipItems[0].label;
                            },
                            label: function(context) {
                                const value = context.parsed.y;
                                let levelInfo = '';
                                // DIPERBAIKI: Sesuai dengan level baru
                                if (value >= 564) levelInfo = ' (Level 5 - Kerusakan Berat)';
                                else if (value >= 168) levelInfo = ' (Level 4 - Kerusakan Sedang)';
                                else if (value >= 89) levelInfo = ' (Level 3 - Kerusakan Ringan)';
                                else if (value >= 2.9) levelInfo = ' (Level 2 - Dirasakan)';
                                else levelInfo = ' (Level 1 - Tidak Dirasakan)';

                                return `PGA: ${value.toFixed(2)} cm/s²${levelInfo}`;
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        tension: 0.2
                    }
                }
            }
        });

        updateSystemStatus("Chart siap, mendengarkan data sensor...");

        // FUNGSI BARU: Untuk menentukan level berdasarkan nilai PGA sesuai ketentuan
        function calculateLevel(pgaValue) {
            if (pgaValue >= 564) return 5; // Level 5: >564 (Kerusakan Berat)
            if (pgaValue >= 168) return 4; // Level 4: 168-564 (Kerusakan Sedang)
            if (pgaValue >= 89) return 3; // Level 3: 89-167 (Kerusakan Ringan)
            if (pgaValue >= 2.9) return 2; // Level 2: 2.9-88 (Dirasakan)
            return 1; // Level 1: <2.9 (Tidak dirasakan)
        }

        // FUNGSI BARU: Untuk mendapatkan status text berdasarkan level
        function getStatusText(level) {
            switch (level) {
                case 5:
                    return "Kerusakan Berat";
                case 4:
                    return "Kerusakan Sedang";
                case 3:
                    return "Kerusakan Ringan";
                case 2:
                    return "Dirasakan";
                case 1:
                    return "Tidak Dirasakan";
                default:
                    return "MENUNGGU DATA";
            }
        }

        // Fungsi update status card - DIPERBAIKI
        function updateStatusCard(pgaValue) {
            const card = document.getElementById('card-status');
            const textElement = document.getElementById('status-text');
            const levelElement = document.getElementById('status-level');

            // Hitung level berdasarkan nilai PGA
            const level = calculateLevel(pgaValue);
            const statusText = getStatusText(level);

            textElement.textContent = statusText;
            levelElement.textContent = level;

            const colors = {
                0: '#6c757d', // Menunggu data
                1: '#28a745', // Aman - Hijau
                2: '#20c997', // Waspada - Hijau muda
                3: '#ffc107', // Siaga - Kuning
                4: '#fd7e14', // Awas - Orange
                5: '#dc3545' // Bahaya - Merah
            };

            card.style.backgroundColor = colors[level] || '#6c757d';

            // Tambahkan efek visual untuk status bahaya
            if (level >= 4) {
                card.classList.add('pulsing');
            } else {
                card.classList.remove('pulsing');
            }
        }

        // Fungsi get badge class berdasarkan level - DIPERBAIKI
        function getStatusBadgeClass(level) {
            const levelMap = {
                1: 'badge-aman',
                2: 'badge-waspada',
                3: 'badge-siaga',
                4: 'badge-awas',
                5: 'badge-bahaya'
            };
            return levelMap[level] || 'bg-secondary';
        }

        // Format waktu untuk tampilan
        function formatTime(timestamp) {
            const date = new Date();
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function formatDateTime(timestamp) {
            if (!timestamp || timestamp < 1000000000000) {
                return new Date().toLocaleString('id-ID');
            }
            const date = new Date(timestamp);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // Variabel untuk menyimpan data chart
        const maxDataPoints = 120;
        let chartData = {
            labels: [],
            values: [],
            lastUpdate: Date.now()
        };

        // Counter untuk data points
        let dataPointCount = 0;

        // Test koneksi database
        updateSystemStatus("Testing koneksi database...");

        database.ref('sensor/current').once('value')
            .then((snapshot) => {
                const testData = snapshot.val();
                console.log("TEST Data dari Firebase:", testData);
                if (testData) {
                    updateSystemStatus("✅ Koneksi database berhasil! Data tersedia.");
                    updateUI(testData);
                } else {
                    updateSystemStatus("⚠️ Koneksi berhasil tapi data kosong.");
                }
            })
            .catch((error) => {
                updateSystemStatus("❌ Gagal membaca data: " + error.message);
                console.error("Test error:", error);
            });

        // Fungsi untuk update UI dengan data - DIPERBAIKI
        function updateUI(data) {
            const currentTime = Date.now();

            // Rate limiting
            if (currentTime - chartData.lastUpdate < 900) {
                return;
            }

            chartData.lastUpdate = currentTime;

            // Ambil data dari JSON structure
            const pga = parseFloat(data.pga) || 0;
            const xPGA = parseFloat(data.x) || 0;
            const yPGA = parseFloat(data.y) || 0;
            const zPGA = parseFloat(data.z) || 0;

            // Update UI dengan nilai PGA
            document.getElementById('val-x').textContent = xPGA.toFixed(2);
            document.getElementById('val-y').textContent = yPGA.toFixed(2);
            document.getElementById('val-z').textContent = zPGA.toFixed(2);
            document.getElementById('val-pga').textContent = pga.toFixed(2) + ' cm/s²';
            document.getElementById('last-update').textContent = formatTime(currentTime);

            // Update status card berdasarkan nilai PGA - DIPERBAIKI
            updateStatusCard(pga);

            // Update chart
            const timeLabel = formatTime(currentTime);

            // Tambah data baru
            chartData.labels.push(timeLabel);
            chartData.values.push(pga);
            dataPointCount++;

            // Batasi jumlah data points
            if (chartData.labels.length > maxDataPoints) {
                chartData.labels.shift();
                chartData.values.shift();
            }

            // Update chart data
            pgaChart.data.labels = chartData.labels;
            pgaChart.data.datasets[0].data = chartData.values;

            // Update data count display
            document.getElementById('chart-data-count').textContent = dataPointCount;

            // Auto-scale Y axis berdasarkan data
            const maxPGA = Math.max(...chartData.values.filter(val => !isNaN(val)));
            const suggestedMax = Math.max(200, maxPGA * 1.3);
            pgaChart.options.scales.y.suggestedMax = suggestedMax;

            // Update chart
            pgaChart.update('active');
        }

        // Real-time listener untuk data sensor
        let lastDataTime = 0;
        database.ref('sensor/current').on('value', (snapshot) => {
            const data = snapshot.val();
            const currentTime = Date.now();

            // Throttle update
            if (currentTime - lastDataTime < 800) {
                return;
            }

            lastDataTime = currentTime;

            console.log("📊 Data real-time:", data);

            if (data) {
                updateSystemStatus("✅ Data real-time - " + formatTime(currentTime));
                updateUI(data);
            } else {
                updateSystemStatus("⚠️ Data kosong dari Firebase");
            }
        });

        // Events listener dengan query untuk data terbaru - DIPERBAIKI
        database.ref('earthquake_events')
            .orderByKey()
            .limitToLast(10)
            .on('value', (snapshot) => {
                const events = snapshot.val();
                console.log("📋 Events data:", events);

                const tbody = document.getElementById('table-event-body');
                tbody.innerHTML = '';

                if (!events) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle"></i> Belum ada event bahaya tercatat.
                            </td>
                        </tr>
                    `;
                    return;
                }

                const eventsArray = Object.entries(events)
                    .map(([timestamp, event]) => ({
                        timestamp: parseInt(timestamp),
                        ...event
                    }))
                    .sort((a, b) => b.timestamp - a.timestamp)
                    .slice(0, 8);

                eventsArray.forEach((event) => {
                    const time = formatDateTime(event.timestamp);
                    // DIPERBAIKI: Hitung level berdasarkan PGA untuk konsistensi
                    const level = calculateLevel(parseFloat(event.pga) || 0);
                    const badgeClass = getStatusBadgeClass(level);
                    const statusText = getStatusText(level);

                    tbody.innerHTML += `
                        <tr>
                            <td>${time}</td>
                            <td>
                                <span class="badge ${badgeClass}">${statusText}</span>
                            </td>
                            <td><strong>${(parseFloat(event.pga) || 0).toFixed(2)}</strong> cm/s²</td>
                            <td>${(parseFloat(event.x) || 0).toFixed(2)} cm/s²</td>
                            <td>
                                <span class="badge bg-dark">Level ${level}</span>
                            </td>
                        </tr>
                    `;
                });
            });

        // Handle window resize untuk chart
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                pgaChart.resize();
            }, 250);
        });

        // Initial state
        updateStatusCard(0);

        console.log("🎉 Sistem monitoring PGA real-time (1 detik) siap!");
    </script>
@endpush

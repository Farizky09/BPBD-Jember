@include('page.components.head')
@vite(['resources/css/app.css', 'resources/js/app.js'])

<body class="index-page mt-4 bg-gray-900 text-gray-100 font-sans">
    @include('page.components.header')

    <section id="stream" class="stream section">
        <div class="container mx-auto px-4 max-w-7xl mt-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-black mb-2">MONITORING GEMPA REAL-TIME</h1>
                <p class="text-black">Sistem Pemantauan Seismik Berbasis Sensor Akselerometer</p>
                <div id="system-status"
                    class="mt-4 px-4 py-2 bg-yellow-500 border border-yellow-500 rounded-lg text-white text-sm inline-block">
                    🔄 Menghubungkan ke sistem...
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white border border-yellow-700 rounded-xl shadow-lg overflow-hidden transition-all duration-500"
                        id="status-card">
                        <div class="bg-yellow-500 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-white">STATUS GEMPA SAAT INI</h2>
                        </div>
                        <div class="p-6 text-center">
                            <div id="status-text" class="text-4xl font-bold mb-2 text-white">MENUNGGU DATA</div>
                            <div class="flex justify-center items-center space-x-4 m-4">
                                <span class="px-4 py-2 bg-yellow-600 text-white rounded-full text-sm font-medium">
                                    Level: <span id="status-level" class="text-white">0</span>/5
                                </span>
                                <span class="px-4 py-2 bg-yellow-600 text-white rounded-full text-sm font-medium">
                                    PGA: <span id="val-pga">0.00</span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- PGA Chart -->
                    <div class="bg-white border border-gray-300 rounded-xl shadow-lg">
                        <div
                            class="bg-yellow-500 px-6 py-4 border-b border-yellow-600 flex justify-between items-center">
                            <h2 class="text-xl font-bold text-white">GRAFIK PGA REAL-TIME</h2>
                            <div class="flex space-x-2 text-xs">
                                <span
                                    class="px-2 py-1 bg-white text-gray-800 border border-gray-300 rounded font-medium">PGA
                                    Maksimum</span>
                                <span
                                    class="px-2 py-1 bg-red-300 text-white border border-red-400 rounded font-medium">Level
                                    3+</span>
                                <span
                                    class="px-2 py-1 bg-red-500 text-white border border-red-600 rounded font-medium">Level
                                    4+</span>
                            </div>
                        </div>
                        <div class="p-4">
                            <div class="h-80 relative">
                                <canvas id="pgaChart"></canvas>
                            </div>
                            <div class="mt-4 text-center text-gray-600 text-sm">
                                <span>🔄 Update 1 detik | </span>
                                <span id="chart-data-count">0</span> data points
                            </div>
                        </div>
                    </div>

                    <!-- Events Table -->
                    <div class=" border shadow-lg rounded-sm">
                        <div class="bg-yellow-500 px-6 py-4 border-b">
                            <h2 class="text-xl font-bold text-white">RIWAYAT KEJADIAN SIGNIFIKAN</h2>
                        </div>
                        <div class="overflow-x-auto ">
                            <table class="w-full">
                                <thead class="bg-yellow-700 ">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-white">Waktu</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-white">Status</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-white">PGA Maks</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-white">Level</th>
                                    </tr>
                                </thead>
                                <tbody id="table-event-body" class="bg-white">
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-center text-gray-900">
                                            📊 Belum ada event bahaya tercatat
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <!-- Sensor Data -->
                    <div class="bg-white border rounded-xl shadow-lg">
                        <div class="bg-yellow-500 px-6 py-4 border-b ">
                            <h2 class="text-xl font-bold text-white">DATA SENSOR PGA</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- X Axis -->
                            <div class="text-center">
                                <div class="text-black text-sm font-medium mb-2">SUMBU X (HORIZONTAL)</div>
                                <div id="val-x" class="text-3xl font-bold text-red-400">0.00</div>
                                <div class="text-gray-500 text-xs mt-1">cm/s²</div>
                            </div>

                            <!-- Y Axis -->
                            <div class="text-center">
                                <div class="text-black text-sm font-medium mb-2">SUMBU Y (HORIZONTAL)</div>
                                <div id="val-y" class="text-3xl font-bold text-yellow-400">0.00</div>
                                <div class="text-gray-500 text-xs mt-1">cm/s²</div>
                            </div>

                            <!-- Z Axis -->
                            <div class="text-center">
                                <div class="text-black text-sm font-medium mb-2">SUMBU Z (VERTIKAL)</div>
                                <div id="val-z" class="text-3xl font-bold text-green-400">0.00</div>
                                <div class="text-gray-500 text-xs mt-1">cm/s²</div>
                            </div>

                            <div class="pt-4 border-t border-gray-700 text-center">
                                <div class="text-black text-sm">Update Terakhir</div>
                                <div id="last-update" class="text-gray-300 font-medium">-</div>
                            </div>
                        </div>
                    </div>

                    <!-- Legend -->
                    <div class="bg-white border border-gray-700 rounded-xl shadow-lg">
                        <div class="bg-yellow-500 px-6 py-4 border-b border-gray-600">
                            <h2 class="text-xl font-bold text-white">LEGENDA STATUS</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-black">Level 1-2</span>
                                <span
                                    class="px-3 py-1 bg-green-600 text-white rounded-full text-xs font-medium">AMAN</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-black">Level 3</span>
                                <span
                                    class="px-3 py-1 bg-yellow-400 text-white rounded-full text-xs font-medium">WASPADA</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-black">Level 4</span>
                                <span
                                    class="px-3 py-1 bg-orange-500 text-white rounded-full text-xs font-medium">SIAGA</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-black">Level 5</span>
                                <span
                                    class="px-3 py-1 bg-red-500 text-white rounded-full text-xs font-medium">BAHAYA</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('page.components.footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-database.js"></script>
    <script>
        // Update system status
        function updateSystemStatus(message) {
            document.getElementById('system-status').textContent = message;
        }

        updateSystemStatus("🔄 Memulai inisialisasi sistem...");

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

        const ctx = document.getElementById('pgaChart').getContext('2d');

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
                ctx.strokeStyle = '#fca5a5';
                ctx.lineWidth = 2;
                ctx.setLineDash([5, 5]);
                ctx.beginPath();
                ctx.moveTo(left, level3Y);
                ctx.lineTo(right, level3Y);
                ctx.stroke();

                // Level 4 threshold (168 cm/s²)
                const level4Y = y.getPixelForValue(168);
                ctx.strokeStyle = '#ef4444';
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

                //lable
                ctx.fillStyle = '#fca5a5';
                ctx.fillText('Level 3 (89 cm/s²)', right - 120, level3Y - 5);
                ctx.fillStyle = '#ef4444';
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
                        if (value >= 564) return '#dc3545'; // Level 5
                        if (value >= 168) return '#ef4444'; // Level 4
                        if (value >= 89) return '#fca5a5'; // Level 3
                        if (value >= 2.9) return '#20c997'; // Level 2
                        return '#28a745'; // Level 1
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
                            color: '#000000'
                        },
                        suggestedMax: 1000,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 11
                            },
                            color: '#000000',
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
                            color: '#000000'
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#000000',
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
                            color: '#000000'
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

        function calculateLevel(pgaValue) {
            if (pgaValue >= 564) return 5;
            if (pgaValue >= 168) return 4;
            if (pgaValue >= 89) return 3;
            if (pgaValue >= 2.9) return 2;
            return 1;
        }

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

        function getStatusBadgeClass(level) {
            const levelMap = {
                1: 'bg-green-600',
                2: 'bg-green-500',
                3: 'bg-yellow-400',
                4: 'bg-orange-500',
                5: 'bg-red-600'
            };
            return levelMap[level] || 'bg-gray-600';
        }

        function updateStatusCard(pgaValue) {
            const card = document.getElementById('status-card');
            const textElement = document.getElementById('status-text');
            const levelElement = document.getElementById('status-level');
            const level = calculateLevel(pgaValue);
            const statusText = getStatusText(level);

            textElement.textContent = statusText;
            levelElement.textContent = level;

            const colors = {
                0: 'bg-gray-700',
                1: 'bg-green-900',
                2: 'bg-green-800',
                3: 'bg-red-200',
                4: 'bg-red-500',
                5: 'bg-red-900'
            };
            card.className =
                `border border-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-500 ${colors[level] || 'bg-white'}`;

            if (level >= 4) card.classList.add('pulsing');
            else card.classList.remove('pulsing');
        }

        function formatTime() {
            return new Date().toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        function formatDateTime(timestamp) {
            try {
                let date;
                if (typeof timestamp === 'number') {
                    if (timestamp < 1000000000000) timestamp = timestamp * 1000;
                    date = new Date(timestamp);
                } else if (typeof timestamp === 'string') {
                    date = new Date(parseInt(timestamp));
                } else {
                    date = new Date();
                }
                if (isNaN(date.getTime())) date = new Date();
                return date.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            } catch (error) {
                console.error('Error formatting date:', error);
                return new Date().toLocaleString('id-ID');
            }
        }

        const maxDataPoints = 120;
        let chartData = {
            labels: [],
            values: [],
            lastUpdate: Date.now()
        };
        let dataPointCount = 0;
        let lastEventTime = 0;
        const EVENT_COOLDOWN = 10000;

        function saveEarthquakeEvent(pgaValue, level, sensorData) {
            const currentTime = Date.now();
            if (level >= 3 && pgaValue > 50 && (currentTime - lastEventTime) > EVENT_COOLDOWN) {
                const eventData = {
                    pga: pgaValue,
                    level: level,
                    status: getStatusText(level),
                    x: sensorData.x || 0,
                    y: sensorData.y || 0,
                    z: sensorData.z || 0,
                    timestamp: currentTime,
                    createdAt: currentTime
                };
                database.ref('earthquake_events').push(eventData)
                    .then(() => {
                        console.log('Event saved:', eventData);
                        lastEventTime = currentTime;
                    })
                    .catch((error) => console.error('Error saving event:', error));
            }
        }

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

        function updateUI(data) {
            const currentTime = Date.now();
            if (currentTime - chartData.lastUpdate < 900) return;
            chartData.lastUpdate = currentTime;

            const pga = parseFloat(data.pga) || 0;
            const xPGA = parseFloat(data.x) || 0;
            const yPGA = parseFloat(data.y) || 0;
            const zPGA = parseFloat(data.z) || 0;
            const currentLevel = calculateLevel(pga);

            if (currentLevel >= 3 && pga > 50) {
                saveEarthquakeEvent(pga, currentLevel, {
                    x: xPGA,
                    y: yPGA,
                    z: zPGA
                });
            }

            document.getElementById('val-x').textContent = xPGA.toFixed(2);
            document.getElementById('val-y').textContent = yPGA.toFixed(2);
            document.getElementById('val-z').textContent = zPGA.toFixed(2);
            document.getElementById('val-pga').textContent = pga.toFixed(2) + ' cm/s²';
            document.getElementById('last-update').textContent = formatTime(currentTime);
            updateStatusCard(pga);

            const timeLabel = formatTime(currentTime);
            chartData.labels.push(timeLabel);
            chartData.values.push(pga);
            dataPointCount++;

            if (chartData.labels.length > maxDataPoints) {
                chartData.labels.shift();
                chartData.values.shift();
            }

            pgaChart.data.labels = chartData.labels;
            pgaChart.data.datasets[0].data = chartData.values;
            document.getElementById('chart-data-count').textContent = dataPointCount;

            const maxPGA = Math.max(...chartData.values.filter(val => !isNaN(val)));
            const suggestedMax = Math.max(200, maxPGA * 1.3);
            pgaChart.options.scales.y.suggestedMax = suggestedMax;
            pgaChart.update('active');
        }

        let lastDataTime = 0;
        database.ref('sensor/current').on('value', (snapshot) => {
            const data = snapshot.val();
            const currentTime = Date.now();
            if (currentTime - lastDataTime < 800) return;
            lastDataTime = currentTime;
            console.log("📊 Data real-time:", data);
            if (data) {
                updateSystemStatus("✅ Data real-time - " + formatTime(currentTime));
                updateUI(data);
            } else {
                updateSystemStatus("⚠️ Data kosong dari Firebase");
            }
        });

        database.ref('earthquake_events')
            .orderByChild('timestamp')
            .limitToLast(10)
            .on('value', (snapshot) => {
                const events = snapshot.val();
                const tbody = document.getElementById('table-event-body');
                tbody.innerHTML = '';

                if (!events) {
                    tbody.innerHTML =
                        `<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">📊 Belum ada event bahaya tercatat</td></tr>`;
                    return;
                }

                const eventsArray = Object.entries(events)
                    .map(([key, event]) => ({
                        id: key,
                        ...event
                    }))
                    .sort((a, b) => (b.timestamp || b.createdAt || 0) - (a.timestamp || a.createdAt || 0))
                    .slice(0, 5);

                eventsArray.forEach((event) => {
                    const eventTimestamp = event.timestamp || event.createdAt || 0;
                    const time = formatDateTime(eventTimestamp);
                    const pgaValue = parseFloat(event.pga) || 0;
                    const level = calculateLevel(pgaValue);
                    const badgeClass = getStatusBadgeClass(level);
                    const statusText = getStatusText(level);

                    tbody.innerHTML += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-black border-b border-gray-200">${time}</td>
                            <td class="px-4 py-3 border-b border-gray-200">
                                <span class="px-2 py-1 text-white ${badgeClass} rounded-full text-xs font-medium">${statusText}</span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-black border-b border-gray-200">${pgaValue.toFixed(2)} cm/s²</td>
                            <td class="px-4 py-3 border-b border-gray-200">
                                <span class="px-2 py-1 text-white font-medium bg-gray-700 rounded-full text-xs">Level ${level}</span>
                            </td>
                        </tr>
                    `;
                });
            });

        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => pgaChart.resize(), 250);
        });

        updateStatusCard(0);
    </script>
</body>

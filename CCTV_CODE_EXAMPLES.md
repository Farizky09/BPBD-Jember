# CCTV Monitoring - Code Examples & Snippets

## 🚀 Quick Integration Examples

### 1. Add to Existing Layout

Jika punya layout yang sudah ada, tambahkan component:

```blade
<!-- resources/views/layouts/app.blade.php -->

@extends('layouts.main')

@section('content')
    <!-- Add CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- Include Component -->
    @include('components.cctv-realtime-monitor')

    <!-- Add Scripts -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
@endsection
```

---

### 2. Custom API Integration

Gunakan API di aplikasi lain:

```javascript
// JavaScript file - integrate ke aplikasi external
async function getCctvData() {
    try {
        const response = await fetch("http://localhost:8000/api/cctv/latest");
        const data = await response.json();

        if (data.success) {
            console.log("Water Level:", data.data.level_meter);
            console.log("Image URL:", data.data.image_url);
            return data.data;
        }
    } catch (error) {
        console.error("Error:", error);
    }
}

// Gunakan dalam aplikasi Anda
getCctvData().then((data) => {
    // Update custom UI
    document.querySelector(".water-level").textContent = data.level_meter;
    document.querySelector(".cctv-image").src = data.image_url;
});
```

---

### 3. Integrasi dengan Chart.js (Visualisasi Data)

```blade
<!-- resources/views/cctv/chart.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>CCTV Data Charts</h1>
    <canvas id="levelChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fetch data dari API
    fetch('/api/cctv/all?limit=30')
        .then(r => r.json())
        .then(data => {
            const timestamps = data.data.map(d =>
                new Date(d.timestamp).toLocaleTimeString('id-ID')
            );
            const levels = data.data.map(d => d.level_meter);

            // Create chart
            new Chart(document.getElementById('levelChart'), {
                type: 'line',
                data: {
                    labels: timestamps,
                    datasets: [{
                        label: 'Water Level (m)',
                        data: levels,
                        borderColor: '#0066cc',
                        backgroundColor: 'rgba(0, 102, 204, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Water Level History'
                        }
                    },
                    scales: {
                        y: {
                            min: 0,
                            max: 2.5
                        }
                    }
                }
            });
        });
</script>
@endsection
```

---

### 4. Notification Alert (Jika Level Tinggi)

```javascript
// Add to CctvRealTimeMonitor class

updateDisplay(data) {
    const level = parseFloat(data.level_meter);

    // ... existing code ...

    // Check level threshold
    if (level > 1.8) {
        this.showAlert('⚠️ WARNING: Water level tinggi!', 'warning');
    } else if (level > 2.0) {
        this.showAlert('🚨 CRITICAL: Water level sangat tinggi!', 'danger');
    }
}

showAlert(message, type = 'info') {
    // Create alert element
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;

    // Add to page
    const container = document.querySelector('.cctv-dashboard');
    container.insertBefore(alert, container.firstChild);

    // Auto remove after 5 seconds
    setTimeout(() => alert.remove(), 5000);
}
```

---

### 5. Export Data to CSV

```php
// Add to CctvDataController

public function exportCsv()
{
    $data = $this->cctvService->getAllData(1000);

    $csv = "Timestamp,Level Meter,Image Path\n";
    foreach ($data as $row) {
        $csv .= "{$row['timestamp']},{$row['level_meter']},{$row['image_path']}\n";
    }

    return response($csv, 200, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="cctv-data.csv"'
    ]);
}

// Route
Route::get('/api/cctv/export-csv', [CctvDataController::class, 'exportCsv']);
```

---

### 6. Database Integration (Alternative to CSV)

Jika ingin pindah ke database:

```php
// Create migration
php artisan make:migration create_cctv_readings_table

// Migration file
Schema::create('cctv_readings', function (Blueprint $table) {
    $table->id();
    $table->timestamp('timestamp');
    $table->float('level_meter');
    $table->string('image_path');
    $table->string('image_url')->nullable();
    $table->timestamps();
    $table->index('timestamp');
});

// Model
php artisan make:model CctvReading

// Update Service untuk query database
public function getLatestData()
{
    $reading = CctvReading::latest('timestamp')->first();

    if (!$reading) return null;

    return [
        'timestamp' => $reading->timestamp,
        'level_meter' => $reading->level_meter,
        'image_url' => $reading->image_url,
    ];
}
```

---

### 7. Webhook Notification (Slack/Discord)

```php
// Add to service untuk send notifikasi ke Slack

public function notifySlack($message, $level)
{
    $webhook = env('SLACK_WEBHOOK_URL');

    $payload = [
        'text' => $message,
        'attachments' => [
            [
                'color' => $level > 1.8 ? 'danger' : 'good',
                'fields' => [
                    [
                        'title' => 'Water Level',
                        'value' => $level . ' m',
                        'short' => true
                    ],
                    [
                        'title' => 'Timestamp',
                        'value' => now(),
                        'short' => true
                    ]
                ]
            ]
        ]
    ];

    Http::post($webhook, $payload);
}

// Call when level changes significantly
if (abs($newLevel - $oldLevel) > 0.1) {
    $this->notifySlack("Water level changed to {$newLevel}m", $newLevel);
}
```

---

### 8. Cron Job untuk Auto-Cleanup

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    // Cleanup images older than 7 days every day
    $schedule->call(function () {
        $path = storage_path('app/public/cctv');
        $files = scandir($path);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $path . '/' . $file;
                $days = (time() - filemtime($filePath)) / (60 * 60 * 24);

                if ($days > 7) {
                    unlink($filePath);
                }
            }
        }
    })->daily();
}

// Jalankan scheduler
// php artisan schedule:run (dalam cron job)
```

---

### 9. API Rate Limiting

```php
// app/Http/Middleware/CctvRateLimit.php

namespace App\Http\Middleware;

use Closure;

class CctvRateLimit
{
    public function handle($request, Closure $next)
    {
        $ip = $request->ip();
        $key = "cctv_api_{$ip}";
        $limit = 60; // 60 requests
        $decay = 60; // per 60 seconds

        if (cache()->has($key) && cache()->get($key) >= $limit) {
            return response()->json([
                'error' => 'Rate limit exceeded'
            ], 429);
        }

        cache()->increment($key, 1, $decay);

        return $next($request);
    }
}

// Add to API routes
Route::middleware('cctv.rate_limit')->group(function () {
    Route::get('/api/cctv/latest', ...);
});
```

---

### 10. Mobile Responsive View

```blade
<!-- resources/views/cctv/mobile.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <style>
        .cctv-dashboard {
            padding: 10px;
        }

        .water-level-card .level-value {
            font-size: 36px;
        }

        .image-container {
            min-height: 200px;
            max-height: 50vh;
        }

        .img-fluid {
            max-height: 50vh;
        }

        @media (max-width: 576px) {
            .level-value {
                font-size: 28px;
            }

            .table {
                font-size: 12px;
            }
        }
    </style>

    @include('components.cctv-realtime-monitor')
</div>
@endsection
```

---

### 11. Email Report (Daily/Weekly)

```php
// app/Jobs/SendCctvReport.php

namespace App\Jobs;

use App\Services\CctvDataService;
use App\Mail\CctvReport;
use Illuminate\Mail\Mailable;

class SendCctvReport
{
    public function handle()
    {
        $service = app(CctvDataService::class);
        $data = $service->getAllData(288); // 24 hours (5-min intervals)

        Mail::to('admin@example.com')->send(new CctvReport($data));
    }
}

// Schedule in Kernel.php
$schedule->job(SendCctvReport::class)->dailyAt('08:00');
```

---

### 12. Advanced Polling dengan Exponential Backoff

```javascript
// More robust polling dengan retry logic

class RobustCctvMonitor {
    constructor() {
        this.retryCount = 0;
        this.maxRetries = 5;
        this.baseInterval = 5000; // 5 seconds
    }

    fetchData() {
        fetch("/api/cctv/latest")
            .then((r) => r.json())
            .then((data) => {
                this.retryCount = 0; // Reset retry
                this.updateDisplay(data.data);
                this.scheduleNextFetch(this.baseInterval);
            })
            .catch((error) => {
                this.handleError(error);
            });
    }

    handleError(error) {
        this.retryCount++;

        if (this.retryCount <= this.maxRetries) {
            // Exponential backoff: 5s, 10s, 20s, 40s, 80s
            const delay = this.baseInterval * Math.pow(2, this.retryCount - 1);
            console.warn(
                `⚠️ Retry ${this.retryCount}/${this.maxRetries} in ${delay}ms`
            );
            this.scheduleNextFetch(delay);
        } else {
            console.error("❌ Max retries reached. Monitoring offline.");
        }
    }

    scheduleNextFetch(delay) {
        setTimeout(() => this.fetchData(), delay);
    }
}
```

---

### 13. Testing dengan PHPUnit

```php
// tests/Feature/CctvDataControllerTest.php

namespace Tests\Feature;

use Tests\TestCase;

class CctvDataControllerTest extends TestCase
{
    public function test_get_latest_data()
    {
        $response = $this->getJson('/api/cctv/latest');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'timestamp',
                    'level_meter',
                    'image_url'
                ]
            ]);
    }

    public function test_get_status()
    {
        $response = $this->getJson('/api/cctv/status');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'status',
                    'last_update',
                    'time_ago_seconds'
                ]
            ]);
    }
}
```

---

## 🎨 CSS Customization Examples

### Dark Mode

```css
.cctv-dashboard.dark-mode {
    background: #1e1e1e;
    color: #fff;
}

.cctv-dashboard.dark-mode .status-card,
.cctv-dashboard.dark-mode .water-level-card {
    background: #2d2d2d;
    color: #fff;
}

.cctv-dashboard.dark-mode .table {
    background: #2d2d2d;
    color: #fff;
}
```

### Custom Colors

```css
.level-bar {
    /* Custom gradient */
    background: linear-gradient(
        90deg,
        #1e90ff 0%,
        /* Blue */ #00ff00 25%,
        /* Green */ #ffff00 50%,
        /* Yellow */ #ff6600 75%,
        /* Orange */ #ff0000 100% /* Red */
    );
}
```

### Animation

```css
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.status-badge.online {
    animation: pulse 2s infinite;
}
```

---

## 📱 Frontend Framework Integration

### Vue.js Example

```javascript
// Integrate dengan Vue 3

import { defineComponent, ref, onMounted, onUnmounted } from "vue";

export default defineComponent({
    name: "CctvMonitor",
    setup() {
        const waterLevel = ref(0);
        const image = ref("");
        let interval;

        const fetchData = async () => {
            const response = await fetch("/api/cctv/latest");
            const data = await response.json();
            waterLevel.value = data.data.level_meter;
            image.value = data.data.image_url;
        };

        onMounted(() => {
            fetchData();
            interval = setInterval(fetchData, 5000);
        });

        onUnmounted(() => clearInterval(interval));

        return { waterLevel, image };
    },
});
```

### React Example

```javascript
// Integrate dengan React

import React, { useState, useEffect } from "react";

export default function CctvMonitor() {
    const [waterLevel, setWaterLevel] = useState(0);
    const [image, setImage] = useState("");

    useEffect(() => {
        const fetchData = async () => {
            const response = await fetch("/api/cctv/latest");
            const data = await response.json();
            setWaterLevel(data.data.level_meter);
            setImage(data.data.image_url);
        };

        fetchData();
        const interval = setInterval(fetchData, 5000);
        return () => clearInterval(interval);
    }, []);

    return (
        <div>
            <h2>Water Level: {waterLevel}m</h2>
            <img src={image} alt="CCTV" />
        </div>
    );
}
```

---

Semua examples ini siap digunakan dan dapat disesuaikan dengan kebutuhan! 🚀

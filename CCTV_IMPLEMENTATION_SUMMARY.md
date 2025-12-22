# 🎥 CCTV Monitoring System - Implementation Summary

## ✅ Apa yang Sudah Dibuat

Saya sudah membuat **sistem monitoring CCTV real-time lengkap** yang terintegrasi dengan Python CCTV script Anda.

### 📦 Komponen yang Dibuat

#### 1. **Backend Service (PHP)**

-   ✅ `app/Services/CctvDataService.php` - Membaca CSV dan manage data
-   ✅ `app/Http/Controllers/CctvDataController.php` - API endpoints
-   ✅ API routes di `routes/api.php`

#### 2. **Frontend Real-Time (JavaScript)**

-   ✅ `resources/views/components/cctv-realtime-monitor.blade.php` - Component utama
-   ✅ `resources/views/cctv/monitoring.blade.php` - Halaman monitoring
-   ✅ Class `CctvRealTimeMonitor` untuk polling real-time

#### 3. **Additional Features**

-   ✅ Event class untuk WebSocket (future use)
-   ✅ Console command untuk testing
-   ✅ WebSocket component template (optional)

#### 4. **Documentation**

-   ✅ `CCTV_MONITORING_DOCS.md` - Dokumentasi lengkap
-   ✅ `CCTV_QUICK_START.md` - Quick reference guide

---

## 🚀 Cara Menggunakan

### Step 1: Akses Halaman Monitoring

```
http://localhost:8000/cctv/monitoring
```

### Step 2: Dashboard Real-Time Akan Menampilkan

-   📊 Status monitoring (Online/Offline)
-   💧 Tingkat air terbaru
-   📈 Level bar visual dengan gradient warna
-   📸 Gambar tangkapan terakhir
-   📋 Tabel riwayat data 10 terakhir

---

## 🔗 API Endpoints (Bisa diakses langsung)

### 1. Get Latest Data

```
GET /api/cctv/latest
```

**Response:**

```json
{
    "success": true,
    "data": {
        "timestamp": "2025-12-22T10:30:45",
        "level_meter": 1.234,
        "image_path": "D:/path/to/image.jpg",
        "image_url": "/storage/cctv/image.jpg"
    }
}
```

### 2. Get History Data

```
GET /api/cctv/all?limit=50
```

### 3. Get Status

```
GET /api/cctv/status
```

---

## ⚡ Real-Time Methods Explained

### 📊 Current Implementation: **Polling (5 detik)**

```javascript
// Polling every 5 seconds
setInterval(() => this.fetchData(), 5000);
```

**Kelebihan:**

-   ✅ Simple dan reliable
-   ✅ No additional library needed
-   ✅ Works everywhere

**Kekurangan:**

-   ❌ Delay update hingga 5 detik
-   ❌ Bandwidth usage naik dengan polling

---

### 🔌 Alternative 1: **WebSocket (True Real-Time)**

Gunakan Laravel WebSocket untuk push updates:

```bash
composer require beyondcode/laravel-websockets
php artisan websockets:serve
```

**Kelebihan:**

-   ✅ Update instant (milisecond)
-   ✅ Bandwidth lebih efisien
-   ✅ Scalable untuk banyak users

**Kekurangan:**

-   ❌ Setup lebih kompleks
-   ❌ Need extra server process

Template sudah siap di: `cctv-websocket-monitor.blade.php`

---

### 🌊 Alternative 2: **Server-Sent Events (SSE)**

Lightweight alternative:

```php
Route::get('/api/cctv/stream', function () {
    return response()->stream(function () {
        while (true) {
            $data = app(CctvDataService::class)->getLatestData();
            echo "data: " . json_encode($data) . "\n\n";
            flush();
            sleep(5);
        }
    }, 200, [
        'Content-Type' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
    ]);
});
```

---

## 📂 File Structure

```
BPBD-Jember/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SimulateCctvData.php      ← Test command
│   ├── Events/
│   │   └── CctvDataUpdated.php           ← WebSocket event
│   ├── Http/
│   │   └── Controllers/
│   │       └── CctvDataController.php    ← API controller ✨
│   └── Services/
│       └── CctvDataService.php           ← CSV reader ✨
├── routes/
│   ├── api.php                           ← API routes ✨
│   └── web.php                           ← Web routes ✨
├── resources/views/
│   ├── components/
│   │   ├── cctv-realtime-monitor.blade.php      ← Main component ✨
│   │   └── cctv-websocket-monitor.blade.php     ← WebSocket component
│   └── cctv/
│       └── monitoring.blade.php          ← Monitor page ✨
├── .env                                  ← Config file
├── CCTV_MONITORING_DOCS.md               ← Full documentation
└── CCTV_QUICK_START.md                   ← Quick reference
```

---

## 🔧 Configuration (Already Set in .env)

```env
# Your path (adjust if needed)
CCTV_DATA_PATH = "D:/Projek/cctv-project/monitoring_results_test"
```

Service otomatis membaca dari path ini.

---

## 💡 How It Works

```
┌──────────────────┐
│ Python Script    │ ──┐
│ (menghasilkan)   │   │
└──────────────────┘   │
                       ├─→ data_level_air.csv
┌──────────────────┐   │
│ CCTV Camera      │ ──┤
│ (ambil gambar)   │   │
└──────────────────┘   │
                       └─→ [timestamp].jpg
                           (di folder path)
                             │
                             ▼
┌────────────────────────────────────┐
│ CctvDataService (PHP)              │
│ - Baca CSV                         │
│ - Extract latest data              │
│ - Copy image ke storage/public     │
└────────────────────────────────────┘
             │
             ▼
┌────────────────────────────────────┐
│ CctvDataController (API)           │
│ /api/cctv/latest                   │
│ /api/cctv/all                      │
│ /api/cctv/status                   │
└────────────────────────────────────┘
             │
             ▼
┌────────────────────────────────────┐
│ Frontend JS (Polling)              │
│ - Fetch data setiap 5 detik        │
│ - Update display real-time         │
│ - Show image & metrics             │
└────────────────────────────────────┘
             │
             ▼
┌────────────────────────────────────┐
│ User Interface                     │
│ - Status badge                     │
│ - Water level display              │
│ - Latest image                     │
│ - Data history table               │
└────────────────────────────────────┘
```

---

## 🎯 Features

### Dashboard

-   [x] Real-time water level display
-   [x] Status indicator (online/offline)
-   [x] Live image viewer
-   [x] Data history table
-   [x] Timestamp tracking
-   [x] Visual level bar with color gradient

### API

-   [x] Get latest data
-   [x] Get historical data with pagination
-   [x] Get monitoring status
-   [x] Error handling

### Real-Time

-   [x] Polling implementation (5 detik)
-   [x] WebSocket template (ready to implement)
-   [x] Auto-refresh capability
-   [x] Status tracking

---

## 🧪 Testing

### 1. Test API Endpoint

```bash
# PowerShell
(Invoke-WebRequest http://localhost:8000/api/cctv/latest).Content | ConvertFrom-Json

# atau buka di browser
http://localhost:8000/api/cctv/latest
```

### 2. Open Monitoring Dashboard

```
http://localhost:8000/cctv/monitoring
```

### 3. Lihat Console Browser (F12)

Akan ada logs dari real-time monitor.

---

## ⚙️ Customization

### Ubah Refresh Interval

Edit `cctv-realtime-monitor.blade.php` baris terakhir:

```javascript
refreshInterval: 3000; // 3 detik (lebih cepat)
```

### Ubah Max Water Level

Edit `CctvDataService.php`:

```php
// Ubah value 2.0 ke nilai max yang sesuai
max(0, min(2.5, $level))
```

### Ubah Warna/Styling

Edit CSS di `cctv-realtime-monitor.blade.php`:

```css
.level-bar {
    background: linear-gradient(...); /* ubah gradient warna */
}
```

---

## 📊 Data Flow

1. **Python Script**

    - Capture gambar dari CCTV
    - Analyze untuk dapat level air
    - Save ke folder + CSV

2. **Laravel Service**

    - Read CSV terbaru
    - Extract latest row
    - Copy image ke storage public

3. **API Endpoint**

    - Return JSON data
    - Include image URL

4. **Frontend JavaScript**
    - Poll API setiap 5 detik
    - Update display
    - Show image real-time

---

## 🚨 Troubleshooting

### Images tidak muncul?

```bash
# Buat storage link
php artisan storage:link

# Check folder permissions
chmod -R 755 storage/app/public
```

### API returns 404?

```bash
# Clear route cache
php artisan route:clear

# Verify route
php artisan route:list | grep cctv
```

### Data tidak update?

```bash
# Check browser console (F12)
# Verify API response: http://localhost:8000/api/cctv/latest
# Check network tab untuk errors
```

---

## 📈 Next Steps (Optional)

1. **Upgrade to WebSocket** (true real-time)

    - Setup `beyondcode/laravel-websockets`
    - Use template di `cctv-websocket-monitor.blade.php`

2. **Add Alerts** (jika level melebihi threshold)

    ```php
    if ($level > 1.5) {
        // Send alert/notification
    }
    ```

3. **Add Database** (instead of CSV)

    - Faster queries
    - Better reporting

4. **Export Data** (Excel/PDF)

    - Use `maatwebsite/excel`

5. **Add Charts** (visualization)
    - Use Chart.js atau ApexCharts

---

## 📞 Support

-   Detailed docs: `CCTV_MONITORING_DOCS.md`
-   Quick reference: `CCTV_QUICK_START.md`
-   Laravel logs: `storage/logs/laravel.log`

---

## ✨ Summary

Sekarang Anda memiliki:
✅ Function untuk baca CSV terbaru
✅ Function untuk ambil gambar dari path
✅ Real-time monitoring dashboard
✅ RESTful API endpoints
✅ Configurable path dari .env
✅ Documentation lengkap
✅ Ready untuk production atau upgrade

**Tinggal buka**: `http://localhost:8000/cctv/monitoring`

Enjoy! 🎥📊

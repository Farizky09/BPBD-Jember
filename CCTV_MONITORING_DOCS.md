# CCTV Real-Time Monitoring System

## 📋 Dokumentasi Implementasi

Sistem monitoring CCTV real-time yang menampilkan data tingkat air dari Python CCTV script.

---

## 🏗️ Arsitektur Sistem

```
┌─────────────────────┐
│  Python CCTV        │
│  Script             │ ──→ Menyimpan data ke:
│                     │     - Gambar (JPG)
└─────────────────────┘     - CSV data_level_air.csv
         ↓
┌─────────────────────────────────────────┐
│  File System Storage                    │
│  D:/Projek/cctv-project/monitoring_...  │
│  ├── data_level_air.csv                 │
│  └── [timestamp].jpg                    │
└─────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────┐
│  Laravel API (PHP)                      │
│  ├── Service: CctvDataService           │
│  ├── Controller: CctvDataController      │
│  └── Routes: /api/cctv/*                │
└─────────────────────────────────────────┘
         ↓
┌─────────────────────────────────────────┐
│  Real-Time Frontend (JavaScript)        │
│  ├── Polling: Setiap 5 detik            │
│  ├── Display: Water Level + Image       │
│  └── Table: Riwayat Data                │
└─────────────────────────────────────────┘
```

---

## 📦 File yang Dibuat

### 1. **Service Layer**

📄 `app/Services/CctvDataService.php`

-   Membaca file CSV dari path ENV
-   Extract data terbaru dan riwayat
-   Copy gambar ke storage public
-   Check status monitoring (online/offline)

### 2. **API Controller**

📄 `app/Http/Controllers/CctvDataController.php`

-   Endpoint: `/api/cctv/latest` - Ambil data terbaru
-   Endpoint: `/api/cctv/all?limit=50` - Ambil riwayat
-   Endpoint: `/api/cctv/status` - Status monitoring

### 3. **Routes**

📄 `routes/api.php` - API routes untuk CCTV
📄 `routes/web.php` - Web route untuk monitoring page

### 4. **Frontend Component**

📄 `resources/views/components/cctv-realtime-monitor.blade.php`

-   React-like component dengan vanilla JS
-   Real-time update display
-   Image viewer
-   Data history table

### 5. **Monitoring Page**

📄 `resources/views/cctv/monitoring.blade.php`

-   Page utama untuk monitoring

---

## 🚀 Cara Menggunakan

### 1. **Setup .env** (sudah ada)

```env
CCTV_DATA_PATH = "D:/Projek/cctv-project/monitoring_results_test"
```

### 2. **Akses Halaman Monitoring**

```
http://localhost:8000/cctv/monitoring
```

### 3. **API Endpoints**

#### Get Latest Data

```bash
GET /api/cctv/latest
```

**Response:**

```json
{
    "success": true,
    "message": "Latest data retrieved",
    "data": {
        "timestamp": "2025-12-22T10:30:45",
        "level_meter": 1.234,
        "image_path": "D:/path/to/image.jpg",
        "image_url": "/storage/cctv/2025-12-22_10-30-45.jpg"
    }
}
```

#### Get All Data

```bash
GET /api/cctv/all?limit=50
```

#### Get Status

```bash
GET /api/cctv/status
```

**Response:**

```json
{
    "success": true,
    "data": {
        "status": "online",
        "last_update": "2025-12-22T10:30:45",
        "time_ago_seconds": 5,
        "latest_level": 1.234
    }
}
```

---

## ⚡ Real-Time Update Methods

### **1. Polling (Currently Implemented)**

```javascript
// Refresh setiap 5 detik
setInterval(() => this.fetchData(), 5000);
```

**Pros:**

-   ✅ Simple, tidak butuh library tambahan
-   ✅ Compatible semua browser
-   ✅ Mudah debug

**Cons:**

-   ❌ Bandwidth boros (request terus-menerus)
-   ❌ Delay hingga 5 detik sebelum update

### **2. WebSocket (Recommended untuk production)**

Untuk real-time yang lebih baik, gunakan Laravel Broadcasting:

```php
// Install WebSocket
composer require beyondcode/laravel-websockets

php artisan websockets:serve
```

Frontend menggunakan Echo:

```javascript
Echo.channel("cctv-monitoring").listen("CctvDataUpdated", (event) => {
    this.updateDisplay(event.data);
});
```

### **3. Server-Sent Events (SSE)**

Alternative ringan untuk push updates:

```php
// Controller
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

Frontend:

```javascript
const eventSource = new EventSource("/api/cctv/stream");
eventSource.addEventListener("message", (event) => {
    const data = JSON.parse(event.data);
    this.updateDisplay(data);
});
```

---

## 🔧 Konfigurasi Lanjutan

### **1. Ubah Interval Refresh**

Edit di `resources/views/components/cctv-realtime-monitor.blade.php`:

```javascript
cctvMonitor = new CctvRealTimeMonitor({
    apiBaseUrl: "/api/cctv",
    refreshInterval: 3000, // 3 detik (lebih cepat)
});
```

### **2. Custom Styling**

Modify CSS dalam component untuk styling sesuai kebutuhan.

### **3. Maximum Water Level**

Edit di `app/Services/CctvDataService.php`:

```php
// Ubah nilai maksimal level air (default 2.0 meter)
```

---

## 🐛 Troubleshooting

### **Gambar tidak tampil**

1. Check folder storage public ada di `storage/app/public/cctv`
2. Jalankan: `php artisan storage:link`
3. Check permission folder

### **CSV tidak terbaca**

1. Verify path di `.env` benar
2. Check format CSV: `timestamp,level_meter,image_path`
3. Pastikan delimiter adalah koma (`,`)

### **Data tidak update real-time**

1. Check browser console (F12)
2. Verify API endpoint accessible: `http://localhost:8000/api/cctv/latest`
3. Check network tab untuk response time

### **Storage link tidak bekerja**

```bash
# Generate storage link
php artisan storage:link

# Verify link
ls -l public/storage
```

---

## 📊 Monitoring Dashboard Features

✅ **Status Badge** - Online/Offline indicator
✅ **Water Level Display** - Nilai real-time dalam meter
✅ **Level Bar** - Visual representation dengan gradient warna
✅ **Latest Image** - Tampilan tangkapan terbaru
✅ **History Table** - Riwayat 10 data terakhir
✅ **Quick View** - Klik riwayat untuk lihat gambar lama

---

## 🔐 Security Notes

1. **File Access** - CSV dan gambar disimpan di disk, bukan di git
2. **API Rate Limiting** - Tambah di middleware jika perlu
3. **CORS** - Configure jika frontend terpisah domain
4. **File Permissions** - Ensure app bisa read/write ke CCTV_DATA_PATH

---

## 📈 Performance Tips

1. **Limit History Data**

    ```php
    Route::get('/all?limit=20') // Jangan terlalu banyak
    ```

2. **Image Compression**

    ```php
    // Di CctvDataService, compress gambar sebelum copy
    ```

3. **Caching**

    ```php
    // Cache data status 5 detik
    Cache::remember('cctv_status', 5, function () {
        return $this->getMonitoringStatus();
    });
    ```

4. **Database Option** (Future)
    ```php
    // Instead of CSV, simpan ke database untuk query lebih cepat
    ```

---

## 🎯 Next Steps

1. ✅ Monitoring page sudah berjalan
2. ⏳ Implementasi WebSocket untuk real-time lebih baik
3. ⏳ Add alarm/alert ketika level melebihi threshold
4. ⏳ Export data ke Excel
5. ⏳ Chart/grafik history data

---

## 📞 API Quick Reference

| Endpoint           | Method | Description             |
| ------------------ | ------ | ----------------------- |
| `/api/cctv/latest` | GET    | Data terbaru            |
| `/api/cctv/all`    | GET    | Semua data dengan limit |
| `/api/cctv/status` | GET    | Status monitoring       |
| `/cctv/monitoring` | GET    | Halaman monitoring      |

---

Enjoy monitoring! 🎥📊

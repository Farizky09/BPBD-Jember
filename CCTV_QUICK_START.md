# CCTV Monitoring - Quick Start Guide

## 🚀 Quick Setup

### 1. Clear Cache & Publish Storage

```bash
php artisan cache:clear
php artisan config:clear
php artisan storage:link
```

### 2. Test API Endpoint

```bash
# Terminal/PowerShell
curl http://localhost:8000/api/cctv/latest

# Atau buka di browser
http://localhost:8000/api/cctv/latest
```

### 3. Open Monitoring Page

```
http://localhost:8000/cctv/monitoring
```

---

## 🔗 API Endpoints

### Latest Data

```
GET http://localhost:8000/api/cctv/latest

Response:
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

### All Data with Limit

```
GET http://localhost:8000/api/cctv/all?limit=50
```

### Monitoring Status

```
GET http://localhost:8000/api/cctv/status

Response:
{
  "success": true,
  "data": {
    "status": "online",        // atau "offline"
    "last_update": "2025-12-22T10:30:45",
    "time_ago_seconds": 5,
    "latest_level": 1.234
  }
}
```

---

## 🎯 Main Files

| File                                                         | Purpose               |
| ------------------------------------------------------------ | --------------------- |
| `app/Services/CctvDataService.php`                           | Read CSV, manage data |
| `app/Http/Controllers/CctvDataController.php`                | API endpoints         |
| `routes/api.php`                                             | API routes            |
| `routes/web.php`                                             | Web routes            |
| `resources/views/components/cctv-realtime-monitor.blade.php` | UI Component          |
| `resources/views/cctv/monitoring.blade.php`                  | Monitoring page       |

---

## 🔄 Real-Time Options

### Option 1: Polling (Default - Already Working ✅)

-   Refresh setiap 5 detik
-   Simple & reliable
-   File: `cctv-realtime-monitor.blade.php`

### Option 2: WebSocket (Advanced)

-   True real-time push
-   Requires: `beyondcode/laravel-websockets`
-   File: `cctv-websocket-monitor.blade.php`

### Option 3: Server-Sent Events

-   Alternative polling ringan
-   Requires: EventSource API

---

## 🛠️ Customization

### Change Refresh Interval

Edit `cctv-realtime-monitor.blade.php`:

```javascript
refreshInterval: 3000; // 3 detik (lebih cepat)
```

### Change API Base URL

```javascript
apiBaseUrl: "/api/cctv"; // atau URL lain
```

### Change Max Water Level

Edit `app/Services/CctvDataService.php`:

```php
'level_meter' => max(0, min(2.5, (float)$row['level_meter']))
```

---

## 🐛 Common Issues & Solutions

### **API returns 404**

```bash
# Verify route exists
php artisan route:list | grep cctv

# Clear routes cache
php artisan route:clear
```

### **Images not showing**

```bash
# Create storage link
php artisan storage:link

# Check permissions
chmod -R 755 storage/app/public
```

### **CSV not found**

```bash
# Verify path in .env
echo $env:CCTV_DATA_PATH  # PowerShell
# atau
echo %CCTV_DATA_PATH%    # CMD

# Check CSV exists
dir "D:/Projek/cctv-project/monitoring_results_test"
```

### **403 Forbidden**

-   Add route to web.php without auth middleware
-   Or check user permissions

---

## 📊 Expected CSV Format

```csv
timestamp,level_meter,image_path
2025-12-22T10:30:45,1.234,D:/Projek/cctv-project/monitoring_results_test/2025-12-22_10-30-45.jpg
2025-12-22T10:30:50,1.240,D:/Projek/cctv-project/monitoring_results_test/2025-12-22_10-30-50.jpg
```

---

## 💡 Tips & Tricks

### Test with Curl

```bash
# Get latest data
curl -s http://localhost:8000/api/cctv/latest | python -m json.tool

# Get 10 latest records
curl -s "http://localhost:8000/api/cctv/all?limit=10"
```

### Monitor in Real-Time

```bash
# Watch API response every 5 seconds (PowerShell)
while($true) {
  curl http://localhost:8000/api/cctv/status
  Start-Sleep -Seconds 5
}
```

### Check Data Freshness

```bash
# Status menunjukkan "time_ago_seconds"
# Jika > 300 (5 menit), status = offline
```

---

## 🔒 Security Checklist

-   [ ] .env file tidak ter-commit ke git
-   [ ] CSV path hanya readable oleh app
-   [ ] API endpoints protected (jika perlu auth)
-   [ ] Validate file path sebelum akses
-   [ ] Limit file size untuk images

---

## 📈 Performance Tips

1. **Limit polling interval** - Jangan < 2 detik
2. **Compress images** - Saat simpan dari Python
3. **Cache status** - Cache 5 detik
4. **Pagination** - Jangan fetch semua data sekaligus

---

## 🧪 Testing Commands

```bash
# Clear everything
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Verify service provider
php artisan tinker
>>> $service = app(App\Services\CctvDataService::class)
>>> $service->getLatestData()

# Run server
php artisan serve

# Monitor CCTV (jika sudah ada Python script jalan)
php artisan cctv:simulate --interval=3
```

---

## 📞 Contact & Support

-   Check logs: `storage/logs/laravel.log`
-   API response status codes:
    -   `200` - Success
    -   `404` - Data not found
    -   `500` - Server error

---

Created: December 22, 2025
Laravel Version: 11.x
PHP Version: 8.0+

<?php

namespace App\Services;

use Illuminate\Support\Collection;

class CctvDataService
{
    protected $csvPath;

    public function __construct()
    {
        $this->csvPath = env('CCTV_DATA_PATH');
    }

    /**
     * Get latest data from CSV
     */
    public function getLatestData()
    {
        $csvFile = $this->csvPath . '/data_level_air.csv';

        if (!file_exists($csvFile)) {
            return null;
        }

        // Baca file CSV
        $rows = [];
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = null;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($header === null) {
                    $header = $data;
                } else {
                    $row = array_combine($header, $data);
                    $rows[] = $row;
                }
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return null;
        }

        // Ambil data terbaru (baris terakhir)
        $latestData = end($rows);

        return [
            'timestamp' => $latestData['timestamp'] ?? null,
            'level_meter' => (float) ($latestData['level_meter'] ?? 0),
            'image_path' => $latestData['image_path'] ?? null,
            'image_url' => $this->convertPathToUrl($latestData['image_path'] ?? null),
        ];
    }

    /**
     * Get all data from CSV
     */
    public function getAllData($limit = null)
    {
        $csvFile = $this->csvPath . '/data_level_air.csv';

        if (!file_exists($csvFile)) {
            return collect();
        }

        $rows = [];
        if (($handle = fopen($csvFile, 'r')) !== false) {
            $header = null;
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if ($header === null) {
                    $header = $data;
                } else {
                    $row = array_combine($header, $data);
                    $rows[] = [
                        'timestamp' => $row['timestamp'] ?? null,
                        'level_meter' => (float) ($row['level_meter'] ?? 0),
                        'image_path' => $row['image_path'] ?? null,
                        'image_url' => $this->convertPathToUrl($row['image_path'] ?? null),
                    ];
                }
            }
            fclose($handle);
        }

        $collection = collect($rows);

        if ($limit) {
            $collection = $collection->take($limit);
        }

        return $collection;
    }

    /**
     * Convert file path to URL that can be accessed
     */
    protected function convertPathToUrl($filePath)
    {
        if (!$filePath || !file_exists($filePath)) {
            return null;
        }

        // Jika path adalah absolute path Windows atau Unix
        // Konversi ke storage path yang accessible
        $basename = basename($filePath);
        $relativePath = 'cctv/' . $basename;

        // Copy file ke storage public jika belum ada
        $storagePath = storage_path('app/public/cctv');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $storageFull = storage_path('app/public/cctv/' . $basename);
        if (!file_exists($storageFull)) {
            copy($filePath, $storageFull);
        }

        return '/storage/cctv/' . $basename;
    }

    /**
     * Get status monitoring
     */
    public function getMonitoringStatus()
    {
        $latestData = $this->getLatestData();

        if (!$latestData) {
            return [
                'status' => 'offline',
                'message' => 'No data available',
            ];
        }

        $lastTimestamp = strtotime($latestData['timestamp']);
        $currentTime = time();
        $timeDiff = $currentTime - $lastTimestamp;

        // Jika data lebih dari 5 menit lalu, anggap offline
        $isOnline = $timeDiff < 300;

        return [
            'status' => $isOnline ? 'online' : 'offline',
            'last_update' => $latestData['timestamp'],
            'time_ago_seconds' => $timeDiff,
            'latest_level' => $latestData['level_meter'],
        ];
    }
}

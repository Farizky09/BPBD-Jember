<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CctvDataService
{
    protected $csvPath;
    protected $cacheDuration = 60; 

    public function __construct()
    {
        $this->csvPath = env('CCTV_DATA_PATH');
    }

    public function getLatest()
    {
        $rows = $this->readCsv();
        return empty($rows) ? null : end($rows);
    }

    public function getAll($limit = null)
    {
        $rows = collect($this->readCsv());

        if ($limit) {
            $rows = $rows->take($limit)->values();
        }

        return $rows;
    }
    private function readCsv()
    {
        // Gunakan cache untuk mengurangi I/O
        return Cache::remember('cctv_data_csv', $this->cacheDuration, function () {
            $file = $this->csvPath . '/water_level_report.csv';

            if (!file_exists($file)) {
                return [];
            }

            $rows = [];
            if (($handle = fopen($file, 'r')) !== false) {
                $header = null;

                // Baca maksimal 100 baris terakhir untuk efisiensi
                $lines = [];
                while (($line = fgets($handle)) !== false) {
                    $lines[] = $line;
                    if (count($lines) > 101) { // Keep last 100 + header
                        array_shift($lines);
                    }
                }
                fclose($handle);

                // Parse CSV dari lines
                foreach ($lines as $index => $line) {
                    $data = str_getcsv($line);
                    if ($index === 0) {
                        $header = $data;
                    } elseif ($header && count($data) === count($header)) {
                        $rows[] = array_combine($header, $data);
                    }
                }
            }

            return $rows;
        });
    }
    /**
     * Get status monitoring
     *
     *
     */

    public function getDashboardData($historyLimit = 10)
    {
        return Cache::remember('cctv_dashboard', 15, function () use ($historyLimit) {
            $rows = collect($this->readCsv());

            if ($rows->isEmpty()) {
                return null;
            }

            $latest = $rows->last();
            $history = $rows->take(-$historyLimit)->reverse()->values();

            return [
                'latest' => $latest,
                'history' => $history,
            ];
        });
    }
    public function getMonitoringStatus()
    {
        $latestData = $this->getLatest();

        if (!$latestData) {
            return [
                'status' => 'offline',
                'message' => 'No data available',
            ];
        }

        try {
            // Parse timestamp format: 2025-12-22T09:15:09.442205 (with microseconds)
            $timestamp = $latestData['timestamp'];

            // Remove microseconds jika ada
            if (strpos($timestamp, '.') !== false) {
                $timestamp = substr($timestamp, 0, strpos($timestamp, '.'));
            }

            $dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $timestamp);

            if (!$dateTime) {
                // Fallback: try parsing with strtotime
                $lastTimestamp = strtotime($latestData['timestamp']);
            } else {
                $lastTimestamp = $dateTime->getTimestamp();
            }
        } catch (\Exception $e) {
            // Jika parse error, return offline
            return [
                'status' => 'offline',
                'message' => 'Failed to parse timestamp',
                'error' => $e->getMessage(),
            ];
        }

        $currentTime = time();
        $timeDiff = $currentTime - $lastTimestamp;

        // Status online jika data update dalam 2 menit (120 detik)
        // Offline jika tidak ada update > 2 menit
        $isOnline = $timeDiff <= 10;

        return [
            'status' => $isOnline ? 'online' : 'offline',
            'last_update' => $latestData['timestamp'],
            'time_ago_seconds' => $timeDiff,
            'latest_level' => $latestData['level_meter'] ?? null,
            'water_level_status' => $latestData['level_meter'] ?? null,
        ];
    }
}

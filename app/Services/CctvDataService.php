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

    public function getLatest()
    {
        $rows = $this->readCsv();

        return empty($rows) ? null : end($rows);
    }

    public function getAll($limit = null)
    {
        $rows = collect($this->readCsv());

        return $limit ? $rows->take($limit)->values() : $rows;
    }

    private function readCsv()
    {
        $file = $this->csvPath . '/water_level_report.csv';

        if (!file_exists($file)) return [];

        $rows = [];
        if (($handle = fopen($file, 'r')) !== false) {
            $header = null;

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = $data;
                } else {
                    $rows[] = array_combine($header, $data);
                }
            }
            fclose($handle);
        }

        return $rows;
    }
    /**
     * Get status monitoring
     */
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

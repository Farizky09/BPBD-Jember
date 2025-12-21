<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    // public function index()
    // {
    //     $folderPath = env('CCTV_DATA_PATH');
    //     // dd($folderPath);

    //     if (!$folderPath) {
    //         abort(500, 'CCTV_DATA_PATH belum diset di .env');
    //     }

    //     $csvPath = rtrim($folderPath, '/') . '/data_level_air.csv';
    //     $dataMonitoring = [];

    //     // dd("CSV Path: " . $csvPath, "CSV Exists: " . (file_exists($csvPath) ? 'Yes' : 'No'));
    //     if (!file_exists($csvPath)) {
    //         abort(404, 'File CSV tidak ditemukan: ' . $csvPath);
    //     }

    //     if (($file = fopen($csvPath, 'r')) !== false) {

    //         // skip header
    //         fgetcsv($file);

    //         while (($row = fgetcsv($file)) !== false) {

    //             // validasi kolom
    //             if (count($row) < 3) {
    //                 continue;
    //             }

    //             $fullPathGambar = $row[2];

    //             // ambil nama file saja
    //             $namaFileGambar = basename(str_replace('\\', '/', $fullPathGambar));

    //             $dataMonitoring[] = [
    //                 'waktu' => $row[0],
    //                 'level' => $row[1],
    //                 'gambar' => $namaFileGambar,
    //             ];
    //         }

    //         fclose($file);
    //     }
    //     // dd($dataMonitoring[$row - 1]);
    //     return view('monitoring.index', [
    //         'dataMonitoring' => array_reverse($dataMonitoring),
    //     ]);
    // }
    public function index()
    {
        // Hanya return data JSON untuk testing
        $folderPath = env('CCTV_DATA_PATH');

        if (!$folderPath) {
            return response()->json(['error' => 'CCTV_DATA_PATH not set'], 500);
        }

        $csvPath = rtrim($folderPath, '/') . '/data_level_air.csv';

        if (!file_exists($csvPath)) {
            return response()->json(['error' => 'CSV not found', 'path' => $csvPath], 404);
        }

        $data = [];
        if (($file = fopen($csvPath, 'r')) !== false) {
            fgetcsv($file); // skip header

            while (($row = fgetcsv($file)) !== false) {
                if (count($row) < 3) continue;

                $data[] = [
                    'waktu' => $row[0],
                    'level' => $row[1],
                    'gambar' => basename(str_replace('\\', '/', $row[2])),
                ];
            }
            fclose($file);
        }

        return response()->json([
            'success' => true,
            'data_count' => count($data),
            'first_item' => $data[0] ?? null,
            'folder_path' => $folderPath,
            'csv_path' => $csvPath,
        ]);
    }


    public function getImage($filename)
    {
        $folderPath = env('CCTV_DATA_PATH');

        if (!$folderPath) {
            abort(500, 'CCTV_DATA_PATH belum diset di .env');
        }

        // Debug: cek path yang dibentuk
        $filePath = rtrim($folderPath, '/') . '/' . $filename;

        // Coba beberapa kemungkinan path
        $possiblePaths = [
            rtrim($folderPath, '/') . '/' . $filename,
            rtrim($folderPath, '/') . '/images/' . $filename,
            $folderPath . '/' . $filename,
            $folderPath . '/images/' . $filename,
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                $filePath = $path;
                break;
            }
        }

        // Debug output
        if (!file_exists($filePath)) {
            // Kembalikan gambar placeholder untuk debugging
            return response("Gambar tidak ditemukan: $filePath\n\n" .
                "Folder Path: $folderPath\n" .
                "Filename: $filename\n" .
                "Possible paths tried:\n" .
                implode("\n", $possiblePaths), 404);
        }

        return response()->file($filePath);
    }
}

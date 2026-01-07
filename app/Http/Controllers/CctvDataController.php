<?php

namespace App\Http\Controllers;

use App\Services\CctvDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use function PHPSTORM_META\map;

class CctvDataController extends Controller
{
    protected $cctvService;

    public function __construct(CctvDataService $cctvService)
    {
        $this->cctvService = $cctvService;
    }

    /**
     * Get latest CCTV data
     */

    public function index()
    {
        return view('cctv.monitoring');
    }

    /**
     * Latest Data
     */
    public function getLatest(): JsonResponse
    {
        $data = $this->cctvService->getLatest();
        $levelInCm = $this->convertToCm($data['level_meter']);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'No data available',
                'data' => null,
            ], 404);
        }

        $imageUrl = url('/api/cctv/image?path=' . urlencode($data['image_path']));

        return response()->json([
            'success' => true,
            'message' => 'Latest data retrieved',

            'data' => [
                ...$data,
                'image_url' => $imageUrl,
                'level_meter_cm' => $levelInCm,
            ]
        ]);
    }


    /**
     * All Data (Optional Limit)
     */
    public function getAll(): JsonResponse
    {
        try {
            // $limit = request()->query('limit', 10);
            $data = $this->cctvService->getAll();

            $data = $data->map(function ($item) {
                $item['level_meter_cm'] = $this->convertToCm($item['level_meter']);
                return $item;
            });

            return response()->json([
                'success' => true,
                'total' => $data->count(),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    public function history(): JsonResponse
    {
        try {
            $limit = request()->query('limit', 10);

            $data = $this->cctvService->getAll()
                ->take(-$limit)
                ->reverse()
                ->values();
            $data = $data->map(function ($item) {
                $item['level_meter_cm'] = $this->convertToCm($item['level_meter']);
                return $item;
            });

            return response()->json([
                'success' => true,
                'total' => count($data),
                'data' => $data,
                'limit' => $limit,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Show Image
     */
    public function showImage(Request $request)
    {
        try {
            $relativePath = urldecode($request->query('path'));
            // contoh input: monitoring_results_test\2025-12-24_14-05-24.jpg

            // Normalisasi path
            $relativePath = str_replace('\\', '/', $relativePath);

            // Jika sudah mengandung folder monitoring_results_test, hapus biar tidak double
            $basePath = rtrim(env('CCTV_IMAGE_BASE_PATH', '/www/wwwroot/processed_results'), '/');

            // Jika path relatif sudah mengandung base path, gunakan langsung
            if (strpos($relativePath, $basePath) === 0) {
                $fullPath = $relativePath;
            } else {
                $fullPath = $basePath . '/' . $relativePath;
            }


            return response()->file($fullPath);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }



    /**
     * Get monitoring status
     */
    public function getStatus(): JsonResponse
    {
        try {
            $status = $this->cctvService->getMonitoringStatus();

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // private function getWaterLevelStatus($level)
    // {
    //     if ($level === null || $level === '') {
    //         return 'unknown';
    //     }

    //     $levelInCm = $this->convertToCm($level);

    //     if ($levelInCm >= 0 && $levelInCm <= 80) {
    //         return 'normal';
    //     } elseif ($levelInCm > 80 && $levelInCm < 150) {
    //         return 'waspada';
    //     } else {
    //         return 'siaga evakuasi';
    //     }
    // }
    private function convertToCm($value)
    {
        $value = str_replace('.', '', $value);
        return floatval($value) / 10;
    }
}

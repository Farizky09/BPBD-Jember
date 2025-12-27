<?php

namespace App\Http\Controllers;

use App\Services\CctvDataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
                'image_url' => $imageUrl
            ]
        ]);
    }


    /**
     * All Data (Optional Limit)
     */
    public function getAll(): JsonResponse
    {
        try {
            // $limit = request()->query('limit');
            $data = $this->cctvService->getAll();

            return response()->json([
                'success' => true,
                'total' => count($data),
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
            $relativePath = str_replace('monitoring_results_test/', '', $relativePath);

            $basePath = rtrim(env('CCTV_IMAGE_BASE_PATH'), '/');

            $fullPath = $basePath . '/' . $relativePath;

            if (!file_exists($fullPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                    'debug_full_path' => $fullPath
                ], 404);
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
}

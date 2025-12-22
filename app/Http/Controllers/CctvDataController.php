<?php

namespace App\Http\Controllers;

use App\Services\CctvDataService;
use Illuminate\Http\JsonResponse;

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
    public function getLatest(): JsonResponse
    {
        try {
            $data = $this->cctvService->getLatestData();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data available',
                    'data' => null,
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Latest data retrieved',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get all CCTV data with limit
     */
    public function getAll(): JsonResponse
    {
        try {
            $limit = request()->query('limit', 50);
            $data = $this->cctvService->getAllData($limit);

            return response()->json([
                'success' => true,
                'message' => 'Data retrieved',
                'total' => count($data),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => null,
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

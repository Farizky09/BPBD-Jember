<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ConfirmReport;
use App\Models\DisasterCategory;
use App\Models\Reports;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DisasterApiController extends Controller
{


    public function getLocationReports(Request $request)
    {
        try {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $locationReports = ConfirmReport::with(['report'])
                ->where('confirm_reports.status', '=', 'accepted')
                ->get()
                // ->get(['report.id', 'report.name', 'report.latitude', 'report.longitude', 'report.address', 'report.description', 'report.created_at'])
                ->map(function ($report) use ($latitude, $longitude) {
                    $report->distance = $this->getDistanceFromUser($latitude, $longitude, $report->report);
                    return $report;
                })
                ->sortBy('distance');
            $formatterResultReports = $locationReports->values()->map(function ($report) {
                return [
                    'id' => $report->report->id,
                    'name' => $report->report->name,
                    'latitude' => $report->report->latitude,
                    'longitude' => $report->report->longitude,
                    'address' => $report->report->address,
                    'description' => $report->report->description,
                    'reported_at' => $report->report->created_at,
                    'confirmed_at' => $report->created_at,
                    'status' => $report->status,
                    'distance' => round($report->distance, 2),
                    'images' => $report->report->images
                ];
            });

            return ResponseHelper::success('Berhasil mendapatkan laporan lokasi', $formatterResultReports, 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mendapatkan laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getWarning(Request $request)
    {
        try {
            $userLat = $request->latitude;
            $userLng = $request->longitude;
            if (!$userLat || !$userLng) {
                return response()->json(['html' => '<p style="color:red;">Lokasi tidak valid.</p>'], 400);
            }
            // return Carbon::now()->subDay();
            $reports = ConfirmReport::where('status', '=', 'accepted')
                ->with(['report'])
                ->where('created_at', '>', Carbon::now()->subDay())
                ->get();
            // return $reports;
            if ($reports->isEmpty()) {
                return ResponseHelper::success('Berhasil mendapatkan peringatan bencana', [
                    'html' => '',
                    'distance' => 0,
                    'disaster_time' => '',
                    'report' => null
                ], 200);
            }

            $nearest = $reports->map(function ($report) use ($userLat, $userLng) {
                $report->distance = $this->getDistanceFromUser($userLat, $userLng, $report->report);
                $report->disaster_time = $report->report->created_at->diffForHumans();
                return $report;
            })->sortBy('distance')->first();
            $distance = round($nearest->distance, 2);
            // return $nearest;
            $message = $this->generateWarningMessage($distance, $nearest);

            return ResponseHelper::success('Berhasil mendapatkan peringatan bencana', [
                'html' => $message,
                'distance' => $distance,
                'disaster_time' => $nearest->disaster_time,
                'report' => $nearest->report
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mendapatkan peringatan bencana',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDistanceFromUser($userLat, $userLng, $report)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($report->latitude - $userLat);
        $dLng = deg2rad($report->longitude - $userLng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($userLat)) * cos(deg2rad($report->latitude)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function generateWarningMessage($distance, $report)
    {
        if ($distance <= 5) {
            return "
            <div style='background-color: #ffe6e3; padding: 20px; border-radius: 10px;'>
            <div style='color: #e74c3c; text-align: center'>
            <h3><b> BAHAYA </b></h3>
            <br />
                <p style='padding-top: 5px'>
                Bencana <strong>{$report->name}</strong> dilaporkan <strong>{$report->created_at->diffForHumans()}</strong> sangat dekat dengan
                Anda (~{$distance} km). <strong>Lihat Peta Bencana dan segera evakuasi ke tempat aman!</strong>
                </p>
                </div>
                </div>
                <div class='info'>Berdasarkan pelaporan bencana 24 jam terakhir</div>
                ";
        } elseif ($distance <= 10) {
            return "
                <div style='background-color: #fff6e7; padding: 20px; border-radius: 10px'>
            <div style='color: #f39c12; text-align: center'>
                <h3><b> INFO </b></h3>
                <br />
                <p style='padding-top: 5px'>
                Telah terjadi bencana <strong>{$report->created_at->diffForHumans()}</strong> <strong>{$report->name}</strong> terdeteksi sekitar {$distance} km
                dari Anda. <strong>Tetap waspada dan selalu pantau Peta Bencana</strong>.
                </p>
            </div>
            </div>
            <div class='info'>Berdasarkan pelaporan bencana 24 jam terakhir</div>

            ";
        } else {
            return "
                	<div style='background-color: #e5fff1; padding: 20px; border-radius: 10px'>
                    <div style='color: #0ec564; text-align: center'>
                        <h3><b> AMAN </b></h3>
                        <br />
                        <p style='padding-top: 5px'>
                        Tidak ada bencana terdekat yang terdeteksi dalam radius 10km dari lokasi
                        Anda.
                        </p>
                    </div>
                    </div>
            <div class='info'>Berdasarkan pelaporan bencana 24 jam terakhir</div>

            ";
        }
    }

    public function getDisasterCategory()
    {
        try {
            $allDisasterCategory = DisasterCategory::get();
            return ResponseHelper::success('Berhasil mendapatkan kategori bencana', $allDisasterCategory);
        } catch (Exception $exception) {
            return ResponseHelper::error($exception, 500);

        }
    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\ImageReport;
use App\Models\Reports;
use Carbon\Carbon;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportsApiController extends Controller
{
    public function index()
    {
        try {
            $user_id = Auth::id();
            $reports = Reports::with(['images', 'disasterCategory'])
                ->where('reports.user_id', '=', $user_id)
                ->orderBy('created_at', 'DESC')
                ->get()
                ->groupBy(function ($report) {
                    return Carbon::parse($report->created_at)->toDateString();
                });

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan laporan',
                'data' => $reports
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mendapatkan laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function store(Request $request)
    {
        $request->validate([
            // 'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'id_category' => 'required|integer',
        ]);

        try {
            
            DB::beginTransaction();

            $date = Carbon::now();
            $userId = Auth::user()->id;
            $i = 0;

            $existingToday = Reports::whereDate('created_at', $date->toDateString())->count();
            $nomorUrut = $existingToday + ($i + 1);

            $kdReport = sprintf(
                "Laporan/%s/%s/%s/U%02d/%04d",
                $date->format('Y'),
                $date->format('m'),
                $date->format('d'),
                $userId,
                $nomorUrut
            );

            $report = new Reports();
            // $report->name = $request->name;
            $report->kd_report = $kdReport;
            $report->latitude = $request->latitude;
            $report->longitude = $request->longitude;
            $report->address = $request->address;
            $report->description = $request->description;
            $report->user_id = Auth::id();
            $report->status = "pending";
            $report->id_category = $request->id_category;
            $report->save();

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('image_report', 'public');
                    $savedPaths[] = $path;
                    $image_report = new ImageReport();
                    $image_report->report_id = $report->id;
                    $image_report->image_path = $path;
                    $report->images = $savedPaths;
                    // $report->images = asset(Storage::url($path));
                    $image_report->save();
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dibuat',
                'data' => $report
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat membuat laporan',
                'error' => $e->getMessage()
            ], 500);

        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                // 'name' => 'required|string|max:255',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'address' => 'required|string|max:255',
                'description' => 'required|string|max:1000',
                'removedImages' => 'string',
            ]);
            $report = Reports::findOrFail($id);
            $imageReport = ImageReport::where('report_id', $id)->get();
            if ($request->removedImages) {
                $request['removedImages'] = json_decode($request->removedImages);
                // return response()->json([
                //     'status' => 'success',
                //     // 'message' => 'Laporan berhasil diubah'
                //     'data' => $request->all()
                // ], 200);
                foreach ($request->removedImages as $image) {
                    $imageReport = ImageReport::where('image_path', "image_report/" . $image)->first();
                    if ($imageReport) {
                        Storage::disk('public')->delete("image_report" . $image);
                        $imageReport->delete();
                    }
                }
            }
            if ($request->hasFile('addedImages')) {

                foreach ($request->file('addedImages') as $image) {
                    $path = $image->store('image_report', 'public');
                    $savedPaths[] = $path;
                    $image_report = new ImageReport();
                    $image_report->report_id = $report->id;
                    $image_report->image_path = $path;
                    // $report->images = $savedPaths;
                    // $report->images = asset(Storage::url($path));
                    $image_report->save();
                }
            }
            $report->update([
                // 'name' => $request->name,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'address' => $request->address,
                'description' => $request->description,
                'id_category' => $request->id_category
            ]);
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil diubah'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengubah laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $report = Reports::findOrFail($id);
            $imageReports = ImageReport::where('report_id', $id)->get();
            foreach ($imageReports as $imageReport) {
                Storage::disk('public')->delete($imageReport->image_path);
                $imageReport->delete();
            }
            $report->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getById($id)
    {
        try {
            $user_id = Auth::id();
            $reports = Reports::with(['images'])
                ->where('reports.user_id', '=', $user_id)
                ->where('reports.id', '=', $id)
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mendapatkan laporan',
                'data' => $reports
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mendapatkan laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPendingCount()
    {
        return response()->json([
            'count' => Reports::where('status', 'pending')->count()
        ]);
    }

}

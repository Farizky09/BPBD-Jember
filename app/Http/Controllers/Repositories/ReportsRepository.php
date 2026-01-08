<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\ReportsInterfaces;
use App\Models\ConfirmReport;
use App\Models\ImageReport;
use App\Models\Reports;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ReportsRepository implements ReportsInterfaces
{
    private $reports;
    private $confirmReports;

    public function __construct(Reports $reports, ConfirmReport $confirmReports)
    {
        $this->reports = $reports;
        $this->confirmReports = $confirmReports;
    }

    public function get()
    {
        return $this->reports->get();
    }

    public function getById($id)
    {
        return $this->reports->find($id);
    }

    public function show($id)
    {
        return $this->reports->with(['images', 'user', 'disasterCategory'])->find($id);
    }

    public function store($data)
    {
        return DB::transaction(function () use ($data) {

            $data['kd_report'] = $this->generateKdReport();
            $data['user_id']   = Auth::id();
            $data['status']    = 'pending';

            $report = $this->reports->create($data);

            if (!empty($data['images'])) {
                $this->handlingImagesStorage(
                    $data['images'],
                    $report
                );
            }

            return $report;
        });
    }

    public function update($data, $id)
    {
        return DB::transaction(function () use ($data, $id) {

            $report = $this->reports->findOrFail($id);
            $report->update($data);

            if (!empty($data['images'])) {
                $this->handlingImagesStorage(
                    $data['images'],
                    $report
                );
            }

            return $report;
        });
    }

    public function delete($id)
    {

        DB::beginTransaction();
        try {
            $report = $this->reports->find($id);

            foreach ($report->images as $image) {
                $imageData = ImageReport::find($image->id);
                if ($imageData) {
                    Storage::disk('public')->delete($imageData->image_path);
                    $imageData->delete();
                }
            }

            $report->delete();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function datatable()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        return DB::table('reports')
            ->join('users', 'reports.user_id', '=', 'users.id')
            ->select(
                'reports.id',
                'reports.kd_report',
                'reports.subdistrict',
                'reports.address',
                'reports.status',
                'reports.created_at',
                'users.name as user_name',
                'users.id as user_id'
            )
            ->when(request('status'), function ($query) {
                $query->where('reports.status', request('status'));
            })
            ->when(Auth::user()->hasRole('user'), function ($query) {
                $query->where('reports.user_id', auth()->id());
            })
            ->orderBy('reports.created_at', 'desc')
            ->get();
    }


    public function process($id)
    {
        return DB::transaction(function () use ($id) {
            $report = $this->reports->findOrFail($id);
            $report->update([
                'status' => 'process',
            ]);
            $this->confirmReports->create([
                'report_id' => $report->id,
                'admin_id' => Auth::user()->id,
                'status' => 'proses',
            ]);
            return $report;
        });
    }

    public function accept($data, $id)
    {
        DB::beginTransaction();
        try {
            $report = Reports::findOrFail($id);
            $report->update([
                'status' => 'accepted',
                'disaster_level' => $data['disaster_level'],

            ]);
            DB::commit();
            return $report;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function reject($data, $id)
    {
        DB::beginTransaction();
        try {
            $report = Reports::findOrFail($id);
            $report->update([
                'status' => 'rejected',
                'reject_reason' => $data['reject_reason'],
            ]);
            DB::commit();
            return $report;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function generateKdReport()
    {
        $lastReport = $this->reports->orderBy('created_at', 'desc')->first();
        if (!$lastReport) {
            return 'RPT-0001';
        }

        $lastKdReport = $lastReport->kd_report;
        $number = (int) substr($lastKdReport, 4);
        $number++;
        return 'RPT-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    private function handlingImagesStorage(array $images, $report)
    {
        $savedImages = [];

        try {
            foreach ($images as $index => $image) {

                if (!$image->isValid()) {
                    throw new \Exception("File gambar ke-" . ($index + 1) . " tidak valid");
                }

                $date = Carbon::parse($report->created_at)->format('j-n-Y');
                $folderPath = "imageReports/{$date}/{$report->user_id}";

                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }

                $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();

                $path = $image->storeAs($folderPath, $filename, 'public');

                if (!$path || !Storage::disk('public')->exists($path)) {
                    throw new \Exception("Gagal menyimpan file gambar ke-" . ($index + 1));
                }

                $imageRecord = ImageReport::create([
                    'report_id'  => $report->id,
                    'image_path' => $path,
                ]);

                if (!$imageRecord) {
                    throw new \Exception("Gagal menyimpan record gambar ke-" . ($index + 1));
                }

                $savedImages[] = $path;
            }
        } catch (\Throwable $th) {
            // hapus file fisik jika gagal
            foreach ($savedImages as $imagePath) {
                if (Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
            }

            throw $th; // biar DB::transaction rollback
        }
    }
}

<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\ReportsInterfaces;
use App\Models\ConfirmReport;
use App\Models\ImageReport;
use App\Models\Reports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            return $this->reports->create($data);
        });
    }

    public function update($data, $id)
    {
        DB::beginTransaction();
        try {
            $report = $this->reports->where('id', $id);
            $report->update($data);
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        DB::commit();
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
        DB::beginTransaction();
        try {
            $report = Reports::findOrFail($id);
            $report->update([
                'status' => 'process',
            ]);

            DB::commit();
            return $report;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
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
}

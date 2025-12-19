<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\DisasterReportDocumentationsInterfaces;
use App\Models\DisasterReportDocumentations;
use App\Models\ImageDisasterReports;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisasterReportDocumentationsRepository implements DisasterReportDocumentationsInterfaces
{
    private $disasterReportDocumentations;

    public function __construct(DisasterReportDocumentations $disasterReportDocumentations)
    {
        $this->disasterReportDocumentations = $disasterReportDocumentations;
    }

    public function get()
    {
        return $this->disasterReportDocumentations->get();
    }

    public function getById($id)
    {
        return $this->disasterReportDocumentations->find($id);
    }

    public function show($id)
    {
        return $this->disasterReportDocumentations->with(['confirmReport', 'images'])->find($id);
    }

    public function store($data)
    {
        return $this->disasterReportDocumentations->create($data);
    }

    public function update($id, $data)
    {
        $documentation = $this->disasterReportDocumentations->find($id);
        $documentation->update($data);
        return $documentation;
    }

    public function delete($id)
    {

        $documentation = $this->disasterReportDocumentations->find($id);
        foreach ($documentation->images as $image) {
            $imageData = ImageDisasterReports::find($image->id);
            if ($imageData) {
                Storage::disk('public')->delete($imageData->image_path);
                $imageData->delete();
            }
        }
        return $documentation->delete();
    }

    public function datatable()
    {

        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $subdistrict = request()->subdistrict;
        $category = request()->category;
        $isAdmin = Auth::user()->hasRole('admin');

        $data = $this->disasterReportDocumentations->with(['confirmReport', 'images'])
            ->when($isAdmin, function ($query) use ($isAdmin) {
                $query->whereHas('confirmReport', function ($subQuery) use ($isAdmin) {
                    $subQuery->where('admin_id', $isAdmin);
                });
            })

            ->when($subdistrict, function ($query) use ($subdistrict) {
                $query->whereHas('confirmReport.report', function ($subQuery) use ($subdistrict) {
                    $subQuery->whereRaw('LOWER(subdistrict) = ?', [strtolower($subdistrict)]);
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('confirmReport.report.disasterCategory', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return $data;
    }

    public function getDataExport()
    {
        $startDate = request()->start_date;
        $endDate = request()->end_date;
        $subdistrict = request()->subdistrict;
        $category = request()->category;
        $isAdmin = Auth::user()->hasRole('admin');

        return $this->disasterReportDocumentations->with(['confirmReport', 'images'])
            ->when($isAdmin, function ($query) use ($isAdmin) {
                $query->whereHas('confirmReport', function ($subQuery) use ($isAdmin) {
                    $subQuery->where('admin_id', $isAdmin);
                });
            })
            ->when($subdistrict, function ($query) use ($subdistrict) {

                $query->whereHas('confirmReport.report', function ($subQuery) use ($subdistrict) {
                    $subQuery->whereRaw('LOWER(subdistrict) = ?', [strtolower($subdistrict)]);
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->whereHas('confirmReport.report.disasterCategory', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                if ($startDate == $endDate) {
                    $query->whereDate('created_at', $startDate);
                } else {
                    $query->whereBetween('created_at', [
                        $startDate . ' 00:00:00',
                        $endDate . ' 23:59:59'
                    ]);
                }
            })
            ->orderBy('created_at', 'asc')
            ->get();
        return $data;
    }
}

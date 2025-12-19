<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\DashboardInterfaces;
use App\Models\ConfirmReport;
use App\Models\DisasterCategory;
use App\Models\DisasterImpacts;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Dotenv\Util\Str;
use Illuminate\Console\View\Components\Confirm;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class DashboardRepository implements DashboardInterfaces
{
    private $confirmReport;

    public function __construct(ConfirmReport $confirmReport)
    {
        $this->confirmReport = $confirmReport;
    }

    protected function getSubdistrictList(): array
    {
        return [
            'AJUNG',
            'AMBULU',
            'ARJASA',
            'BALUNG',
            'BANGSALSARI',
            'GUMUKMAS',
            'JELBUK',
            'JENGGAWAH',
            'JOMBANG',
            'KALISAT',
            'KALIWATES',
            'KENCONG',
            'LEDOKOMBO',
            'MAYANG',
            'MUMBULSARI',
            'PAKUSARI',
            'PANTI',
            'PATRANG',
            'PUGER',
            'RAMBIPUJI',
            'SEMBORO',
            'SILO',
            'SUKORAMBI',
            'SUKOWONO',
            'SUMBERBARU',
            'SUMBERJAMBE',
            'SUMBERSARI',
            'TANGGUL',
            'TEMPUREJO',
            'UMBULSARI',
            'WULUHAN',
        ];
    }

    /**

     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function getDisasterCategoryCount(?string $startDate = null, ?string $endDate = null): Collection
    {
        $categories = DisasterCategory::all()->pluck('name', 'id');

        $query = DB::table('confirm_reports')
            ->select('reports.id_category', DB::raw('COUNT(confirm_reports.id) as total_incidents'))
            ->join('reports', 'confirm_reports.report_id', '=', 'reports.id')
            ->where('confirm_reports.status', 'accepted');

        if ($startDate && $endDate) {
            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('confirm_reports.created_at', [$startDateTime, $endDateTime]);
        }

        $reportCounts = $query->groupBy('reports.id_category')->get();

        $result = $categories->map(function ($name, $id) use ($reportCounts) {
            $count = $reportCounts->firstWhere('id_category', $id);
            return [
                'category_name' => $name,
                'total_incidents' => $count ? (int) $count->total_incidents : 0,
            ];
        })->values();

        return $result;
    }

    /**

     *
     * @param string|null $startDate
     * @param string|null $endDate
     * @return Collection
     */
    public function getSubdistrictCount(?string $startDate = null, ?string $endDate = null): Collection
    {
        $subdistricts = collect($this->getSubdistrictList());


        $query = DB::table('confirm_reports')
            ->select(DB::raw('UPPER(reports.subdistrict) as subdistrict'), DB::raw('COUNT(confirm_reports.id) as total_incidents'))
            ->join('reports', 'confirm_reports.report_id', '=', 'reports.id')
            ->where('confirm_reports.status', 'accepted');

        if ($startDate && $endDate) {
            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('confirm_reports.created_at', [$startDateTime, $endDateTime]);
        }


        $reportCounts = $query->groupBy('reports.subdistrict')->get();

        $result = $subdistricts->map(function ($subdistrict) use ($reportCounts) {

            $count = $reportCounts->where('subdistrict', $subdistrict)->first();
            return [
                'subdistrict' => ucwords(strtolower($subdistrict)),
                'total_incidents' => $count ? (int) $count->total_incidents : 0,
            ];
        })->values();

        return $result;
    }

    public function getDataStats(?string $startDate = null, ?string $endDate = null): array
    {

        $dateCondition = function (Builder $query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $startDateTime = Carbon::parse($startDate)->startOfDay();
                $endDateTime = Carbon::parse($endDate)->endOfDay();
                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }
        };

        $acceptedImpacts = DisasterImpacts::whereHas('confirmReport', function ($query) use ($startDate, $endDate) {
            $query->where('status', 'accepted')
                ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay()
                    ]);
                });
        })->get();


        $stats = [
            'total_kejadian' => ConfirmReport::where('status', 'accepted')
                ->whereNull('main_report_id')
                ->where($dateCondition)->count(),
            'lightly_damaged_houses' => $acceptedImpacts->sum('lightly_damaged_houses'),
            'moderately_damaged_houses' => $acceptedImpacts->sum('moderately_damaged_houses'),
            'heavily_damaged_houses' => $acceptedImpacts->sum('heavily_damaged_houses'),
            'damaged_public_facilities' => $acceptedImpacts->sum('damaged_public_facilities'),
            'injured_people' => $acceptedImpacts->sum('injured_people'),
            'deceased_people' => $acceptedImpacts->sum('deceased_people'),
            'missing_people' => $acceptedImpacts->sum('missing_people'),
            'affected_babies' => $acceptedImpacts->sum('affected_babies'),
            'affected_elderly' => $acceptedImpacts->sum('affected_elderly'),
            'affected_disabled' => $acceptedImpacts->sum('affected_disabled'),
            'affected_pregnant_women' => $acceptedImpacts->sum('affected_pregnant_women'),
            'affected_general' => $acceptedImpacts->sum('affected_general'),
        ];


        $stats['affected_people'] = $acceptedImpacts->sum(function ($impact) {
            return $impact->affected_babies +
                $impact->affected_elderly +
                $impact->affected_disabled +
                $impact->affected_pregnant_women +
                $impact->affected_general;
        });

        return $stats;
    }
}

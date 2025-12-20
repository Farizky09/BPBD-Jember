<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\DashboardInterfaces;
use App\Models\ConfirmReport;
use App\Models\Consultation;
use App\Models\DisasterCategory;
use App\Models\DisasterImpacts;
use App\Models\Infografis;
use App\Models\News;
use App\Models\Reports;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    private $confirmReport;
    public function __construct(DashboardInterfaces $confirmReport)
    {
        $this->confirmReport = $confirmReport;
    }
    public function index()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $user = auth()->user();
        $news = News::with(['imageNews'])->where('status', 'published')->take(8)->get();
        $isUser = $user->hasRole('user');
        $isAdmin = $user->hasRole('admin');
        $isSuperAdmin = $user->hasRole('super_admin');
        $consultation = Consultation::all();
        $informasiBPBD = Infografis::where('category_image', 'head_image')->get();
        $infografisJember = Infografis::where('category_image', 'infografis_jember')->get();
        $infografisRaung = Infografis::where('category_image', 'infografis_raung')->get();

        // Data untuk admin
        $proses = ConfirmReport::where('status', 'pending')
            ->where('admin_id', $user->id)
            ->count();
        $accept = ConfirmReport::where('status', 'accepted')
            ->where('admin_id', $user->id)
            ->count();
        $reject = ConfirmReport::where('status', 'rejected')
            ->where('admin_id', $user->id)
            ->count();

        // Data untuk super admin
        $totalUser = User::count();
        $totalUserPeople = User::whereHas('roles', function ($query) {
            $query->where('name', 'user');
        })->count();
        $totalAdmin = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();


        $totalReport = Reports::count();
        $totalReportPending = Reports::where('status', 'pending')->count();
        $totalReportProcess = Reports::where('status', 'process')->count();

        $totalReportAccepted = ConfirmReport::where('status', 'accepted')->count();
        $totalReportRejected = ConfirmReport::where('status', 'rejected')->count();
        $totalReportDone = Reports::where('status', 'done')->count();



        // Data untuk grafik
        $query = Reports::with('disasterCategory')
            ->join('confirm_reports', 'reports.id', '=', 'confirm_reports.report_id')
            ->where('confirm_reports.status', '=', 'accepted');
        $dataDays = (clone $query)
            ->whereDate('confirm_reports.created_at', '>=', now()->subDays(7))
            ->select('reports.*', 'confirm_reports.created_at as confirmed_at')
            ->get();

        $dataWeeks = (clone $query)
            ->whereDate('confirm_reports.created_at', '>=', now()->subWeeks(4))
            ->select('reports.*', 'confirm_reports.created_at as confirmed_at')
            ->get();

        $dataMonths = (clone $query)
            ->whereDate('confirm_reports.created_at', '>=', now()->subMonths(12))
            ->select('reports.*', 'confirm_reports.created_at as confirmed_at')
            ->get();


        $categories = DisasterCategory::all();
        $defaultColors = [
            '#733AEA',
            '#0FCA7A',
            '#F2426E',
            '#FD9722',
            '#1C1C1C',
            '#8A2BE2',
            '#2F4F7F',
            '#3E8E41',
            '#8B0A1A',
            '#F7DC6F',
            '#00BFFF',
            '#33CC33',
            '#FF0033',
            '#FFFF00',
            '#7A288A',
            '#F2C464',
            '#32CD32',
            '#032B44',
            '#FF0033',
            '#FFD700',
            '#ACFFAC'
        ];

        // Grouping function
        $groupByFormat = function ($collection, $format) {
            return $collection->groupBy(function ($item) use ($format) {
                return Carbon::parse($item->confirmed_at)->format($format);
            });
        };

        // Prepare chart data
        $chartData = [
            'labelsDay' => [],
            'labelsWeek' => [],
            'labelsMonth' => [],
            'categories' => []
        ];

        // Group all data
        $groupedDayAll = $groupByFormat($dataDays, 'Y-m-d');
        $groupedWeekAll = $dataWeeks->groupBy(function ($item) {
            return Carbon::parse($item->confirmed_at)->startOfWeek()->format('Y-m-d');
        });
        $groupedMonthAll = $groupByFormat($dataMonths, 'Y-m');

        $generateDateRanges = function ($period, $interval) {
            $now = now();
            $dates = collect();

            for ($i = $interval; $i >= 0; $i--) {
                $date = $now->copy()->sub($period, $i);
                $dates->push($period === 'months' ? $date->format('Y-m') : ($period === 'weeks' ? $date->startOfWeek()->format('Y-m-d') :
                    $date->format('Y-m-d')));
            }

            return $dates;
        };

        $recentDays = $generateDateRanges('days', 6);
        $recentWeeks = $generateDateRanges('weeks', 3);
        $recentMonths = $generateDateRanges('months', 11);
        $filterGroupedData = function ($groupedData, $recentDates) {
            return $recentDates->mapWithKeys(function ($date) use ($groupedData) {
                return [$date => $groupedData->get($date, [])];
            });
        };

        $chartData['categories']['all'] = [
            'name' => 'Semua Bencana',
            'color' => '#888888',
            'pointRadius' => 4,
            'pointHoverRadius' => 6,
            'fill' => true,
            'tension' => 0.3,
            'dataDay' => $filterGroupedData($groupedDayAll, $recentDays),
            'dataWeek' => $filterGroupedData($groupedWeekAll, $recentWeeks),
            'dataMonth' => $filterGroupedData($groupedMonthAll, $recentMonths)
        ];

        foreach ($categories as $index => $category) {
            $categoryReportsDays = $dataDays->filter(fn($report) => $report->id_category == $category->id);
            $categoryReportsWeeks = $dataWeeks->filter(fn($report) => $report->id_category == $category->id);
            $categoryReportsMonths = $dataMonths->filter(fn($report) => $report->id_category == $category->id);

            $groupedDay = $groupByFormat($categoryReportsDays, 'Y-m-d');
            $groupedWeek = $categoryReportsWeeks->groupBy(function ($item) {
                return Carbon::parse($item->confirmed_at)->startOfWeek()->format('Y-m-d');
            });
            $groupedMonth = $groupByFormat($categoryReportsMonths, 'Y-m');

            $color = $category->color ?? $defaultColors[$index % count($defaultColors)];

            $chartData['categories'][$category->id] = [
                'name' => $category->name,
                'pointStyle' => 'circle',
                'color' => $color,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'pointBackgroundColor' => $color,
                'fill' => true,
                'tension' => 0.3,
                'dataDay' => $filterGroupedData($groupedDay, $recentDays),
                'dataWeek' => $filterGroupedData($groupedWeek, $recentWeeks),
                'dataMonth' => $filterGroupedData($groupedMonth, $recentMonths)
            ];
        }

        // Buat label unik dan urut
        $chartData['labelsDay'] = $recentDays->map(fn($d) => [
            'raw' => $d,
            'display' => Carbon::parse($d)->translatedFormat('d M Y')
        ])->toArray();

        $chartData['labelsWeek'] = $recentWeeks->map(fn($d) => [
            'raw' => $d,
            'display' => 'Minggu ' . Carbon::parse($d)->translatedFormat('d M')
        ])->toArray();

        $chartData['labelsMonth'] = $recentMonths->map(fn($d) => [
            'raw' => $d,
            'display' => Carbon::parse($d . '-01')->translatedFormat('F Y')
        ])->toArray();

        $data = User::where('username', '!=', 'superadmin')
            ->orderBy('last_active_at', 'desc')
            ->take(5)
            ->get();

        $latestNews = News::with('confirmReports')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $leaderboard = User::with('roles')
            ->whereHas('roles', function ($query) {
                $query->where('role_id', 3);
            })
            ->orderByDesc('poin')
            ->take(5)
            ->get();
        // end grafik

        $reportCategories = DisasterCategory::all();

        if ($isUser) {
            return view('page.components.index', compact('apiKey', 'news', 'consultation', 'informasiBPBD', 'infografisJember', 'infografisRaung'));
        } elseif ($isAdmin) {
            return view('dashboard.admin-dashboard', compact(
                'apiKey',
                'proses',
                'accept',
                'reject',
                'news',
                'consultation',
                'totalReportPending',
                'informasiBPBD',
                'infografisJember',
                'infografisRaung'
            ));
        } elseif ($isSuperAdmin) {
            return view('dashboard.super-admin-dashboard', [
                'apiKey' => $apiKey,
                'totalUser' => $totalUser,
                'totalUserPeople' => $totalUserPeople,
                'totalAdmin' => $totalAdmin,
                'totalReport' => $totalReport,
                'totalReportPending' => $totalReportPending,
                'totalReportProcess' => $totalReportProcess,
                'totalReportAccepted' => $totalReportAccepted,
                'totalReportRejected' => $totalReportRejected,
                'totalReportDone' => $totalReportDone,
                'data' => $data,
                'latestNews' => $latestNews,
                'leaderboard' => $leaderboard,
                'reportCategories' => $reportCategories,
                'chartData' => $chartData,
                'news' => $news,
                'consultation' => $consultation,
                'informasiBPBD',
                'infografisJember',
                'infografisRaung'
            ]);
        }
    }
    public function indexDashboard(Request $request)
    {

        $defaultStartDate = Carbon::now()->subMonth()->format('Y-m-d');
        $defaultEndDate = Carbon::now()->format('Y-m-d');
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $disasterCategories = DisasterCategory::all();
        $defaultColors = [
            '#733AEA',
            '#0FCA7A',
            '#F2426E',
            '#FD9722',
            '#1C1C1C',
            '#8A2BE2',
            '#2F4F7F',
            '#3E8E41',
            '#8B0A1A',
            '#F7DC6F',
            '#00BFFF',
            '#33CC33',
            '#FF0033',
            '#FFFF00',
            '#7A288A',
            '#F2C464',
            '#32CD32',
            '#032B44',
            '#FF0033',
            '#FFD700',
            '#ACFFAC'
        ];
        $chartData = [
            'labels' => [],
            'datasets' => []
        ];

        $disasterCounts = FacadesDB::table('confirm_reports')
            ->join('reports', 'confirm_reports.report_id', '=', 'reports.id')
            ->where('confirm_reports.status', 'accepted')
            ->whereBetween('confirm_reports.created_at', [$startOfYear, $endOfYear])
            ->selectRaw("DATE_FORMAT(confirm_reports.created_at, '%Y-%m') as month, reports.id_category, COUNT(*) as count")
            ->groupBy('month', 'reports.id_category')
            ->orderBy('month')
            ->get();


        $months = collect(range(1, 12))->map(function ($m) {
            $raw = Carbon::create(null, $m, 1)->format('Y-m');
            $display = Carbon::create(null, $m, 1)->translatedFormat('F');
            return ['raw' => $raw, 'display' => $display];
        });

        $chartData['labels'] = $months->pluck('display')->toArray();
        foreach ($disasterCategories as $index => $category) {
            $color = $category->color ?? $defaultColors[$index % count($defaultColors)];

            $categoryCounts = $months->map(function ($month) use ($disasterCounts, $category) {
                $count = $disasterCounts
                    ->where('month', $month['raw'])
                    ->where('id_category', $category->id)
                    ->first();
                return $count ? $count->count : 0;
            });

            $chartData['datasets'][] = [
                'label' => $category->name,
                'data' => $categoryCounts,
                'backgroundColor' => $color . '33',
                'borderColor' => $color,
                'borderWidth' => 2,
                'pointBackgroundColor' => $color,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'fill' => true,
                'tension' => 0.3
            ];
        }

        $stats = $this->confirmReport->getDataStats($defaultStartDate, $defaultEndDate);
        return view('dashboard.indexDashboard', [
            'startDate' => $defaultStartDate,
            'endDate' => $defaultEndDate,
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    public function getData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dataType = $request->input('data_type');

        if ($dataType === 'subdistrict') {
            $data = $this->confirmReport->getSubdistrictCount($startDate, $endDate);
            return DataTables::of($data)->addIndexColumn()->make(true);
        } elseif ($dataType === 'category') {
            $data = $this->confirmReport->getDisasterCategoryCount($startDate, $endDate);
            return DataTables::of($data)->addIndexColumn()->make(true);
        } elseif ($dataType === 'stats') {

            $stats = $this->confirmReport->getDataStats($startDate, $endDate);
            return response()->json($stats);
        } else {
            return response()->json(['data' => []]);
        }
    }

    public function indexInfografis(Request $request)
    {
        $defaultStartDate = Carbon::now()->subMonth()->format('Y-m-d');
        $defaultEndDate = Carbon::now()->format('Y-m-d');

        $startDate = $request->input('start_date', $defaultStartDate);
        $endDate = $request->input('end_date', $defaultEndDate);

        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();



        $subdistricts = [
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


        $disasterCategories = DisasterCategory::all();
        $defaultColors = [
            '#733AEA',
            '#0FCA7A',
            '#F2426E',
            '#FD9722',
            '#1C1C1C',
            '#8A2BE2',
            '#2F4F7F',
            '#3E8E41',
            '#8B0A1A',
            '#F7DC6F',
            '#00BFFF',
            '#33CC33',
            '#FF0033',
            '#FFFF00',
            '#7A288A',
            '#F2C464',
            '#32CD32',
            '#032B44',
            '#FF0033',
            '#FFD700',
            '#ACFFAC'
        ];
        $chartData = [
            'labels' => [],
            'datasets' => []
        ];

        $disasterCounts = FacadesDB::table('confirm_reports')
            ->join('reports', 'confirm_reports.report_id', '=', 'reports.id')
            ->where('confirm_reports.status', 'accepted')
            ->whereBetween('confirm_reports.created_at', [$startOfYear, $endOfYear])
            ->selectRaw("DATE_FORMAT(confirm_reports.created_at, '%Y-%m') as month, reports.id_category, COUNT(*) as count")
            ->groupBy('month', 'reports.id_category')
            ->orderBy('month')
            ->get();


        $months = collect(range(1, 12))->map(function ($m) {
            $raw = Carbon::create(null, $m, 1)->format('Y-m');
            $display = Carbon::create(null, $m, 1)->translatedFormat('F');
            return ['raw' => $raw, 'display' => $display];
        });

        $chartData['labels'] = $months->pluck('display')->toArray();
        foreach ($disasterCategories as $index => $category) {
            $color = $category->color ?? $defaultColors[$index % count($defaultColors)];

            $categoryCounts = $months->map(function ($month) use ($disasterCounts, $category) {
                $count = $disasterCounts
                    ->where('month', $month['raw'])
                    ->where('id_category', $category->id)
                    ->first();
                return $count ? $count->count : 0;
            });

            $chartData['datasets'][] = [
                'label' => $category->name,
                'data' => $categoryCounts,
                'backgroundColor' => $color . '33',
                'borderColor' => $color,
                'borderWidth' => 2,
                'pointBackgroundColor' => $color,
                'pointRadius' => 4,
                'pointHoverRadius' => 6,
                'fill' => true,
                'tension' => 0.3
            ];
        }




        return view('dashboard.infografis-dashboard', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'subdistricts' => $subdistricts,
            'disasterCategories' => $disasterCategories,
            'chartData' => $chartData,
            'disasterCounts' => $disasterCounts,
        ]);
    }

    // public function eartquake()
    // {
    //     $firebaseConfig = [
    //         'apiKey' => env('FIREBASE_API_KEY'),
    //         'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
    //         'databaseURL' => env('FIREBASE_DATABASE_URL'),
    //         'projectId' => env('FIREBASE_PROJECT_ID'),
    //         'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
    //         'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
    //         'appId' => env('FIREBASE_APP_ID'),
    //         'measurementId' => env('FIREBASE_MEASUREMENT_ID'),
    //     ];
    //     return view('dashboard.earthquake-dashboard', compact('firebaseConfig'));
    // }

    // public function getData(Request $request)
    // {
    //     $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
    //     $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

    //     $startDateTime = $startDate . ' 00:00:00';
    //     $endDateTime = $endDate . ' 23:59:59';

    //     // Fungsi untuk menangani kondisi tanggal
    //     $dateCondition = function ($query) use ($startDate, $endDate, $startDateTime, $endDateTime) {
    //         if ($startDate == $endDate) {
    //             $query->whereDate('created_at', $startDate);
    //         } else {
    //             $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
    //         }
    //     };


    //     $stats = [
    //         'total_kejadian' => ConfirmReport::where('status', 'accepted')
    //             ->where($dateCondition)
    //             ->count(),

    //         'affected_people' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_people'),


    //         'lightly_damaged_houses' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('lightly_damaged_houses'),

    //         'moderately_damaged_houses' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('moderately_damaged_houses'),

    //         'heavily_damaged_houses' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('heavily_damaged_houses'),

    //         'damaged_public_facilities' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('damaged_public_facilities'),

    //         'injured_people' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('injured_people'),

    //         'deceased_people' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('deceased_people'),

    //         'missing_people' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('missing_people'),

    //         'affected_babies' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_babies'),

    //         'affected_elderly' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_elderly'),

    //         'affected_disabled' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_disabled'),

    //         'affected_pregnant_women' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_pregnant_women'),

    //         'affected_general' => DisasterImpacts::whereHas('confirmReport', function ($query) use ($dateCondition) {
    //             $query->where('status', 'accepted')
    //                 ->where($dateCondition);
    //         })->sum('affected_general'),
    //     ];


    //     $disasterTypes = [];
    //     foreach (DisasterCategory::all() as $category) {
    //         $count = ConfirmReport::where('status', 'accepted')
    //             ->whereHas('report', function ($query) use ($category) {
    //                 $query->where('id_category', $category->id);
    //             })
    //             ->where($dateCondition)
    //             ->count();

    //         $disasterTypes[$category->name] = $count;
    //     }

    //     $subdistrictData = [];
    //     $subdistricts = [
    //         'AJUNG',
    //         'AMBULU',
    //         'ARJASA',
    //         'BALUNG',
    //         'BANGSALSARI',
    //         'GUMUKMAS',
    //         'JELBUK',
    //         'JENGGAWAH',
    //         'JOMBANG',
    //         'KALISAT',
    //         'KALIWATES',
    //         'KENCONG',
    //         'LEDOKOMBO',
    //         'MAYANG',
    //         'MUMBULSARI',
    //         'PAKUSARI',
    //         'PANTI',
    //         'PATRANG',
    //         'PUGER',
    //         'RAMBIPUJI',
    //         'SEMBORO',
    //         'SILO',
    //         'SUKORAMBI',
    //         'SUKOWONO',
    //         'SUMBERBARU',
    //         'SUMBERJAMBE',
    //         'SUMBERSARI',
    //         'TANGGUL',
    //         'TEMPUREJO',
    //         'UMBULSARI',
    //         'WULUHAN'
    //     ];

    //     foreach ($subdistricts as $subdistrict) {
    //         $count = ConfirmReport::where('status', 'accepted')
    //             ->whereHas('report', function ($query) use ($subdistrict) {
    //                 $query->where('subdistrict', $subdistrict); // Pastikan ini menggunakan = bukan LIKE
    //             })
    //             ->where($dateCondition)
    //             ->count();

    //         $subdistrictData[$subdistrict] = $count;
    //     }

    //     // dd($subdistrictData);

    //     return response()->json([
    //         'stats' => $stats,
    //         'disasterTypes' => $disasterTypes,
    //         'subdistrictData' => $subdistrictData,
    //     ]);
    // }
    public function homeuser()
    {
        return view('page.components.index');
    }

    // public function userdashboard()
    // {
    //     return view('dashboard.user-dashboard');
    // }

    public function getReportData(Request $request)
    {
        $filter = $request->input('filter', 'month');

        // Ambil semua kategori bencana
        $categories = DisasterCategory::all();

        $datasets = [];
        $colors = ['#733AEA', '#0FCA7A', '#F2426E', '#FD9722', '#1C1C1C', '#8A2BE2']; // Warna default

        foreach ($categories as $index => $category) {
            $data = Reports::whereHas('disasterCategory', function ($q) use ($category) {
                $q->where('id', $category->id);
            })
                ->selectRaw($this->getDateGrouping($filter))
                ->selectRaw('COUNT(*) as total')
                ->groupBy('date_group')
                ->orderBy('date_group')
                ->get();
            $color = $category->color ?? $colors[$index % count($colors)];

            $datasets[] = [
                'label' => $category->name,
                'data' => $data->pluck('total'),
                'borderColor' => $color,
                'backgroundColor' => $color . '33', // Tambahkan transparansi
                'tension' => 0.4
            ];
        }

        return response()->json([
            'labels' => $categories->isNotEmpty() ? $data->pluck('date_group') : [],
            'datasets' => $datasets
        ]);
    }

    private function getDateGrouping($filter)
    {
        switch ($filter) {
            case 'day':
                return "DATE_FORMAT(created_at, '%Y-%m-%d') as date_group";
            case 'week':
                return "CONCAT(YEAR(created_at), '-W', WEEK(created_at)) as date_group";
            case 'year':
                return "YEAR(created_at) as date_group";
            default: // month
                return "DATE_FORMAT(created_at, '%Y-%m') as date_group";
        }
    }

    public function mapLoad()
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');
        if (empty($apiKey)) {
            return response('Google Maps API Key not configured.', 500)
                ->header('Content-Type', 'application/javascript');
        }
        $googleMapsJsUrl = "https://maps.googleapis.com/maps/api/js?key={$apiKey}&callback=initMap&libraries=places";
        try {
            $response = Http::get($googleMapsJsUrl);
            if ($response->successful()) {
                $scriptContent = $response->body();
            } else {
                return response('Error loading Google Maps JavaScript.', 500)
                    ->header('Content-Type', 'application/javascript');
            }
        } catch (Exception $e) {
            return response('Error loading Google Maps JavaScript.', 500)
                ->header('Content-Type', 'application/javascript');
        }
        return response($scriptContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=86400');
    }
}

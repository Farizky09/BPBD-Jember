<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;


class NewsApiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $pePage = $request->query('per_page', 10);
            if (!$request->has('page')) {
                throw new \Exception('Parameter page harus disertakan untuk kebutuhan pagination');
            }
            if ($request->has('category')) {
                $news = News::whereHas('confirmReports.reports.disasterCategory', function ($query) use ($request) {
                    if ($request->category === "all") {
                        return;
                    }
                    $query->where('name', '=', $request->category);
                })
                    ->with([
                        'imageNews',
                        'confirmReports',
                        'confirmReports.reports',
                        'confirmReports.reports.disasterCategory' => function ($query) use ($request) {
                            if ($request->category === "all") {
                                return;
                            }
                            $query->where('name', '=', $request->category);
                        }
                    ])
                    ->where('status', '=', 'published')
                    ->orderBy('created_at', 'DESC')
                    // ->get()
                    // ->groupBy(function ($news) {
                    //     return Carbon::parse($news->created_at)->toDateString();
                    // })
                ;
            } else {

                $news = News::with(['imageNews'])
                    ->where('status', '=', 'published')
                    ->orderBy('created_at', 'DESC')
                    // ->get()
                    // ->groupBy(function ($news) {
                    //     return Carbon::parse($news->created_at)->toDateString();
                    // })
                ;
            }

            $paginated = $news->paginate($pePage);
            // $grouped = $paginated->getCollection()->groupBy(function ($news) {
            //     return Carbon::parse($news->created_at)->toDateString();
            // });
            // $paginated->setCollection($paginate);

            return ResponseHelper::success('Berhasil mendapatkan detail berita', $paginated, isPaginated: true);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mendapatkan detail berita', $e->getMessage());
        }
    }

    public function getById($slug)
    {

        try {
            $news = News::with(['imageNews', 'confirmReports.reports.disasterCategory'])->where('slug', $slug)->where('status', '=', 'published')->first();

            if (!$news) {
                return ResponseHelper::error('Berita tidak ditemukan', null, 404);
            }
            return ResponseHelper::success(
                'Berhasil mendapatkan berita',
                $news,
            );
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mendapatkan berita', $e->getMessage());
        }
    }

    public function getRecommendation(Request $request)
    {
        try {
            $news = News::with(['imageNews', 'confirmReports.reports.disasterCategory'])
                ->where('status', '=', 'published')
                ->where('slug', '!=', $request->slug)
                ->orderBy('created_at', 'DESC')
                ->take(5)
                ->get();

            return ResponseHelper::success('Berhasil mendapatkan berita rekomendasi', $news);
        } catch (\Exception $e) {
            return ResponseHelper::error('Terjadi kesalahan saat mendapatkan berita rekomendasi', $e->getMessage());
        }
    }

}

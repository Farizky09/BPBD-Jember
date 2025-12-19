<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ConfirmReportsInterfaces;
use App\Http\Controllers\Interfaces\NewsInterfaces;
use App\Models\ConfirmReport;
use App\Models\ImageNews;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    private $news;
    private $confirmReports;
    public function __construct(NewsInterfaces $news, ConfirmReportsInterfaces $confirmReports)
    {
        $this->news = $news;
        $this->confirmReports = $confirmReports;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = $this->news->datatable();


            return datatables()->of($data)
                ->addColumn('kd_report', fn($data) => $data->confirmReports->report->kd_report)
                ->addColumn('title', fn($data) => $data->title)
                ->addColumn('slug', fn($data) => $data->slug)
                ->addColumn('status', fn($data) => $data->status)
                ->addColumn('published_at', fn($data) => $data->published_at ?? '-')
                ->addColumn('takedown_at', fn($data) => $data->takedown_at ?? '-')
                ->addColumn('action', fn($data) => view('news.column.action', compact('data')))
                ->addIndexColumn()
                ->make(true);
        }
        $status = ['draft', 'published', 'takedown'];
        return view('news.index', compact('status'));
    }

    public function create()
    {


        $existingConfirmReportIds = News::pluck('id_confirm_reports')->toArray();
        $data = ConfirmReport::where('status', 'accepted')
            ->whereNotIn('id', $existingConfirmReportIds)
            ->whereNull('main_report_id')
            ->get();
        // dd($data);

        return view('news.create', compact('data'));
    }
    public function store(Request $request)
    {
        // return $request->all();

        $data = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'published_at' => 'required',
            'takedown_at' => 'nullable',

        ]);

        try {
            $data['id_confirm_reports'] = $request->id_confirm_reports;
            $user = Auth::user()->id;
            $data['status'] = 'draft';
            $title = $request->title;
            $data['slug'] = Str::slug(str_replace(' ', '-', $title)) . '-' . uniqid();
            if (!$request->published_at) {
                $data['published_at'] = null;
            } else {
                $data['published_at'] = $request->published_at;
            }

            if (!$request->takedown_at) {
                $data['takedown_at'] = null;
            } else {
                $data['takedown_at'] = $request->takedown_at;
            }


            $news = $this->news->store($data);
            // dd($news);
            foreach ($request->file('image') as $image) {
                $path = $image->store('image_news/' . now()->format('Y-m-d') . '/' . $title . $user, 'public');
                ImageNews::create([
                    'id_news' => $news->id,
                    'image_path' => $path
                ]);
            }
            // dd($news);
            return redirect()->route('news.index')->with('success', 'Berita berhasil ditambahkan');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with('error', 'Berita gagal ditambahkan' . $th->getMessage());
        }
    }

    public function detail($id)
    {
        $data = $this->news->show($id);
        return view('news.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->news->show($id);
        $confirmReport = ConfirmReport::where('status', 'accepted')
            ->whereNull('main_report_id')
            ->when($data->confirm_report_id, function ($query) use ($data) {
                $query->orWhere('id', $data->confirm_report_id);
            })
            ->with('report')
            ->get();
        if (!($data->status === 'draft')) {
            abort(403, 'Berita tidak dapat diubah');
        }
        return view('news.edit', compact('data', 'confirmReport'));
    }
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'title' => 'required',
            // 'slug' => 'required',
            'content' => 'required',

            'published_at' => 'nullable|date',
            'takedown_at' => 'nullable|date',
            'id_confirm_reports' => 'required',
        ]);

        try {
            $user = Auth::user()->id;
            $data['id_confirm_reports'] = $request->id_confirm_reports;
            $title = $request->title;
            $existingNews = $this->news->show($id);
            if ($title !== $existingNews->title) {
                $data['slug'] = Str::slug(str_replace(' ', '-', $title)) . '-' . uniqid();
            } else {
                $data['slug'] = $existingNews->slug;
            }
            if (!$request->published_at) {
                $data['published_at'] = null;
            } else {
                $data['published_at'] = $request->published_at;
            }

            if (!$request->takedown_at) {
                $data['takedown_at'] = null;
            } else {
                $data['takedown_at'] = $request->takedown_at;
            }

            $this->news->update($data, $id);

            if ($request->filled('deleted_images')) {
                $deletedIds = json_decode($request->deleted_images, true); // dari input hidden
                foreach ($deletedIds as $imageId) {
                    $image = ImageNews::find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path); // hapus file
                        $image->delete(); // hapus data dari DB
                    }
                }
            }

            // Upload gambar baru jika ada
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $uploadedImage) {
                    if ($uploadedImage->isValid()) {
                        $path = $uploadedImage->store('image_news/' . now()->format('Y-m-d') . '/' . $user, 'public');
                        ImageNews::create([
                            'id_news' => $id,
                            'image_path' => $path
                        ]);
                    }
                }
            }
            return redirect()->route('news.index')->with('success', 'Berita berhasil diubah');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with('error', 'Berita gagal diubah' . $th->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $this->news->delete($id);
            return response()->json(['success' => 'Berita berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Berita gagal dihapus' . $th->getMessage()]);
        }
    }

    public function publish($id)
    {
        try {
            $this->news->publish($id);
            return response()->json(['success' => 'Berita berhasil dipublikasikan']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Berita gagal dipublikasikan' . $th->getMessage()]);
        }
    }
    public function takedown($id)
    {
        try {
            $this->news->takedown($id);
            return response()->json(['success' => 'Berita berhasil ditakedown']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Berita gagal ditakedown' . $th->getMessage()]);
        }
    }

    // public function show($slug)
    // {
    //     $data = News::with('imageNews')
    //         ->where('slug', $slug)
    //         ->firstOrFail();

    //     $relatedData = News::where('id', '!=', $data->id)
    //         ->latest()
    //         ->take(5)
    //         ->get();

    //     return view('disaster_detail.index', compact('data', 'relatedData'));
    // }
}

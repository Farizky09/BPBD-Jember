<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ConsultationInterfaces;
use App\Models\DisasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    private $consultation;
    public function __construct(ConsultationInterfaces $consultation)
    {
        $this->consultation = $consultation;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->consultation->datatable();

            return datatables()->of($data)
                ->addColumn('typekategori_id', function ($data) {
                    return $data->consultations->name ?? '-';
                })
                ->addColumn('type', fn($data) => $data->type)
                ->addColumn('video_path', function ($data) {
                    if ($data->video_path && Str::contains($data->video_path, 'youtube.com/embed')) {
                        return '
                        <div style="width: 160px; height: 90px; overflow: hidden;">
                            <iframe
                                src="' . $data->video_path . '"
                                width="160" height="90"
                                style="border:0;"
                                allowfullscreen>
                            </iframe>
                        </div>';
                    }
                    return 'Tidak Ada Video';
                })

                ->addColumn('action', fn($data) => view('consultation.column.action', compact('data')))
                ->rawColumns(['video_path'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('consultation.index');
    }
    public function getById($id)
    {
        return $this->consultation->getById($id);
    }

    public function create()
    {
        $typekategori = DisasterCategory::all();
        return view('consultation.create', compact('typekategori'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = $request->validate([
            'typekategori_id' => 'required',
            'type' => 'required|string|max:255',
            'video_path' => 'required',
        ]);


        try {
            $type = $request->type;
            $typekategori_id = $request->typekategori_id;
            $this->consultation->store($data);
            return redirect()->route('consultation.index')->with('success', 'Berhasil menambahkan data');
        } catch (\Throwable $e) {
            throw $e;
            return redirect()->back()->with('error', 'Gagal menambahkan data' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $data = $this->consultation->getById($id);
        $typekategori = DisasterCategory::all();
        return view('consultation.edit', compact('data', 'typekategori'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'typekategori_id' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'video_path' => 'required',
        ]);
        try {
            $this->consultation->update($id, $data);
            return redirect()->route('consultation.index')->with('success', 'Berhasil memperbarui data');
        } catch (\Throwable $e) {
            throw $e;
            return redirect()->back()->with('error', 'Gagal memperbarui data' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {

            $this->consultation->delete($id);
            return response()->json(['success' => 'Berhasil menghapus data']);
        } catch (\Throwable $e) {
            throw $e;
            return response()->json(['error' => 'Gagal menghapus data' . $e->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Interfaces\InfografisInterface;
use App\Models\Infografis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InfografisController extends Controller
{
    private $infografis;
    public function __construct(InfografisInterface $infografis)
    {
        $this->infografis = $infografis;
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Infografis::query();
            if ($request->has('category') && $request->category !== '') {
                $query->where('category_image', $request->category);
            }

            $query->orderByDesc('id');

            return datatables()
                ->of($query->get())
                ->addColumn('name', function ($data) {
                    return $data->name;
                })
                ->addColumn('image', function ($data) {
                    if ($data->image) {
                        $imageUrl = asset('storage/' . $data->image);
                        return '
                            <a href="' . $imageUrl . '" data-lightbox="infografis" data-title="Gambar Informasi">
                                <img src="' . $imageUrl . '" alt="Infografis Image" style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;" />
                            </a>
                        ';
                    }

                    return 'No Image';
                })
                ->addColumn('category_image', function ($data) {
                    switch ($data->category_image) {
                        case 'head_image':
                            return 'Informasi BPBD';
                        case 'infografis_jember':
                            return 'Infografis Bulanan';
                        case 'infografis_raung':
                            return 'Infografis Bencana';
                        default:
                            return $data->category_image;
                    }
                })
                ->addColumn('created_at', function ($data) {
                    return Carbon::parse($data->created_at)->translatedFormat('d/F/Y');
                })
                ->addColumn('action', function ($data) {
                    return view('infografis.action.index', compact('data'));
                })
                ->rawColumns(['image', 'category_image', 'action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('infografis.index');
    }

    public function getById($id)
    {
        return $this->infografis->getById($id);
    }
    public function create()
    {
        $infografis = $this->infografis->get();
        return view('infografis.create', compact('infografis'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        $validate = $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_image' => 'required'
        ]);
        try {
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $datePath = date('Y-m-d');
                $path = $image->store("infografis/{$datePath}", 'public');
                $validate['image'] = $path;
            }
            $this->infografis->store($validate);

            return redirect()->route('infografis.index')->with('success', 'Berhasil menambahkan data');
        } catch (\Throwable $th) {
            return redirect()->route('infografis.index')->with('error', 'Gagal menyimpan data.');
        }
    }

    public function show() {}

    public function edit($id)
    {
        $infografis = $this->infografis->getById($id);
        $data = Infografis::get();
        return view('infografis.edit', compact('infografis', 'data'));
    }

    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'category_image' => $request->category_image,
        ];

        try {
            $infografis = $this->infografis->getById($id);
            if ($request->hasFile('image')) {

                if ($infografis->image && Storage::disk('public')->exists($infografis->image)) {
                    Storage::disk('public')->delete($infografis->image);
                }

                $datePath = date('Y-m-d');
                $path = $request->file('image')->store("infografis/{$datePath}", 'public');
                $data['image'] = $path;
            }
            $this->infografis->update($id, $data);

            return redirect()->route('infografis.index')->with('success', 'Data infografis berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Gagal memperbarui data.');
        }
    }

    public function delete($id)
    {
        try {
            $this->infografis->delete($id);
            return response()->json(['success' => 'Berhasil menghapus data']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Gagal menghapus data']);
        }
    }
}

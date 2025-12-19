<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ConfirmReportsInterfaces;
use App\Http\Controllers\Interfaces\ReportsInterfaces;
use App\Models\DisasterCategory;
use App\Models\ImageReport;
use App\Models\Reports;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    private $reports;
    private $confirmReports;

    public function __construct(ReportsInterfaces $reports, ConfirmReportsInterfaces $confirmReports)
    {
        $this->reports = $reports;
        $this->confirmReports = $confirmReports;
    }


    public function index(Request $request)
    {
        // dd($this->reports->datatable('pending'));
        // $type = $request->is('reports/history') ? 'history' : 'pending';

        if ($request->ajax()) {
            $data = $this->reports->datatable();

            return datatables()->of($data)
                ->addColumn('kd_report', fn($data) => $data->kd_report)
                ->addColumn('subdistrict', fn($data) => $data->subdistrict)
                ->addColumn('address', fn($data) => $data->address)
                ->addColumn('status', fn($data) => $data->status)
                ->addColumn('action', fn($data) => view('reports.column.action', compact('data')))
                ->addColumn('pengirim', function ($data) {
                    if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin')) {
                        return $data->user->name;
                    }
                    return null;
                })
                ->addIndexColumn()
                ->make(true);
        }
        return view('reports.index');
    }



    public function getById($id)
    {
        return $this->reports->getById($id);
    }

    public function create()
    {
        $user = Auth::user();
        // if (is_null($user->nik) || is_null($user->image_avatar) || is_null($user->photo_identity_path)) {
        //     return back()->with('incomplete_profile', true);
        // }

        $category = DisasterCategory::get();
        return view('reports.create', compact('category'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $user = Auth::user();
        // if (is_null($user->nik) || is_null($user->image_avatar) || is_null($user->photo_identity_path)) {
        //     return back()->with('incomplete_profile', true);
        // }
        // return $request->all();
        $datavalidate = $request->validate([
            // 'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required',
            'description' => 'required',
            'id_category' => 'required',
            'subdistrict' => 'required',


        ]);

        try {
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
            $data = $datavalidate;
            $data['user_id'] = $userId;
            $data['status'] = "pending";
            $data['kd_report'] = $kdReport;
            $subdistrictRequest = $request->subdistrict;
            $cleanedSubdistrict = preg_replace('/^(Kecamatan|Kec\.?)\s*/i', '', $subdistrictRequest);
            $data['subdistrict'] = trim($cleanedSubdistrict);
            $report = $this->reports->store($data);

            foreach ($request->file('image') as $image) {
                $path = $image->store('image_report/' . now()->format('Y-m-d') . '/' . $data['user_id'], 'public');
                ImageReport::create([
                    'report_id' => $report->id,
                    'image_path' => $path
                ]);
            }

            if ($request->from == 'dashboard') {
                return redirect()->route('reports.index')->with('success', 'Laporan berhasil dikirim');
            }

            return back()->with('success', 'Laporan berhasil dikirim');
            // return redirect()->route('reports.index')->with('success', 'Laporan berhasil dikirim');
        } catch (\Throwable $th) {
            // dd($th);
            // Tampilkan error di halaman sebelumnya
            return redirect()->route('reports.index')->with('error', 'Gagal: ' . $th->getMessage());
        }
    }


    public function detail($id)
    {
        $data = $this->reports->show($id);
        // dd($data->images);
        return view('reports.detail', compact('data'));
    }

    public function edit($id)
    {
        $data = $this->reports->getById($id);
        $category = DisasterCategory::get();

        if (!($data->status === 'pending' && $data->user_id === Auth::id())) {
            abort(403, 'Unauthorized action.');
        }

        return view('reports.edit', compact('data', 'category'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $validate = $request->validate([
            // 'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'address' => 'required',
            'description' => 'required',
            'id_category' => 'required',
            'subdistrict' => 'required',
        ]);

        try {
            $data = $validate;
            $this->reports->update($data, $id);

            // Hapus gambar yang dipilih untuk dihapus
            if ($request->filled('deleted_images')) {
                $deletedIds = json_decode($request->deleted_images, true); // dari input hidden
                foreach ($deletedIds as $imageId) {
                    $image = ImageReport::find($imageId);
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
                        $path = $uploadedImage->store('image_report/' . now()->format('Y-m-d') . '/' . Auth::id(), 'public');
                        ImageReport::create([
                            'report_id' => $id,
                            'image_path' => $path
                        ]);
                    }
                }
            }

            return redirect()->route('reports.index')->with('success', 'Laporan berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->route('reports.edit', $id)->with('error', 'Laporan gagal diubah ' . $th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->reports->delete($id);
            return response()->json(['success' => 'Laporan berhasil dihapus']);
        } catch (\Throwable $th) {
            // dd($th);
            return response()->json(['error' => 'Laporan gagal dihapus' . $th->getMessage()]);
        }
    }

    public function process($id)
    {
        try {

            // dd($this->reports->process($id));
            $result = $this->reports->process($id);

            // dd($result);
            $this->confirmReports->store([
                'report_id' => $result->id,
                'admin_id' => Auth::user()->id,
                'status' => 'proses'
            ]);
            return response()->json(['success' => 'Laporan berhasil diprosess']);
        } catch (\Throwable $th) {
            // dd($result);
            return response()->json(['error' => 'Laporan gagal diprosess' . $th->getMessage()]);
        }
    }

    public function location(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');

        $apiKey = env('GOOGLE_MAPS_API_KEY');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$apiKey}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response);

        if ($data && $data->status === 'OK' && isset($data->results[0])) {
            $address = $data->results[0]->formatted_address;
            return response()->json([
                'status' => 'success',
                'display_name' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
            ]);
        }

        return response()->json(['status' => 'error', 'message' => 'Unable to retrieve your address']);
    }
}

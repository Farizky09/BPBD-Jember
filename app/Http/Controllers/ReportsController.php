<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ReportsInterfaces;
use App\Http\Requests\ReportRequest;
use App\Models\DisasterCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    private $reports;

    public function __construct(ReportsInterfaces $reports)
    {
        $this->reports = $reports;
    }


    public function index(Request $request)
    {
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
                        return $data->user_name;
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
        $category = DisasterCategory::get();
        return view('reports.create', compact('category'));
    }

    public function store(ReportRequest $request)
    {
        try {
            // dd($request->all());
            $this->reports->store($request->validated());

            if ($request->from == 'dashboard') {
                return redirect()->route('reports.index')->with('success', 'Laporan berhasil dikirim');
            }
            return back()->with('success', 'Laporan berhasil dikirim');
        } catch (\Throwable $th) {
            return redirect()->route('reports.index')->with('error', 'Gagal: ' . $th->getMessage());
        }
    }


    public function detail($id)
    {
        $data = $this->reports->show($id);
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

    public function update(ReportRequest $request, $id)
    {

        try {
            $this->reports->update($request->validated(), $id);
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
            return response()->json(['error' => 'Laporan gagal dihapus' . $th->getMessage()]);
        }
    }

    public function process($id)
    {
        try {
            $this->reports->process($id);
            return response()->json(['success' => 'Laporan berhasil diprosess']);
        } catch (\Throwable $th) {
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

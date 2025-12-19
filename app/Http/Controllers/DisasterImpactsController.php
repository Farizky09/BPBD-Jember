<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\DisasterImpactsInterfaces;
use App\Models\ConfirmReport;
use App\Models\DisasterImpacts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisasterImpactsController extends Controller
{
    private $disasterImpacts;
    public function __construct(DisasterImpactsInterfaces $disasterImpacts)
    {
        $this->disasterImpacts = $disasterImpacts;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->disasterImpacts->datatable();
            return datatables()->of($data)
                ->addColumn('kd_report', fn($data) => $data->confirmReport->report->kd_report ?? "-")
                ->addColumn('disaster_category', fn($data) => $data->confirmReport->report->disasterCategory->name ?? "-")
                ->addColumn('address', fn($data) => $data->confirmReport->report->address ?? "-")
                ->addColumn('action', function ($data) {
                    return view('disaster_impacts.column.action', compact('data'));
                })
                ->addIndexColumn()
                ->make(true);
        }

        return view('disaster_impacts.index');
    }

    public function create($id)
    {
        $confirmReports = ConfirmReport::find($id);
        return view('disaster_impacts.create', compact('confirmReports'));
    }
    public function store(Request $request)
    {
        // return $request->all();
        // dd($request->all());
        $data = $request->validate([
            'confirm_report_id' => 'required|exists:confirm_reports,id',
            'lightly_damaged_houses' => 'nullable|integer|min:0',
            'moderately_damaged_houses' => 'nullable|integer|min:0',
            'heavily_damaged_houses' => 'nullable|integer|min:0',
            'damaged_public_facilities' => 'nullable|integer|min:0',
            'missing_people' => 'nullable|integer|min:0',
            'injured_people' => 'nullable|integer|min:0',
            // 'affected_people' => 'nullable|integer|min:0',
            'deceased_people' => 'nullable|integer|min:0',
            'affected_babies' => 'nullable|integer|min:0',
            'affected_elderly' => 'nullable|integer|min:0',
            'affected_disabled' => 'nullable|integer|min:0',
            'affected_general' => 'nullable|integer|min:0',
            'affected_pregnant_women' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'logistic_aid_description' => 'nullable|string'
        ]);

        try {
            $date = Carbon::now();
            $userId = Auth::user()->id;

            $existingToday = DisasterImpacts::whereDate('created_at', $date->toDateString())->count();


            $sequenceNumber = $existingToday + 1;
            $kdDisasterImpact = sprintf(
                "Penanganan/%s/%s/%s/U%02d/%04d",
                $date->format('Y'),
                $date->format('m'),
                $date->format('d'),
                $userId,
                $sequenceNumber
            );
            $data['kd_disaster_impacts'] = $kdDisasterImpact;
            $this->disasterImpacts->store($data);
            return redirect()->route('disaster_impacts.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            return redirect()->route('disaster_impacts.create')->with('error', 'Data gagal disimpan');
        }
    }
    public function edit($id)
    {
        $disasterImpact = $this->disasterImpacts->getById($id);
        return view('disaster_impacts.edit', compact('disasterImpact'));
    }
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'confirm_report_id' => 'required|exists:confirm_reports,id',
            'lightly_damaged_houses' => 'nullable|integer|min:0',
            'moderately_damaged_houses' => 'nullable|integer|min:0',
            'heavily_damaged_houses' => 'nullable|integer|min:0',
            'damaged_public_facilities' => 'nullable|integer|min:0',
            'missing_people' => 'nullable|integer|min:0',
            'injured_people' => 'nullable|integer|min:0',
            // 'affected_people' => 'nullable|integer|min:0',
            'deceased_people' => 'nullable|integer|min:0',
            'logistic_aid_description' => 'nullable|string'
        ]);
        try {

            $this->disasterImpacts->update($id, $data);
            return redirect()->route('disaster_impacts.index')->with('success', 'Data berhasil diupdate');
        } catch (\Throwable $th) {
            return redirect()->route('disaster_impacts.edit', $id)->with('error', 'Data gagal diupdate');
        }
    }
    public function delete($id)
    {
        try {
            $this->disasterImpacts->delete($id);
            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal dihapus! ' . $th->getMessage()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\RecapExport;
use App\Http\Controllers\Interfaces\ConfirmReportsInterfaces;
use App\Models\DisasterCategory;
use App\Models\DisasterVictims;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\View\Components\Confirm;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\RecapExport;

class RecapController extends Controller
{
    private $confirmReports;

    public function __construct(ConfirmReportsInterfaces $confirmReports)
    {
        $this->confirmReports = $confirmReports;
    }

    public function index(Request $request)
    {
        // dd($request->all());
        // dd($this->confirmReports->recapDataTables());
        if ($request->ajax()) {
            $data = $this->confirmReports->recapDataTables();
            return datatables()->of(source: $data)
                ->addColumn('kd_report', fn($data) => $data->report->kd_report ?? '-')
                ->addColumn('sender', fn($data) => $data->report->user->name ?? '-')
                ->addColumn('subdistrict', fn($data) => $data->report->subdistrict ?? '-')
                ->addColumn('address', fn($data) => $data->report->address ?? '-')
                ->addColumn('category', fn($data) => $data->report->disasterCategory->name ?? '-')
                ->addColumn('disaster_level', fn($data) => $data->disaster_level ?? '-')
                ->addColumn('status', fn($data) => $data->status ?? '-')
                ->addColumn('approve_by', fn($data) => $data->admin->name ?? '-')
                ->addColumn('action', fn($data) => view('recap.column.action', compact('data')))
                ->addIndexColumn()
                ->make(true);
        }
        $categories = DisasterCategory::all();

        $status = ['pending', 'accepted', 'rejected'];
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
        $normalizedSubdistricts = array_map(function ($item) {
            return ucwords(strtolower($item));
        }, $subdistricts);
        return view('recap.index', compact('status', 'categories', 'subdistricts', 'normalizedSubdistricts'));
    }

    public function detailRecaps($id)
    {
        $data = $this->confirmReports->detailRecaps($id);
        return view('recap.detail', compact('data'));
    }

    public function exportPDFRecap(Request $request)
    {
        try {
            $data = $this->confirmReports->getDataExportRecap($request);
            $pdf = Pdf::loadView('recap.export_pdf', [
                'data' => $data
            ])->setPaper('A3', 'landscape');
            return $pdf->download('Rekap_data_' . now()->format('YmdHis') . '.pdf');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()
                ->with('error', 'Gagal: ' . $th->getMessage());
        }
    }

    public function exportExcelRecap(Request $request)
    {
        try {
            $data = $this->confirmReports->getDataExportRecap($request);

            return Excel::download(new \App\Exports\RecapExport($data), 'Rekapitulasi' . now()->format('YmdHis') . '.xlsx');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()
                ->with('error', 'Gagal: ' . $th->getMessage());
        }
    }

    public function dataTableKorban(Request $request, $id)
    {
        if ($request->ajax()) {

            $confirmReport = $this->confirmReports->detailRecaps($id);
            $victims = $confirmReport->disasterImpacts->disasterVictims ?? collect();
            return datatables()->of($victims)

                ->addColumn('fullname', fn($victim) => $victim->fullname ?? '-')
                ->addColumn('nik', fn($victim) => $victim->nik ?? '-')
                ->addColumn('kk', fn($victim) => $victim->kk ?? '-')
                ->addColumn('gender', function ($victim) {
                    return $victim->gender === 'male' ? 'Laki-laki' : ($victim->gender === 'female' ? 'Perempuan' : '-');
                })
                ->addColumn('age', fn($victim) => $victim->age ?? '-')
                ->addColumn('family_status', fn($victim) => $victim->family_status ?? '-')
                ->addColumn('phone_number', fn($victim) => $victim->phone_number ?? '-')
                ->addColumn('birth_place', fn($victim) => $victim->birth_place ?? '-')
                ->addColumn('birth_date', fn($victim) => $victim->birth_date ? \Carbon\Carbon::parse($victim->birth_date)->translatedFormat('d-m-Y') : '-')
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['error' => 'Invalid Request'], 400);
    }
}

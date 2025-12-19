<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\DisasterReportDocumentationsInterfaces;
use App\Models\ConfirmReport;
use App\Models\DisasterCategory;
use App\Models\DisasterReportDocumentations;
use App\Models\ImageDisasterReports;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\View\Components\Confirm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class DisasterReportDocumentationsController extends Controller
{
    private $disasterReportDocumentations;
    public function __construct(DisasterReportDocumentationsInterfaces $disasterReportDocumentations)
    {
        $this->disasterReportDocumentations = $disasterReportDocumentations;
    }

    public function index(Request $request)
    {
        // dd($this->disasterReportDocumentations->datatable());
        if ($request->ajax()) {
            $data = $this->disasterReportDocumentations->datatable();
            // dd($data);
            return datatables()->of($data)
                ->addColumn('kd_report', fn($data) => $data->confirmReport->report->kd_report)
                ->addColumn('time', fn($data) => $data->confirmReport->confirmed_at ?? '-')
                ->addColumn('subdistrict', fn($data) => $data->confirmReport->report->subdistrict ?? '-')
                ->addColumn('address', fn($data) => $data->confirmReport->report->address ?? '-')
                ->addColumn('disaster_category', fn($data) => $data->confirmReport->report->disasterCategory->name ?? '-')
                ->addColumn('disaster_chronology', fn($data) => $data->disaster_chronology ?? '-')
                ->addColumn('disaster_impact', fn($data) => $data->disaster_impact ?? '-')
                ->addColumn('disaster_response', fn($data) => $data->disaster_response ?? '-')
                ->addColumn('action', function ($data) {
                    return view('disaster_report_documentations.column.action', compact('data'));
                })
                ->rawColumns(['disaster_chronology', 'disaster_impact', 'disaster_response'])
                ->addIndexColumn()
                ->make(true);
            // dd($data);
        }
        $categories = DisasterCategory::all();


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
        return view('disaster_report_documentations.index', compact('categories', 'subdistricts', 'normalizedSubdistricts'));
    }

    public function create()
    {
        $existingConfirmReportIds = DisasterReportDocumentations::pluck('confirm_report_id')->toArray();
        $confirmReports = ConfirmReport::where('status', 'accepted')
            ->whereNotIn('id', $existingConfirmReportIds)
            ->whereNull('main_report_id')
            ->orderBY('confirmed_at', 'desc')
            ->get();
        // $confirmReports = ConfirmReport::where('status', 'accepted')->get();
        return view('disaster_report_documentations.create', compact('confirmReports'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'confirm_report_id' => 'required|exists:confirm_reports,id',
            'disaster_chronology' => 'nullable|string',
            'disaster_impact' => 'nullable|string',
            'disaster_response' => 'nullable|string',
        ]);

        try {
            $disasterReportDocumentations = $this->disasterReportDocumentations->store($data);
            $kd_report = $disasterReportDocumentations->confirmReport->report->kd_report;

            foreach ($request->file('image') as $image) {
                $path = $image->store('image_disaster_reports/' . now()->format('Y-m-d') . '/' . $kd_report, 'public');
                ImageDisasterReports::create([
                    'disaster_report_documentation_id' => $disasterReportDocumentations->id,
                    'image_path' => $path
                ]);
            }
            return redirect()->route('disaster_report_documentations.index')->with('success', 'Data berhasil disimpan');
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return redirect()->route('disaster_report_documentations.create')->with('error', 'Data gagal disimpan');
        }
    }

    public function detail($id)
    {

        $data = $this->disasterReportDocumentations->show($id);
        $existingConfirmReportIds = DisasterReportDocumentations::pluck('confirm_report_id')->toArray();


        if ($data->confirm_report_id) {
            $existingConfirmReportIds = array_diff($existingConfirmReportIds, [$data->confirm_report_id]);
        }
         $confirmReports = ConfirmReport::where('status', 'accepted')
            ->whereNotIn('id', $existingConfirmReportIds)
            ->whereNull('main_report_id')
            ->with('report')
            ->orderBy('confirmed_at', 'desc')
            ->get();
        return view('disaster_report_documentations.detail', compact('data', 'confirmReports'));
    }

    public function edit($id)
    {
        $data = $this->disasterReportDocumentations->getById($id);
        $existingConfirmReportIds = DisasterReportDocumentations::pluck('confirm_report_id')->toArray();


        if ($data->confirm_report_id) {
            $existingConfirmReportIds = array_diff($existingConfirmReportIds, [$data->confirm_report_id]);
        }

        $confirmReports = ConfirmReport::where('status', 'accepted')
            ->whereNotIn('id', $existingConfirmReportIds)
            ->whereNull('main_report_id')
            ->with('report')
            ->orderBy('confirmed_at', 'desc')
            ->get();

        // dd($confirmReports);
        return view('disaster_report_documentations.edit', compact('data', 'confirmReports'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $data = $request->validate([
            'confirm_report_id' => 'required|exists:confirm_reports,id',
            'disaster_chronology' => 'nullable|string',
            'disaster_impact' => 'nullable|string',
            'disaster_response' => 'nullable|string',
        ]);

        try {
            $doc = $this->disasterReportDocumentations->update($id, $data);
            $kdReport = $doc->confirmReport->report->kd_report;
            if ($request->filled('deleted_images')) {
                $deletedIds = json_decode($request->deleted_images, true);
                foreach ($deletedIds as $imageId) {
                    $image = ImageDisasterReports::find($imageId);
                    if ($image) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
            }

            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $uploadedImage) {
                    if ($uploadedImage->isValid()) {
                        $path = $uploadedImage->store('image_disaster_reports/' . now()->format('Y-m-d') . '/' . $kdReport, 'public');
                        ImageDisasterReports::create([
                            'disaster_report_documentation_id' => $doc->id,
                            'image_path' => $path
                        ]);
                    }
                }
            }

            return redirect()->route('disaster_report_documentations.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->route('disaster_report_documentations.edit', $id)->with('error', 'Data gagal diperbarui');
        }
    }
    public function delete($id)
    {
        try {
            $this->disasterReportDocumentations->delete($id);
            return response()->json(['success' => 'Data berhasil dihapus']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Data gagal dihapus! ' . $th->getMessage()]);
        }
    }

    public function exportPDF(Request $request)
    {
        // dd($this->disasterReportDocumentations->getDataExport($request));
        $data = $this->disasterReportDocumentations->getDataExport($request);
        $pdf = Pdf::loadView('disaster_report_documentations.export_pdf', [
            'data' => $data
        ])->setPaper([0, 0, 595.28, 935.43], 'landscape'); // F4 size: 21x33 cm in points
        return $pdf->download('Dokumentasi_Laporan_Bencana_' . now()->format('YmdHis') . '.pdf');
        }

    public function exportExcel(Request $request)
    {
        try {
            $data = $this->disasterReportDocumentations->getDataExport($request);
            // dd($data);
            return Excel::download(new \App\Exports\DisasterReportExport($data), 'disaster_report_documentations_' . now()->format('YmdHis') . '.xlsx');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()
                ->with('error', 'Gagal: ' . $th->getMessage());
        }
    }
    }

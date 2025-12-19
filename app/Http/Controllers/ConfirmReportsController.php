<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\ConfirmReportsInterfaces;
use App\Models\ConfirmReport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ConfirmReportsController extends Controller
{
    private $confirmReports;

    public function __construct(ConfirmReportsInterfaces $confirmReports)
    {
        $this->confirmReports = $confirmReports;
    }

    public function index(Request $request)
    {
        // return $this->confirmReports->datatable();
        if ($request->ajax()) {

            $data = $this->confirmReports->datatable();
            // dd($data);

            return datatables()->of($data)

                ->addColumn('kd_report', fn($data) => $data->report->kd_report)
                ->addColumn('address', fn($data) => $data->report->address)
                ->addColumn('category_name', fn($data) => $data->report->DisasterCategory->name ??  "-" )
                ->addColumn('status', fn($data) => $data->status ?? "-")
                ->addColumn('disaster_level', fn($data) => $data->disaster_level ?? "-")
                ->addColumn('notes', fn($data) => $data->notes ?? "-")
                ->addColumn('admin', function ($data) {
                    if (Auth::user()->hasRole(['admin', 'super_admin'])) {
                        return $data->report->user->name ?? "-";
                    } else {
                        return $data->admin->name ?? "-";
                    }
                })
                ->addColumn('action', fn($data) => view('confirm_reports.column.action', compact('data')))
                ->addIndexColumn()
                ->make(true);
        }
        $status = ['proses', 'accepted', 'rejected', 'netral'];
        return view('confirm_reports.index', compact('status'));
    }

    public function getById($id)
    {
        return $this->confirmReports->getById($id);
    }
    public function detail($id)
    {
        $user = Auth::user();

        $data = $this->confirmReports->show($id);
        // dd($data->report->user->poin);
        $disaster_level = ['low', 'medium', 'high', 'extreme'];
        return view('confirm_reports.detail', compact('data', 'disaster_level', 'user'));
    }
    public function create()
    {
        return view('confirm_reports.create');
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $data['admin_id'] = Auth::user()->id;

            $this->confirmReports->store($data);

            return redirect()->route('confirm-reports.index')->with('success', 'Report confirmed successfully.');
        } catch (\Throwable $th) {
            throw $th;
            // return redirect()->back()
        }
    }
    public function edit($id)
    {
        $confirmReport = $this->confirmReports->getById($id);
        return view('confirm_reports.edit', compact('confirmReport'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'report_id' => 'required|exists:reports,id',
            'status' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $this->confirmReports->update($id, $data);

        return redirect()->route('confirm-reports.index')->with('success', 'Report updated successfully.');
    }
    public function delete($id)
    {
        $this->confirmReports->delete($id);
        return redirect()->route('confirm-reports.index')->with('success', 'Report deleted successfully.');
    }

    public function accept(Request $request, $id)
    {
        $request->validate([
            'disaster_level' => 'required|in:low,medium,high,extreme'
        ]);

        try {
            $this->confirmReports->accepted(
                [
                    'disaster_level' => $request->disaster_level,
                    'notes' => $request->notes
                ],
                $id
            );
            return redirect()->route('confirm-reports.index')->with('success', 'Laporan berhasil diterima!');
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()
                ->with('error', 'Laporan gagal diterima' . $th->getMessage());
        }
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'notes' => 'required',
        ]);

        try {
            $this->confirmReports->rejected(
                ['notes' => $request->notes],
                $id
            );

            return redirect()->route('confirm-reports.index')->with('success', 'Laporan berhasil ditolak!');
        } catch (\Throwable $th) {

            return redirect()->back()
                ->with('error', 'Laporan gagal ditolak ' . $th->getMessage());
        }
    }


    public function export_pdf(Request $request)
    {
        try {
            $data = $this->confirmReports->getDataExport($request);

            $pdf = Pdf::loadView('confirm_reports.export_pdf', [
                'data' => $data
            ]);

            return $pdf->download('confirmed_reports_' . now()->format('YmdHis') . '.pdf');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()
                ->with('error', 'Gagal: ' . $th->getMessage());
        }
    }

    public function export_excel(Request $request)
    {
        try {
            $data = $this->confirmReports->getDataExport($request);
            // dd($data);
            return Excel::download(new \App\Exports\ConfirmReportsExport($data), 'confirmed_reports_' . now()->format('YmdHis') . '.xlsx');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()
                ->with('error', 'Gagal: ' . $th->getMessage());
        }
    }
}

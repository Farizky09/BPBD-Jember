<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DisasterReportExport implements FromView
{

    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
    return view('disaster_report_documentations.export-excel', [
            'data' => $this->data,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Interfaces;

// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;

interface ConfirmReportsInterfaces
{
    public function get();
    public function getById($id);
    public function show($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
    public function datatable();
    public function accepted($data, $id);
    public function rejected($data, $id);
    public function recapDataTables();
    public function detailRecaps($id);
    public function getDataExportRecap(Request $request);

    public function getDataExport(Request $request);
}

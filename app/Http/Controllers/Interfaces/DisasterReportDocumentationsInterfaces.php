<?php

namespace App\Http\Controllers\Interfaces;

interface DisasterReportDocumentationsInterfaces
{
    public function get();

    public function getById($id);

    public function show($id);

    public function store($data);
    public function update($data, $id);
    public function delete($id);

    public function datatable();
    public function getDataExport();
}

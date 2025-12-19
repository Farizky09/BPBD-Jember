<?php

namespace App\Http\Controllers\Interfaces;

interface ReportsInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function show($id);
    public function update($data, $id);
    public function process($id);
    
    public function accept($data, $id);
    public function reject($data, $id);
    public function delete($id);
    public function datatable();
    // public function datatable2();
}

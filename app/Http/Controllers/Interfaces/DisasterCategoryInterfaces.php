<?php

namespace App\Http\Controllers\Interfaces;

interface DisasterCategoryInterfaces
{
    public function get();
    public function getByid($id);
    public function store($data);
    public function update($data, $id);
    public function delete($id);
    public function datatable();
}

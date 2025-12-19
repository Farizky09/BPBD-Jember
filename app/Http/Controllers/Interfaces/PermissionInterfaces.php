<?php

namespace App\Http\Controllers\Interfaces;

interface PermissionInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function show();
    public function update($data, $id);
    public function delete($id);
    public function datatable();
}

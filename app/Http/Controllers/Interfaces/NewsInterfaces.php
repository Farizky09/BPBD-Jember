<?php

namespace App\Http\Controllers\Interfaces;

interface NewsInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function show($id);
    public function update($data, $id);
    public function publish($id);
    public function takedown($id);
    public function delete($id);
    public function datatable();
}

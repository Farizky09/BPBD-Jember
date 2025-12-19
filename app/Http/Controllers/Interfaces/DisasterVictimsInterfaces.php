<?php

namespace App\Http\Controllers\Interfaces;

interface DisasterVictimsInterfaces
{
    public function get();
    public function datatable();

    public function getById($id);

    public function store(array $data);

    public function update(array $data, $id);

    public function delete($id);
}

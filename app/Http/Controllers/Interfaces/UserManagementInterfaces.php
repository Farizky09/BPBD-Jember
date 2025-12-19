<?php

namespace App\Http\Controllers\Interfaces;

interface UserManagementInterfaces
{
    public function get();
    public function getById($id);
    public function store($data);
    public function show();
    public function update($data, $id);
    public function updatePermission($data, $id);
    public function delete($id);
    public function datatable();
    public function banUser($id);
    public function unBanUser($id);
}

<?php

namespace App\Http\Controllers\Interfaces;

interface ProfileInterfaces
{
    public function getById($id);
    public function updateProfile($id, $data);
    public function checkOldPassword($id, $oldPassword);
}

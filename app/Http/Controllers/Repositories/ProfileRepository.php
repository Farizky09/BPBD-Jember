<?php

namespace App\Http\Controllers\Repositories;

use App\Http\Controllers\Interfaces\ProfileInterfaces;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileRepository implements ProfileInterfaces
{

    private $profile;

    public function __construct(User $profile)
    {
        $this->profile = $profile;
    }

    public function getById($id)
    {
        return $this->profile->findOrFail($id);
    }

    public function updateProfile($id, $data)
    {
        $profile = $this->profile->find($id);
        $profile->update($data);
        return $profile;
    }

    public function checkOldPassword($id, $oldPassword)
    {
        $profile = $this->profile->find($id);
        return Hash::check($oldPassword, $profile->password);
    }
}

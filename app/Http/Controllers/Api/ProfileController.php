<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'message' => 'Profile retrieved successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'image_avatar' => $user->image_avatar
                    ? asset('storage/' . $user->image_avatar)
                    : null,
                'poin' => $user->poin,
                'last_active_at' => $user->last_active_at,
                'is_active' => $user->is_active,
            ]
        ], 200);
    }

public function getUserById(Request $request, $id)
{
    $user = $request->user();

    return response()->json([
        'message' => 'User retrieved successfully',
        'data' => [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'image_avatar' => $user->image_avatar
                ? asset('storage/' . $user->image_avatar)
                : null,
        ]
    ], 200);
}


    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'string|max:13',
            'image_avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors(),
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->only(['name', 'email', 'phone_number']);
        if ($request->hasFile('image_avatar')) {
            if ($user->image_avatar && Storage::exists('public/' . $user->image_avatar)) {
                Storage::delete('public/' . $user->image_avatar);
            }

            $file = $request->file('image_avatar');
            $folderpath = 'image_avatar/' . now()->format('Y-m-d') . '/' . $user->id;
            $path = $file->store($folderpath, 'public');

            $data['image_avatar'] = $path;
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'image_avatar' => $user->image_avatar
                    ? asset('storage/' . $user->image_avatar)
                    : null,
            ]
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = $request->user();

        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['Password lama tidak cocok.'],
            ]);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password berhasil diperbarui.',
        ]);
    }
}

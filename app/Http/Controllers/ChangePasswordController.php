<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePassword()
    {
        return view('auth.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);
        try {
            $user = auth()->user();

            User::where('id', $user->id)->update([
                'password' => Hash::make($request->password),
            ]);
            return redirect()->route('dashboard')->with('success', 'Password berhasil diubah');
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->back()->with('error', 'Gagal mengubah password: ' . $th->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;


class LoginGoogleApiController extends Controller
{
    public function loginGoogle(Request $request){
        $validate = $request->validate([
            'access_token' => 'required|string',
        ]);
        try {

            $user = User::firstOrCreate(
                ['email' => $request->email],
                ['name' => $request->name,
                'phone_number'=> '_',
                'password'=> 'password',
                'username' => explode('@',  $request->email)[0] . rand(1000,9999),
                ]
            );
            $user->assignRole('user');

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'=>'error',
                'message'=>'Gagal login dengan google',
                'error'=> $e->getMessage()
            ], 500
        );
        }
    }
}

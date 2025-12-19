<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\MailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpApiController extends Controller
{
    public function sendOtp(Request $request)
    {

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            // Generate OTP
            $otp = rand(100000, 999999);
            Mail::to($user->email)->send(new MailOtp($otp));

            return response()
                ->json([
                    'message' => 'OTP sent successfully',
                    'otp' => $otp
                ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()
                ->json([
                    'message' => 'Failed to send OTP',
                    'error' => $th->getMessage()
                ], 500);
        }
    }

    public function resendOtp(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }


            $otp = rand(100000, 999999);
            Mail::to($user->email)->send(new MailOtp($otp));

            return response()
                ->json([
                    'message' => 'OTP resent successfully',
                    'otp' => $otp
                ], 200);
        } catch (\Throwable $th) {
            throw $th;
            return response()
                ->json([
                    'message' => 'Failed to resend OTP',
                    'error' => $th->getMessage()
                ], 500);
        }
    }
}

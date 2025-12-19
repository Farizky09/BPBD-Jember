<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use App\Mail\MailOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(Request $request)
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            $email = $socialUser->getEmail();

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $socialUser->getName() ?? explode('@', $email)[0],
                    'phone_number' => '_',
                    'password' => Hash::make('password'),
                    'username' => explode('@', $email)[0] . rand(1000, 9999),
                ]
            );

            if (!$user->hasRole('user')) {
                $user->assignRole('user');
            }



            if ($user->is_banned == "permanent") {
                Auth::guard('web')->logout();
                return redirect()->route('ban-permanent');
            }

            if ($user->is_banned == "temporary") {
                Auth::guard('web')->logout();
                $data = [
                    'id' => $user->id,
                    'banned_until' => $user->banHistories()->latest('created_at')->value('banned_until'),
                ];
                session('ban', $data);
                $request->session()->put('ban', $data);
                return redirect()->route('ban-temporary');
            }

            Auth::login($user);
            return redirect()->route('page.home');
        } catch (\Exception $e) {
            Log::error('Google OAuth callback error: ' . $e->getMessage());
            return redirect()->route('dashboard')->withErrors(['Gagal login dengan Google.']);
        }
    }

    public function verifyForm()
    {
        return view('auth.login-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('otp');
        $exp = session('expOtp');
        $email = session('emailOtp');
        $phone_number = session('phone_number');
        $password = session('password');

        if (!$otp || now()->greaterThan($exp) || $request->otp != $otp) {
            return back()->withErrors(['otp' => 'OTP tidak valid, kirim ulang kode OTP.']);
        }


        session()->forget(['otp', 'expOtp', 'emailOtp']);


        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => explode('@', $email)[0],
                'phone_number' => $phone_number,
                'password' => $password,
                'username' => explode('@', $email)[0] . rand(1000, 9999),
            ]
        );
        $user->assignRole('user');

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function resendOtp()
    {
        $email = session('emailOtp');

        if (!$email) {
            return redirect()->route('login')->withErrors(['Email tidak ditemukan di sesi.']);
        }

        $otp = rand(100000, 999999);

        session([
            'otp' => $otp,
            'expOtp' => now()->addMinutes(2),
        ]);

        Mail::to($email)->send(new MailOtp($otp));

        return back()->with('status', 'Kode OTP baru telah dikirim.');
    }
}

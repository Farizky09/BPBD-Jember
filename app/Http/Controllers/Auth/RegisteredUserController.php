<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailOtp;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'phone_number' => ['required', 'string', 'min:10', 'unique:' . User::class],
            'password' => ['required', 'min:8'],
        ]);

        // Simpan data user ke session sementara
        session([
            'register_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'username' => '-',
            ]
        ]);

        // Generate dan kirim OTP
        $otp = rand(100000, 999999);
        session([
            'otp' => $otp,
            'expOtp' => now()->addMinutes(2),
            'phone_number' => $request->phone_number,
            'emailOtp' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Mail::to($request->email)->send(new MailOtp($otp));

        return redirect()->route('login-otp.verify')->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }

    // Tampilkan form OTP
    public function showOtpForm(): View
    {
        return view('auth.register-otp');
    }

    // Proses verifikasi OTP
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $otp = session('otp');
        $exp = session('expOtp');
        $registerData = session('register_data');

        if (!$otp || now()->greaterThan($exp) || $request->otp != $otp) {
            return back()->withErrors(['otp' => 'OTP tidak valid atau sudah kadaluarsa.']);
        }

        // Buat user setelah OTP valid
        $user = User::create($registerData);
        $user->assignRole('user');
        $user->last_active_at = now();
        $user->is_active = true;
        $user->save();
        event(new Registered($user));

        // Bersihkan session OTP
        session()->forget(['otp', 'expOtp', 'emailOtp', 'register_data']);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    // Resend OTP
    public function resendOtp(): RedirectResponse
    {
        $email = session('emailOtp');
        if (!$email) {
            return redirect()->route('register')->withErrors(['Email tidak ditemukan di sesi.']);
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

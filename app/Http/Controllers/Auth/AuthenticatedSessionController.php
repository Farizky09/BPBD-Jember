<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            if ($user->is_banned === 'temporary') {
                $data = [
                    'id' => $user->id,
                    'banned_until' => $user->banHistories()->latest('created_at')->value('banned_until'),
                ];
                session('ban', $data);
                Auth::guard('web')->logout();
                $request->session()->flush();
                $request->session()->invalidate();
                $request->session()->put('ban', $data);
                $request->session()->regenerateToken();
                return redirect()->route('ban-temporary');
            } elseif ($user->is_banned == 'permanent') {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('ban-permanent');
            } else {
                if (password_verify("password", $user->password)) {
                    return redirect()->route('user.change-password');
                } else {
                    $user->last_active_at = now();
                    $user->is_active = true;
                    $user->save();
                    return redirect()->intended(route('dashboard'));
                }
            }
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            $user->last_active_at = now();
            $user->is_active = false;
            $user->save();
            // DB::table('sessions')->where('user_id', $user->id)->delete();
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        // Cache::flush();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

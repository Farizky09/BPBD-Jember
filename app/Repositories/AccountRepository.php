<?php

namespace App\Repositories;

use App\Interfaces\AccountInterface;
use App\Mail\SendResetPasswordLink;
use App\Models\ResetPasswordToken;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AccountRepository implements AccountInterface
{

    protected $resetPasswordToken;
    protected $user;
    public function __construct(ResetPasswordToken $resetPasswordToken, User $user)
    {
        $this->resetPasswordToken = $resetPasswordToken;
        $this->user = $user;
    }
    public function forgotPassword()
    {

    }
    public function generateResetPasswordToken($length = 32)
    {
        return rtrim(strtr(base64_encode(random_bytes($length)), '+/', '-_'), '=');
    }

    public function
    validateTokenSenderByEmail($email)
    {
        return $this->user->where('email', '=', $email)->exists();


    }
    public function validateTokenSenderExpires($email): bool
    {
        $hasValidTokenByEmail = $this->resetPasswordToken->where('email', '=', $email)->where('expires_at', '>', Carbon::now())->first();
        // return $hasValidTokenByEmail;
        if ($hasValidTokenByEmail) {
            return true;
        }
        return false;

    }

    public function saveResetToken($token, $email)
    {
        $expiresAt = Carbon::now()->addMinutes(30);
        $this->resetPasswordToken->create([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt,
            'is_used' => false
        ]);
    }

    public function sendResetUrl($data)
    {
        return Mail::to($data['email'])->send(new SendResetPasswordLink($data));
    }

    public function resetPassword($request)
    {
        $user = $this->resetPasswordToken::with('user')->where('token', $request->token)->first();
        $this->resetPasswordToken::where('token', '=', $request->token)->update(['is_used' => true]);
        $this->user->where('email', '=', $user->email)->update(['password' => Hash::make($request->new_password)]);
    }
    public function validateTokenUsed($token)
    {
        $resetToken = $this->resetPasswordToken::where('token', '=', $token)->latest()->first();
        return $resetToken->is_used;
    }
}

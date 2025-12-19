<?php

namespace App\Http\Controllers;

use App\Interfaces\AccountInterface;
use App\Mail\SendResetPasswordLink;
use Carbon\Doctrine\CarbonTypeConverter;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

// use Illuminate\Support\Facades\Request;

class AccountController extends Controller
{
    protected $account;
    public function __construct(AccountInterface $accountInterface)
    {
        $this->account = $accountInterface;
    }
    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
    public function index($token)
    {
        if ($this->account->validateTokenUsed($token)) {
            return view('auth.login');
        }
        return view('auth.reset-password', ['token' => $token]);
    }
    public function resetPassword($token, Request $request)
    {

        DB::beginTransaction();
        try {
            $request['token'] = $token;
            $this->account->resetPassword($request);
            DB::commit();
            return view('auth.login');
        } catch (Exception $exceptoin) {
            DB::rollback();
        }
    }
    public function sendResetLink(Request $request)
    {
        DB::beginTransaction();
        try {
            $email = $request->email;
            if ($this->account->validateTokenSenderByEmail($email) == false) {
                return response()->json(
                    ['status' => false, 'error' => 'email_not_found', 'message' => 'Email tidak ditemukan!']
                );
            }
            if ($this->account->validateTokenSenderExpires($email) == true) {
                return response()->json(
                    ['status' => false, 'error' => 'token_has_used', 'message' => 'Kamu sudah melakukan pengajuan reset password!']
                );
            }
            $resetPasswordToken = $this->account->generateResetPasswordToken();
            $this->account->saveResetToken($resetPasswordToken, $email);
            $data = [
                'email' => $email,
                'reset_url' => url('/forgot-password/' . $resetPasswordToken)
            ];
            Mail::to($data['email'])->sendNow(new SendResetPasswordLink($data));
            DB::commit();
            return response()->json(
                ['status' => true, 'message' => 'Link reset password sudah dikirim ke email kamu!']
            ,201);
        } catch (Exception $exception) {
            DB::rollBack();
            return $exception;
        }
    }
}

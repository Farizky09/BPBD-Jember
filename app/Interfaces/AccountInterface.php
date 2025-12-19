<?php

namespace App\Interfaces;

interface AccountInterface
{
    public function forgotPassword();
    public function generateResetPasswordToken($length = 32);
    public function saveResetToken($token, $email);
    public function sendResetUrl($data);
    public function validateTokenSenderByEmail($email);
    public function validateTokenSenderExpires($email);
    public function resetPassword($request);
    public function validateTokenUsed($token);
}

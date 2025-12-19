<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPasswordToken extends Model
{
    protected $table = 'reset_password_tokens';
    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'is_used'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

}

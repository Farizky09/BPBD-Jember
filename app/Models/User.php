<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    const SUPER_ADMIN = 'super_admin';

    const ADMIN = 'admin';

    const USER = 'user';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone_number',
        'password',
        'image_avatar',
        'poin',
        'last_active_at',
        'is_active',
        'is_banned',
        'is_permanent_ban',
        'banned_until',
        'photo_identity_path',
        'nik',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function UserReport()
    {
        $this->hasMany(Reports::class, 'user_id', 'id');
    }

    public function confirmReport()
    {
        $this->hasMany(ConfirmReport::class, 'admin_id', 'id');
    }
    public function banHistories()
    {
        return $this->hasMany(BanHistories::class, 'user_id', 'id');
    }

    // protected static function booted()
    // {
    //     static::retrieved(function ($model) {
    //         $model->checkUpdateBanned();
    //         $model->save();
    //     });
    // }

    // public function checkUpdateBanned()
    // {
    //     $now = Carbon::now();


    //     if ($this->poin <= 0 && !$this->is_banned && is_null($this->banned_until)) {
    //         $this->is_banned = true;
    //         $this->banned_until = $now->copy()->addDays(7);
    //     } elseif ($this->is_banned && $this->banned_until && $this->banned_until <= $now) {
    //         $this->is_banned = false;
    //         // $this->banned_until = null;
    //         $this->poin = 100;
    //     } elseif (!$this->is_banned && $this->banned_until && $this->poin <= 0) {
    //         $this->is_permanent_ban = true;
    //         $this->banned_until = null;
    //     }
    // }
}

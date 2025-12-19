<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class BanHistories extends Model
{
    use HasFactory;
    protected $table = 'ban_histories';
    protected $fillable = [
        'user_id',
        'banned_at',
        'banned_until',
        'is_permanent_ban',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

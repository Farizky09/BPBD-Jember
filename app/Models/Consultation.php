<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Consultation extends Model
{
    use HasFactory;
    protected $table = 'consultation';
    protected $fillable = [
        'typekategori_id',
        'type',
        'video_path',
    ];

    public function consultations()
    {
        return $this->belongsTo(DisasterCategory::class, 'typekategori_id', 'id');
    }
}

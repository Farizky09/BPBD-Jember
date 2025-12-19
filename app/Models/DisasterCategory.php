<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class DisasterCategory extends Model
{
    use HasFactory;
    protected $table = 'disaster_category';
    protected $fillable = [
        'name',
        'type',

    ];

    public function reports()
    {
        return $this->hasMany(Reports::class, 'id_category', 'id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'typekategori_id', 'id');
    }
}

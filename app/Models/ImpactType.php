<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImpactType extends Model
{
    use HasFactory;
    protected $table = 'impact_type';
    protected $fillable = [
        'name',

    ];

    public function disasterVictims()
    {
        return $this->belongsToMany(DisasterVictims::class, 'victim_has_impact_type',
            'impact_type_id', 'disaster_victim_id');
    }
}

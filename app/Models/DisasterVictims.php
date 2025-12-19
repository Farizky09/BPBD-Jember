<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisasterVictims extends Model
{
    use HasFactory;
    protected $table = 'disaster_victims';
    protected $fillable = [
        'disaster_impact_id',
        'fullname',
        'nik',
        'kk',
        'gender',
        'age',
        'family_status',
        'phone_number',
        'birth_place',
        'birth_date',
        'vulnerable_group',
    ];

    public function disasterImpact()
    {
        return $this->belongsTo(DisasterImpacts::class, 'disaster_impact_id');
    }
    public function ImpactTypes()
    {
        return $this->belongsToMany(ImpactType::class, 'victim_has_impact_type', 'disaster_victim_id', 'impact_type_id');
    }
}

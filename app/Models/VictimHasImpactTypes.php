<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VictimHasImpactTypes extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $table = 'victim_has_impact_type';

    protected $fillable = [
        'disaster_victim_id',
        'impact_type_id',
    ];

    public function disasterVictim()
    {
        return $this->belongsTo(DisasterVictims::class, 'disaster_victim_id');
    }

    public function impactType()
    {
        return $this->belongsTo(ImpactType::class, 'impact_type_id');
    }
}

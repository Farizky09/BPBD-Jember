<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class DisasterImpacts extends Model
{
    use HasFactory;
    protected $table = 'disaster_impacts';

    protected $fillable = [
        'confirm_report_id',
        'kd_disaster_impacts',
        'lightly_damaged_houses',
        'moderately_damaged_houses',
        'heavily_damaged_houses',
        'damaged_public_facilities',
        'missing_people',
        'injured_people',
        // 'affected_people',
        'deceased_people',
        'affected_babies',
        'affected_elderly',
        'affected_disabled',
        'affected_pregnant_women',
        'affected_general',
        'description',
        'logistic_aid_description'
    ];

    public function confirmReport()
    {
        return $this->belongsTo(ConfirmReport::class, 'confirm_report_id');
    }
    public function disasterVictims()
    {
        return $this->hasMany(DisasterVictims::class, 'disaster_impact_id');
    }
    protected function affectedPeople(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) =>
            (int) $attributes['affected_babies'] +
                (int) $attributes['affected_elderly'] +
                (int) $attributes['affected_disabled'] +
                (int) $attributes['affected_pregnant_women'] +
                (int) $attributes['affected_general'],
        );
    }
}

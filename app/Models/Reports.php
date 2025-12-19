<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'kd_report',
        'latitude',
        'longitude',
        'address',
        'description',
        'user_id',
        'status',
        'subdistrict',
        'id_category'

    ];

    public function images()
    {
        return $this->hasMany(ImageReport::class, 'report_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function confirmReport()
    {
        return $this->hasOne(ConfirmReport::class, 'report_id', 'id');
    }

    public function disasterCategory()
    {
        return $this->belongsTo(DisasterCategory::class, 'id_category');
    }
}

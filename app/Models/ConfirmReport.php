<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'admin_id',
        'status',
        'notes',
        'confirmed_at',
        'disaster_level',
        'main_report_id'
    ];
    protected $table = 'confirm_reports';

    public function report()
    {
        return $this->belongsTo(Reports::class, 'report_id');
    }
    public function reports()
    {
        return $this->belongsTo(Reports::class, 'report_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function news()
    {
        return $this->belongsTo(News::class, 'id_confirm_reports');
    }
    public function disasterImpacts()
    {
        return $this->hasOne(DisasterImpacts::class, 'confirm_report_id');
    }
    public function mainReport()
    {
        return $this->belongsTo(ConfirmReport::class, 'main_report_id');
    }

    public function duplicateReports()
    {
        return $this->hasMany(ConfirmReport::class, 'main_report_id');
    }
}

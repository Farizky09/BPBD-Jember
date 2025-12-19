<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisasterReportDocumentations extends Model
{
    use HasFactory;
    protected $table = 'disaster_report_documentations';
    protected $fillable = [
        'confirm_report_id',
        'disaster_chronology',
        'disaster_impact',
        'disaster_response',
    ];

    public function confirmReport()
    {
        return $this->belongsTo(ConfirmReport::class, 'confirm_report_id');
    }

    public function images()
    {
        return $this->hasMany(ImageDisasterReports::class, 'disaster_report_documentation_id');
    }
}

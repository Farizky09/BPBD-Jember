<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageDisasterReports extends Model
{
    use HasFactory;
    protected $table = 'image_disaster_report';
    protected $fillable = [
        'disaster_report_documentation_id',
        'image_path',
    ];

    public function disasterReportDocumentations()
    {
        return $this->belongsTo(DisasterReportDocumentations::class, 'disaster_report_documentation_id');
    }
}

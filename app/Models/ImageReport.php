<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageReport extends Model
{
    use HasFactory;

    protected $table = 'image_reports';
    protected $fillable = ['id', 'report_id', 'image_path'];


    public function Report()
    {
        return $this->belongsTo(Reports::class, 'report_id');
    }
}

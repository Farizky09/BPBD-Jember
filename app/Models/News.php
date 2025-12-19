<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $fillable = [
        'id_confirm_reports',
        'title',
        'slug',
        'content',
        'status',
        'published_at',
        'takedown_at',
    ];

    public function confirmReports()
    {
        return $this->belongsTo(ConfirmReport::class, 'id_confirm_reports');
    }
    public function imageNews()
    {
        return $this->hasMany(ImageNews::class, 'id_news');
    }

    public static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            $model->checkAndUpdateStatus();
        });
    }

    public function checkAndUpdateStatus()
    {
        $now = Carbon::now();

       
        if (
            $this->status === 'draft' &&
            $this->published_at &&
            $this->published_at <= $now
        ) {
            $this->update(['status' => 'published']);
        }


        if (
            $this->status === 'published' &&
            $this->takedown_at &&
            $this->takedown_at <= $now
        ) {
            $this->update(['status' => 'takedown']);
        }
    }
}

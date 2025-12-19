<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImageNews extends Model
{
    protected $table = 'image_news';
    protected $fillable = [
        'id_news',
        'image_path',
    ];

    public function news()
    {
        return $this->belongsTo(News::class, 'id_news');
    }
}

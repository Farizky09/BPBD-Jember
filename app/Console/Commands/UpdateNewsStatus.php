<?php

namespace App\Console\Commands;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateNewsStatus extends Command
{
    protected $signature = 'news:update-status';

    public function handle()
    {

        News::where('status', 'draft')
            ->where('published_at', '<=', now())
            ->update([
                'status' => 'published',
                'published_at' => now()
            ]);


        News::where('status', 'published')
            ->where('takedown_at', '<=', now())
            ->update([
                'status' => 'takedown',
                'takedown_at' => now()
            ]);

        $this->info('Updated: ' . now());
    }
}

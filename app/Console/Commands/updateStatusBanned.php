<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class updateStatusBanned extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-status-banned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $users = User::with(['banHistories' => function ($query) {
            $query->orderBy('banned_at', 'desc');
        }])->get();

        foreach ($users as $user) {
            $latestBan = $user->banHistories->first();
            $banCount = $user->banHistories->count();


            if ($banCount === 0 && $user->poin <= 0 && $user->is_banned === 'none') {
                $user->banHistories()->create([
                    'user_id' => $user->id,
                    'banned_at' => now(),
                    'is_permanent_ban' => false,
                    'banned_until' => now()->addDays(7),
                ]);
                $user->update(['is_banned' => 'temporary']);
                continue;
            }


            if (
                $banCount === 1 &&
                $user->is_banned === 'temporary' &&
                $latestBan->banned_until <= now() &&
                $user->poin <= 0
            ) {
                $user->update([
                    'is_banned' => 'none',
                    'poin' => 100,
                ]);
                continue;
            }


            if (
                $banCount === 1 &&
                $user->is_banned === 'none' &&
                $user->poin <= 0
            ) {
                $user->banHistories()->where('id', $latestBan->id)->update([
                    'banned_at' => now(),
                    'is_permanent_ban' => true,
                    'banned_until' => null,
                ]);
                $user->update(['is_banned' => 'permanent']);
            }
        }

        $this->info('Proses selesai');
    }
}

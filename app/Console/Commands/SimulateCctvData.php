<?php

namespace App\Console\Commands;

use App\Services\CctvDataService;
use Illuminate\Console\Command;

class SimulateCctvData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cctv:simulate {--interval=5}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate CCTV data updates for testing real-time monitoring';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $interval = (int) $this->option('interval');
        $cctvService = app(CctvDataService::class);

        $this->info("🎥 Starting CCTV Data Simulation...");
        $this->info("📊 Interval: {$interval} seconds");
        $this->info("🛑 Press Ctrl+C to stop\n");

        $minLevel = 0.0;
        $maxLevel = 2.0;
        $currentLevel = 0.5;
        $direction = 0.05; // Change per update

        while (true) {
            // Simulate water level changes
            $currentLevel += $direction;

            if ($currentLevel >= $maxLevel) {
                $direction = -0.05;
                $currentLevel = $maxLevel;
            } elseif ($currentLevel <= $minLevel) {
                $direction = 0.05;
                $currentLevel = $minLevel;
            }

            // Get latest data
            $latestData = $cctvService->getLatestData();

            if ($latestData) {
                $this->info(sprintf(
                    "✅ [%s] Level: %.3f m | Image: %s",
                    now()->format('H:i:s'),
                    $latestData['level_meter'],
                    basename($latestData['image_path'] ?? 'N/A')
                ));
            } else {
                $this->warn("⚠️  No data available yet");
            }

            sleep($interval);
        }
    }
}

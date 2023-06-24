<?php

namespace App\Console;

use App\Http\Controllers\Api\FetchNewsController;
use App\Services\NewsService;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel 
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        /**
         * Get from NewsAPI in background
         */
        $schedule->call(function() {
            $newsService = app(NewsService::class);
        $fetchNewsController = new FetchNewsController($newsService);
        $fetchNewsController->fetchNews();
        })->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

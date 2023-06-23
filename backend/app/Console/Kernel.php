<?php

namespace App\Console;

use App\Http\Controllers\FetchNewsController;

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
            (new FetchNewsController())->getFromNewsAPI();
            (new FetchNewsController())->getFromGuardian();
            (new FetchNewsController())->getFromNyTimes();
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

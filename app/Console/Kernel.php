<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\UpdateMicrosoftCatalog;
use App\Jobs\UpdateFuturskillsCatalog;
use App\Jobs\UpdateOpenCampusCatalog;
use App\Jobs\UpdateOpenVhbCatalog;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        #$schedule->command('inspire')->everyFifteenMinutes();
        $schedule->job(new UpdateMicrosoftCatalog)->weeklyOn(7, '1:00');
        $schedule->job(new UpdateFuturskillsCatalog)->weeklyOn(7, '1:05');
        $schedule->job(new UpdateOpenCampusCatalog)->weeklyOn(7, '1:10');
        $schedule->job(new UpdateOpenVhbCatalog)->weeklyOn(7, '1:15');
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

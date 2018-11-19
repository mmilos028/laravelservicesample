<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Helpers\ErrorHelper;
use App\Helpers\IPAddressHelper;

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
        // $schedule->command('inspire')
        //          ->hourly();

        /*
        $schedule->call(function(){

            $detected_ip_address = IPAddressHelper::getDetectedIPAddress();
            $time = date("d M Y H:i:s");
            $message = "TEST SCHEDULE >>> TIME {$time} >>> Detected IP Address = {$detected_ip_address}";
            ErrorHelper::writeInfo($message, $message);

        })
            ->everyFiveMinutes();
        */

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

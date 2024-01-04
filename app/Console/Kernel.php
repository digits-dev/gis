<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\DatabaseBackup',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('db:backup')->daily()->at('04:00');
        $schedule->call('\App\Http\Controllers\Submaster\AdminItemsController@getItemsUpdatedAPI')->hourly()->between('9:00', '23:00');
        $schedule->call('\App\Http\Controllers\Submaster\AdminItemsController@getItemsCreatedAPI')->hourly()->between('9:00', '23:00');

        // backup for capsule inventory
        $schedule->call('\App\Http\Controllers\Capsule\AdminInventoryCapsulesController@createBackUp')->daily()->at('01:00');
        // backup for capsule sales
        $schedule->call('\App\Http\Controllers\Capsule\AdminCapsuleSalesController@createBackUp')->daily()->at('01:00');
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

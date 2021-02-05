<?php

/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

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
        //
    ];

    /**
     * Get the timezone that should be used by default for scheduled events.
     *
     * @return \DateTimeZone|string|null
     */
    protected function scheduleTimezone()
    {
        return 'Europe/Paris';
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // On arrète les Jobs dans les files d'attente (exécution puis arrêt) et on relance une file d'attente
        // On s'assure ainsi que s'il y a plusieurs processus (queue:work) lancés, tous soient arrêtés. Et on en relance qu'un ensuite.
        $schedule->command('queue:restart')->everyThirtyMinutes();
        $schedule->command('queue:work database --tries=3 --sleep=3 --timeout=30')->dailyAt('02:00');

        // On exécute un dump de la bdd à 02:30
        // $schedule->command('dump:mysql')->dailyAt('02:30');

        // On vide tout le cache une fois par jour à 03:00
        $schedule->command('cache:clear')->dailyAt('03:00');

        // On recharge tous les caches à 03:01
        $schedule->command('refresh:cache')->dailyAt('03:01');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

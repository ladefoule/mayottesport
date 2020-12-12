<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Console;

use App\Saison;
use App\CrudTable;
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
        // On vide tout le cache une fois par jour à 03:00
        $schedule->command('cache:clear')->dailyAt('03:00');

        // On recharge tous les caches à 03:01
        $schedule->call(function () {

            // Les caches index et indexCrud ainsi que les listeAttributsVisibles pour chaque table
            $crudTables = CrudTable::all();
            foreach ($crudTables as $crudTable){
                if(! in_array($crudTable->nom, config('constant.tables-non-crudables'))){
                    $crudTable->index();
                    $crudTable->listeAttributsVisibles();
                    $crudTable->listeAttributsVisibles('create');
                    $crudTable->listeAttributsVisibles('show');
                    $crudTable->indexCrud();
                }
            }

            // Liste des tables crudables.
            CrudTable::navbarCrudTables();

            // Les caches saisons/journees et matches QUE pour les saisons en cours
            $saisons = Saison::all();
            foreach ($saisons as $saison){
                if(! $saison->finie){
                    $saison->infos();

                    $matches = $saison->matches;
                    foreach ($matches as $match)
                        $match->infos();

                    $journees = $saison->journees;
                    foreach ($journees as $journee)
                        $journee->infos();

                }
            }

        })->dailyAt('03:01');
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

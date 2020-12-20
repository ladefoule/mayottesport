<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Console\Commands;

use App\Saison;
use App\CrudTable;
use Illuminate\Console\Command;

class CachesRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rechargement du cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Les caches index et indexCrud ainsi que les listeAttributsVisibles pour chaque table
        $crudTables = CrudTable::all();
        foreach ($crudTables as $crudTable) {
            if (!in_array($crudTable->nom, config('constant.tables-non-crudables'))) {
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
        foreach ($saisons as $saison) {
            if (! $saison->finie) {
                $matches = $saison->matches;
                foreach ($matches as $match)
                    $match->infos();

                $journees = $saison->journees;
                foreach ($journees as $journee)
                    $journee->infos();

                $saison->infos();
            }
        }
    }
}

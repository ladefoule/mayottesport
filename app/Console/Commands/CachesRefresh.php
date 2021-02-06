<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Console\Commands;

use App\Saison;
use App\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info(" -------- Command refresh:cache -------- ");
        // Les caches index et indexCrud ainsi que les listeAttributsVisibles pour chaque table
        
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        foreach ($tables as $table) {
            if (!in_array($table, config('listes.tables-non-indexables')))
                index($table);
        }

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

        // Les caches des articles
        $articles = Article::all();
        foreach ($articles as $article)
            $article->infos();
    }
}

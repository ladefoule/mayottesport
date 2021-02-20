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
        
        $tables = DB::select('SHOW TABLES');
        $tables = array_map('current', $tables);

        foreach ($tables as $table)
            if (! in_array($table, config('listes.tables-non-indexables')))
                index($table);

        // Les caches des matches
        $saisons = index('saisons');
        foreach ($saisons as $saison){
            $journees = index('journees')->where('saison_id', $saison->id);
            foreach ($journees as $journee) {
                $matches = index('matches')->where('journee_id', $journee->id);
                foreach ($matches as $match)
                    infos('matches', $match->id);

                infos('journees', $journee->id);
                
            }
            infos('saisons', $saison->id);
        }

        // Les caches des articles
        $articles = index('articles');
        foreach ($articles as $article)
            infos('articles', $article->id);

        Log::info(" -------- FIN Command refresh:cache -------- ");
    }
}

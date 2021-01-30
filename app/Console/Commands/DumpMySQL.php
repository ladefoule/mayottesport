<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\DbDumper\Databases\MySql;
use Spatie\DbDumper\Compressors\GzipCompressor;

class DumpMySQL extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dump:mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sauvegarde de la base de données';

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
        Log::info(" -------- Command dump:mysql -------- ");
        
        MySql::create()
            ->setDbName(env('DB_DATABASE'))
            ->setUserName(env('DB_USERNAME'))
            ->setPassword(env('DB_PASSWORD'))
            ->useCompressor(new GzipCompressor())
            ->dumpToFile('storage/dumpsql/'. date('Y-m-d---h-i-s') .'.sql.gz');
    }
}

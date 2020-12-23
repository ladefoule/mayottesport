<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SaisonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Log::info("Seed des saisons de Régional 1");
        require 'app/scripts/import-saison-1.php';
        // Log::info("Seed des saisons de Régional 2");
        require 'app/scripts/import-saison-2.php';
        // Log::info("Seed des saisons de Ligue 1");
        require 'app/scripts/import-saison-3.php';
        // Log::info("Seed des saisons de Coupe de Mayotte");
        require 'app/scripts/import-saison-4.php';
    }
}

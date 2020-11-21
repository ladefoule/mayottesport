<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Disable all mass assignment restrictions
        Model::unguard();

        $this->call([
            SportSeeder::class,
            CompetitionSeeder::class,
            BaremeSeeder::class,
            SaisonSeeder::class,
            JourneeSeeder::class,
            VilleSeeder::class,
            TerrainSeeder::class,
            EquipeSeeder::class,
            RoleSeeder::class,
            RegionSeeder::class,
            // UserSeeder::class,
        ]);

        Log::info("Seed de la saison 1");
        require 'app/scripts/import-saison-1.php';
        Log::info("Seed de la saison 2");
        require 'app/scripts/import-saison-2.php';
        Log::info("Seed de la saison 3");
        require 'app/scripts/import-saison-3.php';
        Log::info("Seed du CRUD");
        require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}

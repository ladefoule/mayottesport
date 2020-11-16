<?php

use Illuminate\Database\Seeder;
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

        require 'app/scripts/import-saison-1.php';
        require 'app/scripts/import-saison-2.php';
        require 'app/scripts/import-saison-3.php';
        require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}

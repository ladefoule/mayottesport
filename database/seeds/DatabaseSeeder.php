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
            SportTableSeeder::class,
            VilleTableSeeder::class,
            TerrainTableSeeder::class,
            EquipeTableSeeder::class,
            CompetitionTableSeeder::class,
            BaremeTableSeeder::class,
            SaisonTableSeeder::class,
            // JourneeTableSeeder::class,
            RoleTableSeeder::class,
            RegionTableSeeder::class,
            // UserSeeder::class,
        ]);

        Log::info("Seed du CRUD");
        require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}

<?php
use App\Cache;
use Illuminate\Database\Seeder;
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
        // Suppression de tout le cache
        Cache::flush();

        // Disable all mass assignment restrictions
        Model::unguard();

        $this->call([
            SportTableSeeder::class,
            VilleTableSeeder::class,
            // TerrainTableSeeder::class,
            EquipeTableSeeder::class,
            CompetitionTableSeeder::class,
            BaremeTableSeeder::class,
            SaisonTableSeeder::class,
            // JourneeTableSeeder::class,
            RoleTableSeeder::class,
            RegionTableSeeder::class,
            UserTableSeeder::class,
        ]);

        // Log::info("Seed du CRUD");
        // require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}

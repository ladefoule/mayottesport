<?php

require 'UsersTableSeeder.php';

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
            UserSeeder::class,
        ]);

        // $idFootball = App\Sport::firstWhere('nom', 'like', 'football')->id;
        // $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball');
        // $idHandball = Sport::firstWhere('nom', 'like', 'handball');
        // $idBasketball = Sport::firstWhere('nom', 'like', 'basketball');
        // $idRugby = Sport::firstWhere('nom', 'like', 'rugby');

        // On insère les 12 équipes qui participent à la saison
        // $equipesId = ['29','27','26','97','2','1','4','138','13','162','96','45'];
        // foreach ($equipesId as $equipeId) {
        //     App\SaisonEquipe::create([
        //         'saison_id' => 1,
        //         'equipe_id' => $equipeId
        //     ]);
        // }

        require 'app/scripts/import-calendrier.php';
        require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}

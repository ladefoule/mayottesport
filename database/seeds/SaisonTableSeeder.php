<?php

use Illuminate\Database\Seeder;

class SaisonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère une saison de Régional 1 (football)
        App\Saison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y')+1,
            'nb_journees' => 22,
            'bareme_id' => 1,
            'competition_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // On insère une saison de Régional 2 (football)
        App\Saison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y')+1,
            'nb_journees' => 22,
            'bareme_id' => 1,
            'competition_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // On insère une saison de Ligue 1 (football)
        App\Saison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y')+1,
            'nb_journees' => 10,
            'bareme_id' => 1,
            'competition_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // On insère une saison de Coupe de Mayotte (football)
        App\Saison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y'),
            'nb_journees' => 2,
            'competition_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

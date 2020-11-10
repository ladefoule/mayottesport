<?php

use Illuminate\Database\Seeder;

class SaisonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 1 saison du championnat Régional 1
        App\Saison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y')+1,
            'nb_journees' => 22,
            'bareme_id' => 1,
            'competition_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

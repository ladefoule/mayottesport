<?php

use Illuminate\Database\Seeder;

class BaremeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 1 barème de football
        App\Bareme::create([
            'nom' => 'Football - Classique',
            'sport_id' => 1,
            'victoire' => 3,
            'nul' => 1,
            'defaite' => 0,
        ]);

        // On insère 1 barème de Volley
        App\Bareme::create([
            'nom' => 'Volley - Classique',
            'sport_id' => 2,
        ]);

        // On insère 1 barème de Handball
        App\Bareme::create([
            'nom' => 'Handball - Classique',
            'sport_id' => 3,
            'victoire' => 3,
            'nul' => 2,
            'defaite' => 1,
        ]);

        // On insère 1 barème de Basketball
        App\Bareme::create([
            'nom' => 'Basketball - Classique',
            'sport_id' => 4,
            'victoire' => 2,
            'defaite' => 0,
        ]);

        // On insère 1 barème de Rugby
        App\Bareme::create([
            'nom' => 'Rugby - Classique',
            'sport_id' => 5,
            'victoire' => 3,
            'nul' => 2,
            'defaite' => 1,
        ]);
    }
}

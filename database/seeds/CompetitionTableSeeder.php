<?php

use Illuminate\Database\Seeder;

class CompetitionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 2 championnats
        $competitions = ['Régional 1', 'Régional 2'];
        foreach ($competitions as $competition) {
            App\Competition::create([
                'nom' => $competition,
                'type' => 1, // type championnat
                'sport_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère 2 coupes
        $competitions = ['Coupe de Mayotte', 'Coupe de France'];
        foreach ($competitions as $competition) {
            App\Competition::create([
                'nom' => $competition,
                'type' => 2, // type coupe
                'sport_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}

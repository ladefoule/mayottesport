<?php

use Illuminate\Support\Str;
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
        // On insère 2 championnats pour chaque sport
        foreach (App\Sport::all() as $sport) {
            $competitions = ['Régional 1', 'Régional 2'];
            foreach ($competitions as $i => $competition) {
                App\Competition::create([
                    'nom' => $competition,
                    'slug' => Str::slug($competition),
                    'type' => 1, // type championnat
                    'sport_id' => $sport->id,
                    'home_position' => $i + 1,
                    'index_position' => $i + 1,
                    'created_at' => now(),
                    // 'updated_at' => now()
                ]);
            }
        }

        // On insère 2 coupes pour chaque sport
        foreach (App\Sport::all() as $sport) {
            $competitions = ['Coupe de Mayotte', 'Coupe de France'];
            foreach ($competitions as $i => $competition) {
                App\Competition::create([
                    'nom' => $competition,
                    'slug' => Str::slug($competition),
                    'type' => 2, // type coupe
                    'sport_id' => $sport->id,
                    'home_position' => $i + 2,
                    'index_position' => $i + 2,
                    'created_at' => now(),
                    // 'updated_at' => now()
                ]);
            }
        }
    }
}

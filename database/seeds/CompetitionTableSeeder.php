<?php

use App\Sport;
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
        // On insère les championnats de football
        $footballId = Sport::whereSlug('football')->firstOrFail()->id;
        $competitions = [
            ['nom' => 'Régional 1', 'type' => 1],
            ['nom' => 'Régional 2', 'type' => 1],
            ['nom' => 'Coupe de France', 'type' => 2],
            ['nom' => 'Coupe de Mayotte', 'type' => 2],
        ];
        foreach ($competitions as $i => $competition) {
            App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $footballId,
                'home_position' => $i + 1,
                'index_position' => $i + 1,
                'created_at' => now(),
            ]);
        }

        // On insère les championnats de handball
        $handballId = Sport::whereSlug('handball')->firstOrFail()->id;
        $competitions = [
            ['nom' => 'Prénationale masculins', 'type' => 1],
            ['nom' => 'PNM, poule A', 'type' => 1],
            ['nom' => 'Poule B PNM', 'type' => 1],
            ['nom' => 'Prénationale féminins', 'type' => 1],
            ['nom' => 'Excellence masculins', 'type' => 1],
            ['nom' => 'Excellence féminins', 'type' => 1],
        ];
        foreach ($competitions as $i => $competition) {
            App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $handballId,
                'home_position' => $i + 1,
                'index_position' => $i + 1,
                'created_at' => now(),
            ]);
        }
    }
}

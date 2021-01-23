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
        $football = Sport::whereSlug('football')->firstOrFail();
        $footballId = $football->id;
        $competitions = [
            ['nom' => 'Régional 1', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'Régional 2', 'type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
            ['nom' => 'Régional 3 (poule nord)', 'type' => 1, 'navbar_position' => 5],
            ['nom' => 'Régional 3 (poule sud)', 'type' => 1, 'navbar_position' => 6],
            ['nom' => 'Régional 4 (poule A)', 'type' => 1, 'navbar_position' => 7],
            ['nom' => 'Régional 4 (poule B)', 'type' => 1, 'navbar_position' => 8],
            ['nom' => 'Régional 4 (poule C)', 'type' => 1, 'navbar_position' => 9],
            ['nom' => 'Régional 4 (poule D)', 'type' => 1, 'navbar_position' => 10],
            ['nom' => 'Coupe de France', 'type' => 2, 'home_position' => 3, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'Coupe de Mayotte', 'type' => 2, 'home_position' => 4, 'index_position' => 4, 'navbar_position' => 4],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $footballId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $football->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }

        // On insère les championnats de handball
        $handball = Sport::whereSlug('handball')->firstOrFail();
        $handballId = $handball->id;
        $competitions = [
            ['nom' => 'Prénationale masculins', 'type' => 1],
            ['nom' => 'PNM, poule A', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'PNM, poule B', 'type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
            ['nom' => 'Prénationale féminins', 'type' => 1],
            ['nom' => 'PNF, poule A', 'type' => 1, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'PNF, poule B', 'type' => 1, 'index_position' => 4, 'navbar_position' => 4],
            ['nom' => 'Excellence masculins', 'type' => 1],
            ['nom' => 'EXM, poule A', 'type' => 1, 'index_position' => 5, 'navbar_position' => 5],
            ['nom' => 'EXM, poule B', 'type' => 1, 'index_position' => 6, 'navbar_position' => 6],
            ['nom' => 'Excellence féminins', 'type' => 1],
            ['nom' => 'EXF, poule A', 'type' => 1],
            ['nom' => 'EXF, poule B', 'type' => 1],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $handballId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $handball->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }

        // On insère les championnats de basketball
        $basketballId = Sport::whereSlug('basketball')->firstOrFail()->id;
        $competitions = [
            ['nom' => 'Prénationale', 'type' => 1],
            ['nom' => 'Régionale 2', 'type' => 1],
            ['nom' => 'Prénationale féminine', 'type' => 1],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $basketballId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $handball->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }
    }
}

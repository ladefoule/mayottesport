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
            ['nom' => 'Régional 1','type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'Régional 2','type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
            ['nom' => 'Régional 3 nord', 'nom_complet' => 'Régional 3 (poule nord)','type' => 1, 'navbar_position' => 5],
            ['nom' => 'Régional 3 sud', 'nom_complet' => 'Régional 3 (poule sud)','type' => 1, 'navbar_position' => 6],
            ['nom' => 'Régional 4 A', 'nom_complet' => 'Régional 4 (poule A)','type' => 1, 'navbar_position' => 7],
            ['nom' => 'Régional 4 B', 'nom_complet' => 'Régional 4 (poule B)','type' => 1, 'navbar_position' => 8],
            ['nom' => 'Régional 4 C', 'nom_complet' => 'Régional 4 (poule C)','type' => 1, 'navbar_position' => 9],
            ['nom' => 'Régional 4 D', 'nom_complet' => 'Régional 4 (poule D)','type' => 1, 'navbar_position' => 10],
            ['nom' => 'Coupe de France', 'type' => 2, 'home_position' => 3, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'Coupe de Mayotte', 'type' => 2, 'home_position' => 4, 'index_position' => 4, 'navbar_position' => 4],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'nom_complet' => $competition['nom_complet'] ?? $competition['nom'],
                'slug_complet' => isset($competition['nom_complet']) ? Str::slug($competition['nom_complet']) : Str::slug($competition['nom']),
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
            ['nom' => 'Prénationale M', 'nom_complet' => 'Prénationale masculins', 'type' => 1],
            ['nom' => 'PNM poule A', 'nom_complet' => 'Prénationale masculins poule A', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'PNM poule B', 'nom_complet' => 'Prénationale masculins poule B', 'type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
            ['nom' => 'Prénationale F', 'nom_complet' => 'Prénationale féminines', 'type' => 1],
            ['nom' => 'PNF poule A', 'nom_complet' => 'Prénationale féminines poule A', 'type' => 1, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'PNF poule B', 'nom_complet' => 'Prénationale féminines poule B', 'type' => 1, 'index_position' => 4, 'navbar_position' => 4],
            ['nom' => 'Excellence M', 'nom_complet' => 'Excellence masculins', 'type' => 1],
            ['nom' => 'EXM poule A', 'nom_complet' => 'Excellence masculins poule A', 'type' => 1, 'index_position' => 5, 'navbar_position' => 5],
            ['nom' => 'EXM poule B', 'nom_complet' => 'Excellence masculins poule B', 'type' => 1, 'index_position' => 6, 'navbar_position' => 6],
            ['nom' => 'Excellence F', 'nom_complet' => 'Excellence féminines', 'type' => 1],
            ['nom' => 'EXF poule A', 'nom_complet' => 'Excellence féminines poule A', 'type' => 1],
            ['nom' => 'EXF poule B', 'nom_complet' => 'Excellence féminines poule B', 'type' => 1],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'nom_complet' => $competition['nom_complet'] ?? $competition['nom'],
                'slug_complet' => isset($competition['nom_complet']) ? Str::slug($competition['nom_complet']) : Str::slug($competition['nom']),
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
        $basketball = Sport::whereSlug('basketball')->firstOrFail();
        $basketballId = $basketball->id;
        $competitions = [
            ['nom' => 'Prénationale M', 'nom_complet' => 'Prénationale masculine', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'Régionale 2 M', 'nom_complet' => 'Régionale 2 masculine', 'type' => 1, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'Régionale 3 M', 'nom_complet' => 'Régionale 3 masculine', 'type' => 1, 'index_position' => 4, 'navbar_position' => 4],
            ['nom' => 'Prénationale F', 'nom_complet' => 'Prénationale féminine', 'type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'nom_complet' => $competition['nom_complet'] ?? $competition['nom'],
                'slug_complet' => isset($competition['nom_complet']) ? Str::slug($competition['nom_complet']) : Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $basketballId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $basketball->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }

        // On insère les championnats de volleyball
        $volleyball = Sport::whereSlug('volleyball')->firstOrFail();
        $volleyballId = $volleyball->id;
        $competitions = [
            ['nom' => 'Régionale 1 M', 'nom_complet' => 'Régionale 1 masculine', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
            ['nom' => 'Régionale 2 M', 'nom_complet' => 'Régionale 2 masculine', 'type' => 1, 'index_position' => 3, 'navbar_position' => 3],
            ['nom' => 'Régionale 1 F', 'nom_complet' => 'Régionale 1 féminine', 'type' => 1, 'home_position' => 2, 'index_position' => 2, 'navbar_position' => 2],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'nom_complet' => $competition['nom_complet'] ?? $competition['nom'],
                'slug_complet' => isset($competition['nom_complet']) ? Str::slug($competition['nom_complet']) : Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $volleyballId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $volleyball->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }

        // On insère les championnats de rugby
        $rugby = Sport::whereSlug('rugby')->firstOrFail();
        $rugbyId = $rugby->id;
        $competitions = [
            ['nom' => 'Honneur', 'nom_complet' => 'Honneur territorial', 'type' => 1, 'home_position' => 1, 'index_position' => 1, 'navbar_position' => 1],
        ];
        foreach ($competitions as $competition) {
            $competitionModel = App\Competition::create([
                'nom' => $competition['nom'],
                'slug' => Str::slug($competition['nom']),
                'nom_complet' => $competition['nom_complet'] ?? $competition['nom'],
                'slug_complet' => isset($competition['nom_complet']) ? Str::slug($competition['nom_complet']) : Str::slug($competition['nom']),
                'type' => $competition['type'],
                'sport_id' => $rugbyId,
                'home_position' => $competition['home_position'] ?? NULL,
                'index_position' => $competition['index_position'] ?? NULL,
                'created_at' => now(),
            ]);

            if(isset($competition['navbar_position']))
                $rugby->competitionsNavbar()->attach($competitionModel->id, ['position' => $competition['navbar_position']]);
        }
    }
}

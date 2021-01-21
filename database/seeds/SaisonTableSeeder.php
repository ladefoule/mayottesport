<?php

use App\Sport;
use App\Competition;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class SaisonTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Log::info("Seed des saisons de Régional 1");
        require 'app/scripts/import-saison-1.php';
        // Log::info("Seed des saisons de Régional 2");
        require 'app/scripts/import-saison-2.php';
        // Log::info("Seed des saisons de Coupe de Mayotte");
        require 'app/scripts/import-saison-4.php';

        // On insère les championnats de football
        $footballId = Sport::whereSlug('football')->firstOrFail()->id;

        $regional1Id = Competition::whereSlug('regional-1')->whereSportId($footballId)->firstOrFail()->id;
        $regional2Id = Competition::whereSlug('regional-2')->whereSportId($footballId)->firstOrFail()->id;
        $coupeMayotteId = Competition::whereSlug('coupe-de-mayotte')->whereSportId($footballId)->firstOrFail()->id;
        $coupeDeFranceId = Competition::whereSlug('coupe-de-france')->whereSportId($footballId)->firstOrFail()->id;
        $phnordId = Competition::whereSlug('regional-3-poule-nord')->whereSportId($footballId)->firstOrFail()->id;
        $phsudId = Competition::whereSlug('regional-3-poule-sud')->whereSportId($footballId)->firstOrFail()->id;

        for($annee = 1980; $annee <= date('Y') - 1; $annee++) {
            App\Saison::create([
                'competition_id' => $coupeMayotteId,
                'annee_debut' => $annee,
                'annee_fin' => $annee,
                'finie' => 1,
            ]);

            if($annee >= 1990){
                App\Saison::create([
                    'competition_id' => $coupeDeFranceId,
                    'annee_debut' => $annee,
                    'annee_fin' => $annee,
                    'finie' => 1,
                ]);

                App\Saison::create([
                    'competition_id' => $regional1Id,
                    'annee_debut' => $annee,
                    'annee_fin' => $annee,
                    'finie' => 1,
                ]);

                App\Saison::create([
                    'competition_id' => $regional2Id,
                    'annee_debut' => $annee,
                    'annee_fin' => $annee,
                    'finie' => 1,
                ]);
            }

            if($annee >= 2000){
                App\Saison::create([
                    'competition_id' => $phnordId,
                    'annee_debut' => $annee,
                    'annee_fin' => $annee,
                    'finie' => 1,
                ]);

                App\Saison::create([
                    'competition_id' => $phsudId,
                    'annee_debut' => $annee,
                    'annee_fin' => $annee,
                    'finie' => 1,
                ]);
            }
        }
    }
}

<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use App\Saison;
use Illuminate\Support\Facades\Log;
use App\Competition as CompetitionModel;
use Illuminate\Support\Facades\Validator;

class Competition
{
    /**
     * On vérifie si le nom de compétition saisi est bien dans la base.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Competition -------- ");
        // if (Validator::make(['competition' => $request->competition], ['competition' => 'alpha_dash|min:3'])->fails())
        //     abort(404);

        $sport = $request->sport;
        $competition = CompetitionModel::whereSportId($sport->id)->whereSlug($request->competition)->firstOrFail();
        $request->competition = $competition;

        $competitionSlug = $competition->slug;
        $sportSlug = $sport->slug;

        // $saison = Saison::where('competition_id', $competition->id)/* ->where('finie', '!=', 1) */->orderBy('finie')->orderBy('annee_debut')->first();
        // Recherche de la dernière saison de la compétition
        $saison = $competition->saisons()->orderBy('annee_debut', 'desc')->first();

        // Les infos requises pour toutes les pages du middleware
        $request->saison = $saison; // la collection
        $request->hrefIndex = route('competition.index', ['sport' => $sportSlug, 'competition' => $competitionSlug]);
        $request->hrefPalmares = route('competition.palmares', ['sport' => $sportSlug, 'competition' => $competitionSlug]);

        if($saison){
            $request->hrefCalendrier = route('competition.calendrier-resultats', ['sport' => $sportSlug, 'competition' => $competitionSlug]);

            if($competition->type == 1) // Type Championnat
                $request->hrefClassement = route('competition.classement', ['sport' => $sportSlug, 'competition' => $competitionSlug]);

            if($saison){
                $journeesPassees = $saison->journees()->where('date', '<', date('Y-m-d'))->orderBy('date', 'desc')->limit(2)->get();
                foreach ($journeesPassees as $journee)
                    $resultats[] = journee($journee->id)->render;

                $journeesSuivantes = $saison->journees()->where('date', '>=', date('Y-m-d'))->orderBy('date')->limit(2)->get();
                foreach ($journeesSuivantes as $journee)
                    $prochains[] = journee($journee->id)->render;
            }
        }

        $request->resultats = $resultats ?? [];
        $request->prochains = $prochains ?? [];
        return $next($request);
    }
}

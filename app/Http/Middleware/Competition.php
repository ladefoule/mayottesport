<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
        if (Validator::make(['competition' => $request->competition], ['competition' => 'alpha_dash|min:3'])->fails())
            abort(404);

        $sport = $request->sport;
        $competitions = index('competitions')->where('sport_id', $sport->id);

        $find = false;
        foreach($competitions as $compet)
            if(Str::slug($compet->nom) == ($request->competition)){
                $competition = $compet;
                $find = true;
                break;
            }

        if(! $find)
            abort(404);

        $competitionKebab = $request->competition;
        $sportKebab = Str::slug($request->sport->nom);

        $saison = index('saisons')->where('competition_id', $competition->id)->where('finie', '!=', 1)->first();

        // Les infos requises pour toutes les pages du middleware
        $request->competition = $competition; // la collection
        $request->saison = $saison; // la collection
        $request->hrefIndex = route('competition.index', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        $request->hrefPalmares = route('competition.palmares', ['sport' => $sportKebab, 'competition' => $competitionKebab]);

        if($saison){
            $request->hrefCalendrier = route('competition.calendrier-resultats', ['sport' => $sportKebab, 'competition' => $competitionKebab]);

            if($competition->type == 1) // Type Championnat
                $request->hrefClassement = route('competition.classement', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        }
        return $next($request);
    }
}

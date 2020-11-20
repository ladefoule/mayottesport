<?php

namespace App\Http\Middleware;

use Closure;
use App\Saison;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Competition
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(" -------- Middleware Competition -------- ");
        $rules = [
            'competition' => 'alpha_dash|min:3',
        ];

        $validator = Validator::make([
            'competition' => $request->competition,
        ], $rules);

        if ($validator->fails())
            abort(404);

        $sport = $request->sport;
        $competitions = $sport->competitions;

        $find = false;
        foreach($competitions as $compet)
            if(strToUrl($compet->nom) == ($request->competition)){
                $competition = $compet;
                $find = true;
                break;
            }

        if(! $find)
            abort(404);

        $competitionKebab = $request->competition;
        $sportKebab = strToUrl($request->sport->nom);

        $saison = Saison::where('competition_id', $competition->id)->where('finie', '!=', 1)
                            ->orWhere(function($query) use($competition) {
                                $query->whereNull('finie')
                                    ->where('competition_id', $competition->id);
                            })->first();

        // Les infos requises pour toutes les pages du middleware
        $request->competition = $competition; // On remplace la chaine de caractère par l'objet
        $request->saison = $saison;
        $request->hrefIndex = route('competition.index', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        $request->hrefPalmares = route('competition.champions', ['sport' => $sportKebab, 'competition' => $competitionKebab]);

        if($saison){
            $request->hrefCalendrier = route('competition.day', ['sport' => $sportKebab, 'competition' => $competitionKebab]);

            if($competition->type == 1) // Type Championnat
                $request->hrefClassement = route('competition.ranking', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        }

        return $next($request);
    }
}

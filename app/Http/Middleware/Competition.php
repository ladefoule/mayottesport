<?php

namespace App\Http\Middleware;

use Closure;
use App\Sport;
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
        $rules = [
            'sport' => 'alpha_dash|min:3',
            'competition' => 'alpha_dash|min:3',
            'journee' => 'nullable|integer|min:0'
        ];

        $validator = Validator::make([
            'sport' => $request->sport,
            'competition' => $request->competition,
            'journee' => $request->journee
        ], $rules);

        if ($validator->fails()) {
            abort(404);
        }

        $sport = Sport::where('nom', 'like', $request->sport)->firstOrFail();
        $competitions = $sport->competitions;
        $find = false;
        foreach($competitions as $compet)
            if(strToUrl($compet->nom) == ($request->competition)){
                $competition = $compet;
                $find = true;
            break;
            }

        if(!$find)
            abort(404);

        $competitionKebab = $request->competition;
        $sportKebab = $request->sport;

        $saison = Saison::where('competition_id', $competition->id)->where('finie', '!=',1)->orWhereNull('finie')->firstOrFail();
        $derniereJournee = $saison->derniereJournee()->numero;

        $sports = Sport::all();

        // Les infos requises pour toutes les pages du middleware
        $request->competition = $competition;
        $request->saison = $saison;
        $request->sport = $sport;
        $request->sports = $sports;
        $request->hrefIndex = route('competition.index', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        $request->hrefClassement = route('competition.classement', ['sport' => $sportKebab, 'competition' => $competitionKebab]);
        $request->hrefCalendrier = route('competition.journee', ['sport' => $sportKebab, 'competition' => $competitionKebab, 'journee' => $derniereJournee]);
        $request->hrefPalmares = route('competition.palmares', ['sport' => $sportKebab, 'competition' => $competitionKebab]);

        return $next($request);
    }
}

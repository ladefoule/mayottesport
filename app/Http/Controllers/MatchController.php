<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Cache;
use App\Match;
use App\Modif;
use Illuminate\Http\Request;
use App\Jobs\ProcessCacheReload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller Match - Ip : " . request()->ip());
        $this->middleware(['sport', 'competition', 'match-uniqid', 'modification-match']);
        $this->middleware(['saison'])->only('match');
    }

    /**
     * Accès à la view du match
     *
     * @param  Request $request
     * @param  string $sport
     * @param  string $competition
     * @param  string $annee
     * @param  string $equipeDom
     * @param  string $equipeExt
     * @return \Illuminate\View\View|void
     */
    public function match(Request $request, $sport, $competition, $annee, $equipeDom, $equipeExt)
    {
        Log::info(" -------- Controller Match : match -------- ");
        $match = $request->match;
        $sport = $request->sport;
        
        $infos = infos('matches', $match->id);
        $journee = infos('journees', $match->journee_id);
        return view('competition.match', [
            'match' => $infos,
            'sport' => $sport,
            'journee' => $journee,
            'accesModifHoraire' => $request->accesModifHoraire,
            'accesModifResultat' => $request->accesModifResultat,
        ]);
    }

    /**
     * Accès à la page de modification du résultat du match
     *
     * @param  string $matchId
     * @return \Illuminate\View\View|void
     */
    public function resultat(Request $request)
    {
        Log::info(" -------- Controller Match : resultat -------- ");
        $match = $request->match;
        $sport = $request->sport;
        $accesBloque = $match->acces_bloque;
        if ($accesBloque){
            Log::info("Match bloqué. Id match : " . $match->id);
            abort(403);
        }

        $infos = infos('matches', $match->id);
        return view('competition.resultat', [
            'match' => $infos,
            'sport' => $sport,
        ]);
    }

    /**
     * Traitement de la modification du résultat du match
     *
     * @param  Request $request
     * @param  string $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function resultatPost(Request $request)
    {
        Log::info(" -------- Controller Match : resultatPost -------- ");
        $sport = $request->sport;
        Validator::make($request->all(), config('listes.rules-score.' . $sport->slug))->validate();

        $match = Match::findOrFail($request->match->id);

        $score_eq_dom = $request['score_eq_dom'];
        $score_eq_ext = $request['score_eq_ext'];

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $match->score_eq_dom || $score_eq_ext != $match->score_eq_ext) {
            $userId = Auth::id();

            $match->update([
                'score_eq_dom' => $score_eq_dom,
                'score_eq_ext' => $score_eq_ext,
                'nb_modifs' => $match->nb_modifs + 1,
                'user_id' => $userId
            ]);

            Modif::create([
                'user_id' => $userId,
                'match_id' => $match->id,
                'type' => 1,
                'note' => $score_eq_dom . '-' . $score_eq_ext,
            ]);

            Cache::forget('index-modifs');
            Cache::forget('indexcrud-modifs');
            forgetCaches('matches', $match);
            ProcessCacheReload::dispatch('matches', $match->id);
        }

        $urlMatch = $match->infos()->href_match;
        return redirect($urlMatch);
    }

    /**
     * Accès à la page de modification de l'horaire du match
     *
     * @param  mixed $matchId
     * @return \Illuminate\View\View|void
     */
    public function horaire(Request $request)
    {
        Log::info(" -------- Controller Match : horaire -------- ");
        $match = $request->match;
        $sport = $request->sport;

        $infos = $match->infos();
        return view('competition.horaire', [
            'match' => $infos,
            'sport' => $sport,
        ]);
    }

    /**
     * Traitement de la modification de l'horaire du match
     *
     * @param  Request $request
     * @param  string $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function horairePost(Request $request)
    {
        Log::info(" -------- Controller Match : horairePost -------- ");
        $match = $request->match;
        Validator::make($request->all(), [
            'date' => 'required|date',
            'heure' => 'required|size:5'
        ])->validate();

        $date = $request['date'];
        $heure = $request['heure'];

        if ($date != $match->date || $heure != $match->heure) {
            $match->update([
                'date' => $date,
                'heure' => $heure
            ]);

            $date = new Carbon($date);
            Modif::create([
                'user_id' => Auth::id(),
                'match_id' => $match->id,
                'type' => 2,
                'note' => $date->format('d/m/Y') . ' à ' . $heure,
            ]);

            Cache::forget('index-modifs');
            Cache::forget('indexcrud-modifs');
            forgetCaches('matches', $match);
            ProcessCacheReload::dispatch('matches', $match->id);
        }

        $urlMatch = $match->infos()->href_match;
        return redirect($urlMatch);
    }
}

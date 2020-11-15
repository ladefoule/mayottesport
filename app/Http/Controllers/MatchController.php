<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Equipe;
use App\Terrain;
use App\Match;
use App\Modif;
use App\Competition;
use App\Saison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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
        Log::info(" -------- CompetitionController : __construct -------- ");
        $this->middleware(['sport', 'competition', 'match-id'])->except('forgetCaches');
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
        Log::info(" -------- MatchController : match -------- ");
        $match = $request->match;
        $saison = $match->journee->saison;

        // On vérifie l'année
        if($saison->annee() != $annee){
            abort(404);
        }

        $infos = $match->infos();
        return view('football.match', [
            'match' => $infos
        ]);
    }

    /**
     * Accès à la page de modification du résultat du match
     *
     * @param  string $matchId
     * @return \Illuminate\View\View|void
     */
    public function result(Request $request)
    {
        Log::info(" -------- MatchController : result -------- ");
        $match = $request->match;
        $accesBloque = $match->acces_bloque;
        if ($accesBloque){
            Log::info("Match bloqué. Id match : " . $match->id);
            abort(403);
        }

        $infos = $match->infos();
        return view('football.result', [
            'match' => $infos
        ]);
    }

    /**
     * Traitement de la modification du résultat du match
     *
     * @param  Request $request
     * @param  string $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function resultStore(Request $request)
    {
        Log::info(" -------- MatchController : resultStore -------- ");
        Validator::make($request->all(), [
            'score_eq_dom' => 'required|integer|min:0|max:30',
            'score_eq_ext' => 'required|integer|min:0|max:30',
            'note' => 'nullable|string|max:200'
        ])->validate();

        $match = $request->match;
        $score_eq_dom = $request['score_eq_dom'];
        $score_eq_ext = $request['score_eq_ext'];
        $note = $request['note'];

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $match->score_eq_dom || $score_eq_ext != $match->score_eq_ext) {
            $match->update([
                'score_eq_dom' => $score_eq_dom,
                'score_eq_ext' => $score_eq_ext,
                'nb_modifs' => $match->nb_modifs + 1
            ]);

            Modif::create([
                'user_id' => Auth::id(),
                'match_id' => $match->id,
                'note' => $note,
            ]);

            $this::forgetCaches($match);
        }

        $urlMatch = $match->infos()['lienMatch'];
        return redirect($urlMatch);
    }

    /**
     * Accès à la page de modification de l'horaire du match
     *
     * @param  mixed $matchId
     * @return \Illuminate\View\View|void
     */
    public function schedule(Request $request)
    {
        Log::info(" -------- MatchController : schedule -------- ");
        $match = $request->match;

        $infos = $match->infos();
        return view('football.schedule', [
            'match' => $infos
        ]);
    }

    /**
     * Traitement de la modification de l'horaire du match
     *
     * @param  Request $request
     * @param  string $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function scheduleStore(Request $request)
    {
        Log::info(" -------- MatchController : scheduleStore -------- ");
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

            Modif::create([
                'user_id' => Auth::id(),
                'match_id' => $match->id,
                'note' => "Modification de l'horaire du match.",
            ]);

            $this::forgetCaches($match);
        }

        $urlMatch = $match->infos()['lienMatch'];
        return redirect($urlMatch);
    }

    /**
     * Suppression des caches liés au match
     *
     * @param  Match $match
     * @return void
     */
    private static function forgetCaches(Match $match)
    {
        Log::info(" -------- MatchController : forgetCaches -------- ");
        Cache::forget('match-' . $match->uniqid); // Les infos du match
        Cache::forget('journee-' . $match->journee->id); // Les infos de la journée
        Cache::forget('classement-' . $match->journee->saison->id); // Le classement de la saison
    }
}

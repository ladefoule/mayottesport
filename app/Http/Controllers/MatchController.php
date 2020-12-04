<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Match;
use App\Modif;
use App\Sport;
use App\Equipe;
use App\Saison;
use App\Terrain;
use App\CrudTable;
use App\Competition;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrudTable;
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
        Log::info(" --------  Match : __construct -------- ");
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
        Log::info(" -------- Controller Match : match -------- ");
        $match = $request->match;
        $match = Match::find($match->id);
        $journee = index('journees')[$match->journee_id];
        $saison = index('saisons')[$journee->saison_id];

        // On vérifie l'année
        if(annee($saison->annee_debut, $saison->annee_fin) != $annee)
            abort(404);
            // Cache::forget('match-5fc1506c17110');

        $infos = match($match->uniqid);
        // dd($infos);
        return view('competition.match', [
            'match' => $infos
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
        $accesBloque = $match->acces_bloque;
        if ($accesBloque){
            Log::info("Match bloqué. Id match : " . $match->id);
            abort(403);
        }

        $infos = match($match->uniqid);
        return view('competition.resultat', [
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
    public function resultatPost(Request $request)
    {
        Log::info(" -------- Controller Match : resultatPost -------- ");
        Validator::make($request->all(), [
            'score_eq_dom' => 'required|integer|min:0|max:30',
            'score_eq_ext' => 'required|integer|min:0|max:30',
            'note' => 'nullable|max:200'
        ])->validate();

        $match = Match::findOrFail($request->match->id);

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

        $urlMatch = $match->infos()['href_match'];
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

        $infos = $match->infos();
        return view('competition.horaire', [
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

            Modif::create([
                'user_id' => Auth::id(),
                'match_id' => $match->id,
                'note' => "Modification de l'horaire du match.",
            ]);

            $this::forgetCaches($match);
        }

        $urlMatch = $match->infos()['href_match'];
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
        Log::info(" -------- Controller Match : forgetCaches -------- ");
        $crudTable = CrudTable::firstWhere('nom', 'matches');
        $journee = $match->journee;
        $saison = $journee->saison;
        Cache::forget('match-' . $match->uniqid);
        Cache::forget('journee-' . $journee->id);
        Cache::forget('saison-' . $saison->id);
        ProcessCrudTable::dispatch($crudTable, $match);
    }
}

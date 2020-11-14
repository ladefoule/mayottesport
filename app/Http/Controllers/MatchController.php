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
        $this->middleware(['sport', 'competition', 'match-id'])->only(['match', 'resultat', 'horaire', 'resultatPost', 'horairePost']);

        // $this->middleware('match-id')->only(['']);

        // $this->middleware('subscribed')->except('store');
    }

    /**
     * Accès Back-Office
     * Affichage de la liste des matches de Championnat de Football
     *
     * @return \Illuminate\View\View
     */
    public function lister()
    {
        Log::info(" -------- MatchController : lister -------- ");
        $sports = Sport::get();
        // $competitions = Competition::where('sport_id', $idFootball)->where('type', 1)->orderBy('nom')->get();
        $h1 = $title = ' Parcourir les matches';
        return view(
            'admin.matches.lister',
            ['title' => $title, 'h1' => $h1, 'sports' => $sports]
        );
    }

    /**
     * Accès Back-Office
     * Formulaire d'ajout d'un match de football de Championnat
     *
     * @return \Illuminate\View\View|void
     */
    public function ajouter()
    {
        Log::info(" -------- MatchController : ajouter -------- ");
        $match = new Match();
        $competition = new Competition();
        $saison = new Saison();
        $saisons = [];
        $journees = [];
        $title = 'Championnat/Football : Ajout d\'un match';

        $idFootball = Sport::firstWhere('nom', 'like', 'football')->id ?? 0;
        $competitions = Sport::firstWhere('id', $idFootball)->championnats;
        $equipes = Equipe::orderBy('nom')->get();
        $terrains = Terrain::orderBy('nom')->get();

        return view('admin.matches.foot.ajouter', [
            'Match' => $match, 'title' => $title,
            'h1' => $title, 'championnats' => $competitions, 'championnatId' => $competition->id,
            'saisonId' => $saison->id, 'saisons' => $saisons, 'journees' => $journees, 'equipes' => $equipes, 'terrains' => $terrains
        ]);
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

        // $validator = Validator::make([
        //     'equipeDom' => $equipeDom,
        //     'equipeExt' => $equipeExt
        // ], [
        //     'equipeDom' => 'regex:[a-z0-9-]\+|min:3',
        //     'equipeExt' => 'regex:[a-z0-9-]\+|min:3'
        // ]);

        // if ($validator->fails())
        //     abort(404);

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
    public function resultat(Request $request)
    {
        Log::info(" -------- MatchController : resultat -------- ");
        $match = $request->match;
        $accesBloque = $match->acces_bloque;
        if ($accesBloque){
            Log::info("Match bloqué. Id match : " . $match->id);
            abort(403);
        }

        $infos = $match->infos();
        return view('football.resultat', [
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
        Log::info(" -------- MatchController : resultatPost -------- ");
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
    public function horaire(Request $request)
    {
        Log::info(" -------- MatchController : horaire -------- ");
        $match = $request->match;

        $infos = $match->infos();
        return view('football.horaire', [
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
        Log::info(" -------- MatchController : horairePost -------- ");
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
     * Traitement de la suppression de plusieurs matches de football
     * Les ids à supprimer doivent être inclus dans un tableau qui portera le name 'delete'
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        Log::info(" -------- MatchController : delete -------- ");
        $validator = Validator::make($request->all(), [
            'delete' => 'required|array',
            'delete.*' => "integer|exists:champ_matches,id"
        ]);

        if ($validator->fails())
            abort(404);

        $request = $validator->validate();
        foreach ($request['delete'] as $id) {
            $match = Match::whereUniqid($id)->firstOrFail();
            $match->delete();

            $this::forgetCaches($match);
        }
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

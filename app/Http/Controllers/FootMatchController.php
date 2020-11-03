<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Equipe;
use App\Terrain;
use App\Match;
use App\Modif;
use App\Competition;
use App\Saison;
use App\MatchInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class FootMatchController extends Controller
{
    /**
     * Accès Back-Office
     * Affichage de la liste des matches de Championnat de Football
     *
     * @return \Illuminate\View\View
     */
    public function lister()
    {
        Log::info(" -------- FootMatchController : lister -------- ");
        $idFootball = Sport::firstWhere('nom', 'like', 'football')->id ?? 0;
        $championnats = Competition::where('sport_id', $idFootball)->where('type', 'Championnat')->orderBy('nom')->get();
        $h1 = $title = 'Football - Les matches de championnat';
        return view(
            'admin.matches.foot.lister',
            ['title' => $title, 'h1' => $h1, 'championnats' => $championnats]
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
        Log::info(" -------- FootMatchController : ajouter -------- ");
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
     * Accès Back-Office
     * Formulaire de modification (score+horaire) d'un match de football de Championnat
     *
     * @param int $matchId
     * @return \Illuminate\View\View|void
     */
    public function editer(int $matchId)
    {
        Log::info(" -------- FootMatchController : editer -------- ");
        $match = Match::whereUniqid($matchId)->firstOrFail();
        $infosMatch = $match->MatchInfos;
        $liens = infosMatch();
        foreach ($infosMatch as $matchInfo) {
            $info = $matchInfo->information; // On récupère ici un entier
            $attribut = $liens[$info]; // On récupère l'attribut qui correspond à cet entier
            $$attribut = $matchInfo->valeur;
        }

        // On insère les infos supplémentaires du match (forfaits/penalités/tab ... : tous les attributs présents dans le fichier JSON)
        foreach($liens as $cle => $lien)
            $match->$lien = $$lien ?? '';

        $title = "Modification de match";
        return view('admin.matches.foot.editer', [
            'Match' => $match, 'title' => $title
        ]);
    }

    /**
     * Accès Back-Office
     * Traitement de la modification (score+horaire) d'un match de football de Championnat en POST
     *
     * @param Request $request
     * @param int $matchId
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function editerPost(Request $request, int $matchId)
    {
        Log::info(" -------- FootMatchController : editerPost -------- ");
        $match = Match::whereUniqid($matchId)->firstOrFail();
        $request['penalite_eq_dom'] = $request->has('penalite_eq_dom');
        $request['penalite_eq_ext'] = $request->has('penalite_eq_ext');
        $request['forfait_eq_dom'] = $request->has('forfait_eq_dom');
        $request['forfait_eq_ext'] = $request->has('forfait_eq_ext');
        $rules = [
            'score_eq_dom' => 'nullable|integer|min:0|required_with:score_eq_ext',
            'score_eq_ext' => 'nullable|integer|min:0|required_with:score_eq_dom',
            'forfait_eq_dom' => 'boolean',
            'forfait_eq_ext' => 'boolean',
            'penalite_eq_dom' => 'boolean',
            'penalite_eq_ext' => 'boolean',
            'date' => 'nullable|date|date_format:Y-m-d',
            'heure' => 'nullable|string|size:5'
        ];

        $request = Validator::make($request->all(), $rules)->validate();
        $infos = $match->MatchInfos;
        foreach ($infos as $matchInfo)
            $matchInfo->delete();// On supprime tous les pénalités/forfaits éventuels qui existaient déjà

        $liens = infosMatch();
        foreach ($liens as $key => $attribut)
            if($request[$attribut]) // On insère les nouvelles informations
                MatchInfo::create(['information' => $key, 'valeur' => $request[$attribut], 'champ_match_id' => $match->id]);

        $score_eq_dom = $request['score_eq_dom'] ?? '';
        $score_eq_ext = $request['score_eq_ext'] ?? '';

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $match->score_eq_dom || $score_eq_ext != $match->score_eq_ext) {
            $nbModifs = $match->nb_modifs + 1;
            $request['nb_modifs'] = $nbModifs;
        }

        $match->update($request);
        $this::forgetCaches($match);
        return redirect()->route('matches.foot.editer', ['id' => $matchId]);
    }

    /**
     * Accès à la view du match
     *
     * @param  string $competition
     * @param  string $annee
     * @param  string $equipeDom
     * @param  string $equipeExt
     * @param  string $id
     * @return \Illuminate\View\View|void
     */
    public function match(string $competition, string $annee, string $equipeDom, string $equipeExt, string $id)
    {
        Log::info(" -------- FootMatchController : match -------- ");
        $match = Match::whereUniqid($id)->firstOrFail();
        $saison = $match->journee->saison;

        // On vérifie l'URL
        if(strToUrl($saison->competition->nom) != $competition || $saison->annee() != $annee){
            Log::info('Match introuvable - competition : ' . $competition . ', année : ' . $annee . ', id : ' . $id);
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
     * @param  mixed $matchId
     * @return \Illuminate\View\View|void
     */
    public function resultat($matchId)
    {
        Log::info(" -------- FootMatchController : resultat -------- ");
        $match = Match::whereUniqid($matchId)->firstOrFail();
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
     * @param  mixed $request
     * @param  mixed $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function resultatPost(Request $request, int $matchId)
    {
        Log::info(" -------- FootMatchController : resultatPost -------- ");
        $request = Validator::make($request->all(), [
            'score_eq_dom' => 'required|integer|min:0|max:30',
            'score_eq_ext' => 'required|integer|min:0|max:30',
            'note' => 'nullable|string|max:200'
        ])->validate();

        $match = Match::whereUniqid($matchId)->firstOrFail();
        $score_eq_dom = $request['score_eq_dom'];
        $score_eq_ext = $request['score_eq_ext'];
        $note = $request['note'];
        $idUser = Auth::user()->id;

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $match->score_eq_dom || $score_eq_ext != $match->score_eq_ext) {
            $match->update([
                'score_eq_dom' => $score_eq_dom,
                'score_eq_ext' => $score_eq_ext,
                'nb_modifs' => $match->nb_modifs + 1
            ]);

            $modif = new Modif([
                'user_id' => $idUser,
                'champ_match_id' => $match->id,
                'note' => $note,
            ]);
            $modif->saveOrFail();

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
    public function horaire($matchId)
    {
        Log::info(" -------- FootMatchController : horaire -------- ");
        $match = Match::whereUniqid($matchId)->firstOrFail();

        $infos = $match->infos();
        return view('football.horaire', [
            'match' => $infos
        ]);
    }

    /**
     * Traitement de la modification de l'horaire du match
     *
     * @param  mixed $request
     * @param  mixed $matchId
     * @return \Illuminate\Routing\Redirector|void
     */
    public function horairePost(Request $request, int $matchId)
    {
        Log::info(" -------- FootMatchController : horairePost -------- ");
        $match = Match::whereUniqid($matchId)->firstOrFail();
        $request = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'heure' => 'nullable|size:5'
        ])->validate();

        $date = $request['date'];
        $heure = $request['heure'];
        // S'il y a un changement au niveau du score
        if ($date != $match->date || $heure != $match->heure) {
            $match->update([
                'date' => $date,
                'heure' => $heure,
                'nb_modifs' => $match->nb_modifs + 1
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
    public function supprimer(Request $request)
    {
        Log::info(" -------- FootMatchController : supprimer -------- ");
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
        Log::info(" -------- FootMatchController : forgetCaches -------- ");
        Cache::forget('champ-match-' . $match->uniqid); // Les infos du match
        Cache::forget('journee-' . $match->journee->id); // Les infos de la journée
        Cache::forget('classement-' . $match->journee->saison->id); // Le classement de la saison
    }
}

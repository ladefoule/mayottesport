<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Equipe;
use App\Terrain;
use App\ChampMatch;
use App\ChampModif;
use App\Championnat;
use App\ChampSaison;
use App\ChampMatchInfo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class FootMatchChampController extends Controller
{
    /**
     * Accès Back-Office
     * Affichage de la liste des matches de Championnat de Football
     *
     * @return \Illuminate\View\View
     */
    public function lister()
    {
        Log::info(" -------- FootMatchChampController : lister -------- ");
        $idFootball = Sport::firstWhere('nom', 'like', 'football')->id ?? 0;
        $championnats = Championnat::where('sport_id', $idFootball)->orderBy('nom')->get();
        $h1 = $title = 'Football - Les matches de championnat';
        return view(
            'admin.champ-matches.foot.lister',
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
        Log::info(" -------- FootMatchChampController : ajouter -------- ");
        $champMatch = new ChampMatch();
        $championnat = new Championnat();
        $saison = new ChampSaison();
        $saisons = [];
        $journees = [];
        $title = 'Championnat/Football : Ajout d\'un match';

        $idFootball = Sport::firstWhere('nom', 'like', 'football')->id ?? 0;
        $championnats = Sport::firstWhere('id', $idFootball)->championnats;
        $equipes = Equipe::orderBy('nom')->get();
        $terrains = Terrain::orderBy('nom')->get();

        return view('admin.champ-matches.foot.ajouter', [
            'champMatch' => $champMatch, 'title' => $title,
            'h1' => $title, 'championnats' => $championnats, 'championnatId' => $championnat->id,
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
        Log::info(" -------- FootMatchChampController : editer -------- ");
        $champMatch = ChampMatch::findOrFail($matchId);
        $infosMatch = $champMatch->champMatchInfos;
        $liens = infosChampMatch();
        foreach ($infosMatch as $champMatchInfo) {
            $info = $champMatchInfo->information; // On récupère ici un entier
            $attribut = $liens[$info]; // On récupère l'attribut qui correspond à cet entier
            $$attribut = $champMatchInfo->valeur;
        }

        // On insère les infos supplémentaires du match (forfaits/penalités/tab ... : tous les attributs présents dans le fichier JSON)
        foreach($liens as $cle => $lien)
            $champMatch->$lien = $$lien ?? '';

        $title = "Modification de match";
        return view('admin.champ-matches.foot.editer', [
            'champMatch' => $champMatch, 'title' => $title
        ]);
    }

    /**
     * Accès Back-Office
     * Traitement de la modification (score+horaire) d'un match de football de Championnat en POST
     *
     * @param Request $request
     * @param int $matchId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editerPost(Request $request, int $matchId)
    {
        Log::info(" -------- FootMatchChampController : editerPost -------- ");
        $champMatch = ChampMatch::findOrFail($matchId);
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
        $infos = $champMatch->champMatchInfos;
        foreach ($infos as $champMatchInfo)
            $champMatchInfo->delete();// On supprime tous les pénalités/forfaits éventuels qui existaient déjà

        $liens = infosChampMatch();
        foreach ($liens as $key => $attribut)
            if($request[$attribut]) // On insère les nouvelles informations
                ChampMatchInfo::create(['information' => $key, 'valeur' => $request[$attribut], 'champ_match_id' => $champMatch->id]);

        $score_eq_dom = $request['score_eq_dom'] ?? '';
        $score_eq_ext = $request['score_eq_ext'] ?? '';

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $champMatch->score_eq_dom || $score_eq_ext != $champMatch->score_eq_ext) {
            $nbModifs = $champMatch->nb_modifs + 1;
            $request['nb_modifs'] = $nbModifs;
        }

        $champMatch->update($request);
        $this::forgetCaches($champMatch);
        return redirect()->route('champ-matches.foot.editer', ['id' => $matchId]);
    }

    /**
     * Accès à la view du match
     *
     * @param  string $championnat
     * @param  string $annee
     * @param  string $equipeDom
     * @param  string $equipeExt
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function match(string $championnat, string $annee, string $equipeDom, string $equipeExt, int $id)
    {
        Log::info(" -------- FootMatchChampController : match -------- ");
        $champMatch = ChampMatch::findOrFail($id);

        // On vérifie l'URL
        if(strToUrl($champMatch->equipeDom->nom) != $equipeDom || strToUrl($champMatch->equipeExt->nom) != $equipeExt || $champMatch->champJournee->champSaison->annee() != $annee){
            Log::info('Match introuvable - Championnat : ' . $championnat . ', Année : ' . $annee . ', Equipe dom : ' . $equipeDom . ', Equipe ext : ' . $equipeExt);
            abort(404);
        }

        $infos = $champMatch->infos();
        return view('football.match', [
            'match' => $infos
        ]);
    }

    /**
     * Accès à la page de modification du résultat du match
     *
     * @param  mixed $id
     * @return void
     */
    public function resultat($id)
    {
        Log::info(" -------- FootMatchChampController : resultat -------- ");
        $champMatch = ChampMatch::findOrFail($id);
        $accesBloque = $champMatch->acces_bloque;
        if ($accesBloque){
            Log::info("Match bloqué. Id match : " . $champMatch->id);
            abort(403);
        }

        $infos = $champMatch->infos();
        return view('football.resultat', [
            'match' => $infos
        ]);
    }

    /**
     * Traitement de la modification du résultat du match
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function resultatPost(Request $request, int $id)
    {
        Log::info(" -------- FootMatchChampController : resultatPost -------- ");
        $request = Validator::make($request->all(), [
            'score_eq_dom' => 'required|integer|min:0|max:30',
            'score_eq_ext' => 'required|integer|min:0|max:30',
            'note' => 'nullable|string|max:200'
        ])->validate();

        $champMatch = ChampMatch::findOrFail($id);
        $score_eq_dom = $request['score_eq_dom'];
        $score_eq_ext = $request['score_eq_ext'];
        $note = $request['note'];
        $idUser = Auth::user()->id;

        // S'il y a un changement au niveau du score
        if ($score_eq_dom != $champMatch->score_eq_dom || $score_eq_ext != $champMatch->score_eq_ext) {
            $champMatch->update([
                'score_eq_dom' => $score_eq_dom,
                'score_eq_ext' => $score_eq_ext,
                'nb_modifs' => $champMatch->nb_modifs + 1
            ]);

            $champModif = new ChampModif([
                'user_id' => $idUser,
                'champ_match_id' => $id,
                'note' => $note,
            ]);
            $champModif->saveOrFail();

            $this::forgetCaches($champMatch);
        }

        $urlMatch = $champMatch->infos()['lienMatch'];
        return redirect($urlMatch);
    }

    /**
     * Accès à la page de modification de l'horaire du match
     *
     * @param  mixed $id
     * @return void
     */
    public function horaire($id)
    {
        Log::info(" -------- FootMatchChampController : horaire -------- ");
        $champMatch = ChampMatch::findOrFail($id);

        $infos = $champMatch->infos();
        return view('football.horaire', [
            'match' => $infos
        ]);
    }

    /**
     * Traitement de la modification de l'horaire du match
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function horairePost(Request $request, int $id)
    {
        Log::info(" -------- FootMatchChampController : horairePost -------- ");
        $champMatch = ChampMatch::findOrFail($id);
        $request = Validator::make($request->all(), [
            'date' => 'nullable|date',
            'heure' => 'nullable|size:5'
        ])->validate();

        $date = $request['date'];
        $heure = $request['heure'];
        // S'il y a un changement au niveau du score
        if ($date != $champMatch->date || $heure != $champMatch->heure) {
            $champMatch->update([
                'date' => $date,
                'heure' => $heure,
                'nb_modifs' => $champMatch->nb_modifs + 1
            ]);

            $this::forgetCaches($champMatch);
        }

        $urlMatch = $champMatch->infos()['lienMatch'];
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
        Log::info(" -------- FootMatchChampController : supprimer -------- ");
        $validator = Validator::make($request->all(), [
            'delete' => 'required|array',
            'delete.*' => "integer|exists:champ_matches,id"
        ]);

        if ($validator->fails())
            abort(404, 'Requête invalide.');

        $request = $validator->validate();
        foreach ($request['delete'] as $id) {
            $match = ChampMatch::findOrFail($id);
            $match->delete();

            $this::forgetCaches($match);
        }
    }

    /**
     * Suppression des caches liés au match
     *
     * @param  ChampMatch $champMatch
     * @return void
     */
    private static function forgetCaches(ChampMatch $champMatch)
    {
        Log::info(" -------- FootMatchChampController : forgetCaches -------- ");
        Cache::forget('champ-match-' . $champMatch->id); // Les infos du match
        Cache::forget('journee-' . $champMatch->champJournee->id); // Les infos de la journée
        Cache::forget('classement-' . $champMatch->champJournee->champSaison->id); // Le classement de la saison
    }
}

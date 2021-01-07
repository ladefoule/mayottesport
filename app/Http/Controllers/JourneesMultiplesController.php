<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App\Http\Controllers;

use App\Cache;
use App\Sport;
use App\Saison;
use App\Journee;
use Illuminate\Http\Request;
use App\Jobs\ProcessCrudTable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JourneesMultiplesController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("Accès au controller JourneesMultiples - Ip : " . request()->ip());
    }

    /**
     * Ajout de toutes les journées d'une même saison
     *
     * @return \Illuminate\View\View
     */
    public function select()
    {
        Log::info(" -------- Controller JourneesMultiples : select -------- ");
        $sports = Sport::orderBy('nom')->get();
        $h1 = $title = 'Saison : Ajout de toutes les journées';

        return view('admin.journees.multi.select', [
            'sports' => $sports,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Modification de toutes les journées d'une même saison
     *
     * @param int $saisonId
     * @return \Illuminate\View\View
     */
    public function edit($saisonId)
    {
        Log::info(" -------- Controller JourneesMultiples : edit -------- ");
        $saison = Saison::findOrFail($saisonId);
        $nbJournees = $saison->nb_journees;
        $h1 = $title = 'Journees/SaisonId : ' . $saison->id;

        $journees = Journee::whereSaisonId($saisonId)->get();
        foreach ($journees as $journee) {
            $numero = $journee->numero;
            $journee->nameJourneeNumero = 'numero'.$numero;
            $journee->nameJourneeDate = 'date'.$numero;
            $journee->nameJourneeId = 'id'.$numero;
            $journee->nameJourneeDelete = 'delete'.$numero;

            $listeJournees[$numero] = $journee;
        }
        // dd($listeJournees);
        // $journee = DB::table('journees')->where([ ['saison_id', '=', $saisonId], ['numero', '=', $i] ])->first();

        return view('admin.journees.multi.edit', [
            'saison' => $saison->crud_name,
            'saisonId' => $saisonId,
            'listeJournees' => $listeJournees,
            'nbJournees' => $nbJournees,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Traitement de l'ajout/modification de toutes les journées d'une mm saison en POST
     *
     * @param Request $request
     * @param int $saisonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editPost(Request $request, $saisonId)
    {
        Log::info(" -------- Controller JourneesMultiples : editPost -------- ");
        // Todo : fire une validation du tableau reçu
        $nbJournees = Saison::findOrFail($saisonId)->nb_journees;

        // On supprime le cache index de la table
        Cache::forget('index-journees');
        Cache::forget('indexcrud-journees');

        for ($i=1; $i <= $nbJournees; $i++) {
            if ($request['numero' . $i]) {
                $journeeId = $request['id' . $i];
                $numero = $request['numero' . $i];
                $date = $request['date' . $i];

                $journee = Journee::find($journeeId);
                $unique = Rule::unique('journees', 'numero')->where(function ($query) use ($numero, $saisonId) {
                    return $query->whereNumero($numero)->whereSaisonId($saisonId);
                })->ignore($journee);

                $rules = [
                    'date' . $i => 'required|date',
                    'numero' . $i => ["required","integer","min:1","max:$nbJournees", $unique],
                ];

                $donnees = [
                    'numero'.$i => $numero,
                    'date'.$i => $date
                ];

                Validator::make($donnees, $rules)->validate();
                $donnees = [
                    'numero' => $numero,
                    'date' => $date,
                    'saison_id' => $saisonId
                ];

                if($journeeId == 0){ // Si la journée n'existe pas encore dans la bdd, alors on la crée
                    $journee = new Journee($donnees);
                    $journee->save();
                    $id = $journee->id;
                    forgetCaches('journees', $journee);
                    ProcessCrudTable::dispatch('journees', $id);
                }else{
                    $journee = Journee::findOrFail($journeeId);
                    $id = $journee->id;
                    forgetCaches('journees', $journee);

                    $journeeDelete = $request->has('delete' . $i);
                    if($journeeDelete){ // Si la checkbox de suppression a été cochée alors on supprime la Journée
                        $journee->delete();
                        ProcessCrudTable::dispatch('journees');
                    }else{ // Sinon on fait une maj
                        $journee->update($donnees);
                        ProcessCrudTable::dispatch('journees', $id);
                    }
                }
            }
        }

        return redirect()->route('journees.multi.show', ['id' => $saisonId]);
    }

    /**
     *
     * @param int $saisonId
     * @return \Illuminate\View\View
     */
    public function show(int $saisonId)
    {
        Log::info(" -------- Controller JourneesMultiples : show -------- ");
        $saison = Saison::findOrFail($saisonId);
        $h1 = $title = 'Journees/SaisonId : ' . $saisonId;
        $journees = $saison->journees->sortBy('numero');

        return view('admin.journees.multi.show', [
            'saison' => $saison->crud_name,
            'title' => $title,
            'h1' => $h1,
            'saisonId' => $saisonId,
            'journees' => $journees
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Sport;
use App\Competition;
use App\Saison;
use App\Journee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JourneesMultiplesController extends Controller
{
    /**
     * Ajout de toutes les journées d'une même saison
     *
     * @return \Illuminate\View\View
     */
    public function choixSaison()
    {
        $sports = Sport::orderBy('nom')->get();
        $h1 = $title = 'Saison : Ajout de toutes les journées';

        return view('admin.journees.multi.choix-saison', [
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
    public function editMultiples(int $saisonId)
    {
        $saison = Saison::findOrFail($saisonId);
        $competition = $saison->competition;
        $sport = $saison->competition->sport;
        $nbJournees = $saison->nb_journees;
        $h1 = $title = 'journees/Saison : ' . $saison->saison_id;

        return view('admin.journees.multi.editer', [
            'competition' => $competition->nom,
            'sport' => $sport->nom,
            'champSaison' => $saison->nom,
            'saisonId' => $saisonId,
            'nbJournees' => $nbJournees,
            'title' => $title,
            'h1' => $h1
        ]);
    }

    /**
     * Traitement de l'ajout/modification de toutes les journées d'une mm saison en POST
     *
     * @param Request $request
     * @param int $saisonId = 0
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editMultiplesPost(Request $request, int $saisonId = 0)
    {
        $rules = ['saison_id' => 'required|exists:saisons,id'];
        Validator::make($request->all(), $rules)->validate();
        $saisonId = $request['saison_id'];

        for ($i=1; $i <= 100; $i++) {
            if ($request['numero' . $i]) {
                $journeeNumero = $request['numero' . $i];
                $journeeDate = $request['date' . $i];
                $journeeId = $request['id' . $i];
                $tab = ['numero' => $journeeNumero, 'date' => $journeeDate, 'saison_id' => $saisonId];
                $request['delete'.$i] = $request->has('delete'.$i);
                $journeeDelete = $request['delete' . $i];

                $rules = [
                    'date'.$i => 'required|date',
                    'id'.$i => 'required|integer|min:0',
                    'numero'.$i => "required|integer|min:1|max:100",
                    'delete'.$i => 'boolean'
                ];
                Validator::make($request->all(), $rules)->validate();

                if($journeeId == 0){ // Si la journée n'existe pas encore dans la bdd, alors on la crée
                    $journee = new Journee($tab);
                    $journee->save();
                }else{
                    $journee = Journee::findOrFail($journeeId);
                    if($journeeDelete) // Si la checkbox de suppression a été cochée alors on supprime la Journée
                        $journee->delete();
                    else // Sinon on fait une maj
                        $journee->update($tab);
                }
            }
        }
        return redirect()->route('champ-journees.multi.voir', ['id' => $saisonId]);
    }

    /**
     *
     * @param int $saisonId
     * @return \Illuminate\View\View
     */
    public function vueMultiples(int $saisonId)
    {
        $saison = Saison::findOrFail($saisonId);
        $competition = $saison->competition;
        $sport = $competition->sport;
        $h1 = $title = 'Journees/Saison : ' . $saisonId;
        $journees = $saison->journees->sortBy('numero');

        return view('admin.journees.multi.voir', [
            'champSaison' => $saison->nom,
            'competition' => $competition->nom,
            'sport' => $sport->nom,
            'title' => $title,
            'h1' => $h1,
            'saisonId' => $saisonId,
            'journees' => $journees
        ]);
    }
}

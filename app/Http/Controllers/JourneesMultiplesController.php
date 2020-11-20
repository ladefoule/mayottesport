<?php

namespace App\Http\Controllers;

use App\Cache;
use App\Sport;
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
    public function seasonChoice()
    {
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
        $saison = Saison::findOrFail($saisonId);
        $nbJournees = $saison->nb_journees;
        $h1 = $title = 'Journees/SaisonId : ' . $saison->id;

        return view('admin.journees.multi.edit', [
            'saison' => $saison->crud_name,
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
     * @param int $saisonId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editStore(Request $request, $saisonId)
    {
        $rules = ['saison_id' => 'required|exists:saisons,id'];
        Validator::make($request->all(), $rules)->validate();
        $saisonId = $request['saison_id'];
        $nbJournees = Saison::findOrFail($saisonId)->nb_journees;

        for ($i=1; $i <= $nbJournees; $i++) {
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

        Cache::forget('crud-journees-index');
        return redirect()->route('journees.multi.show', ['id' => $saisonId]);
    }

    /**
     *
     * @param int $saisonId
     * @return \Illuminate\View\View
     */
    public function show(int $saisonId)
    {
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

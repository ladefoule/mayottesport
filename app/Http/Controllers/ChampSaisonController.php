<?php

namespace App\Http\Controllers;

use App\Championnat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ChampSaisonController extends Controller
{
    public function classement($championnat, $annee)
    {
        // dd(stripAccents(Str::kebab(str_replace(' ', '-', 'Régional 1'))));
        $annee = explode('-', $annee)[0];
        $championnatSansTirets = str_replace('-', ' ', $championnat);
        $championnat = Championnat::firstWhere('nom', $championnatSansTirets) ?? false;
        // $championnat = Championnat::firstWhere(stripAccents(Str::kebab(str_replace(' ', '-', 'nom'))), 'LIKE', $championnat)->get();
        $champSaison = $championnat->champSaisons->firstWhere('annee_debut', $annee);
        // $champSaison = $championnat->champSaison;
        $nomSaison = $champSaison->nom;
        $classement = $champSaison->classement();
        return view('football.classement', [
            'classement' => $classement,
            'champSaison' => $nomSaison
        ]);
    }
}

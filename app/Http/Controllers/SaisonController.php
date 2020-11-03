<?php

namespace App\Http\Controllers;

use App\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SaisonController extends Controller
{
    public function classement($competition, $annee)
    {
        // dd(stripAccents(Str::kebab(str_replace(' ', '-', 'Régional 1'))));
        $annee = explode('-', $annee)[0];
        $competition = Competition::firstWhere('nom', strToUrl($competition)) ?? false;
        // $competition = Competition::firstWhere(stripAccents(Str::kebab(str_replace(' ', '-', 'nom'))), 'LIKE', $competition)->get();
        $champSaison = $competition->saisons->firstWhere('annee_debut', $annee);
        // $champSaison = $competition->saison;
        $nomSaison = $champSaison->nom;
        $classement = $champSaison->classement();
        return view('football.classement', [
            'classement' => $classement,
            'champSaison' => $nomSaison
        ]);
    }
}

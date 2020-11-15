<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SportController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info(" -------- SportController : __construct -------- ");
        $this->middleware('sport');
    }

    public function index(Request $request)
    {
        $sport = $request->sport;
        $competitions = $sport->competitions;
        $liste = [];
        foreach ($competitions as $competition) {
            $saisonEnCours = $competition->saisons->firstWhere('finie', '!=', 1);
            if($saisonEnCours){

            }
        }
        return view('sport.index', [
            'sport' => $sport->nom,
            'liste' => $liste
        ]);
    }
}

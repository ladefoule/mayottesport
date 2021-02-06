<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AjaxController extends Controller
{
    public function index(Request $request)
    {
        Log::info(" -------- Controller Ajax : index -------- ");
        $table = $request->table;        
        $tables = [
            'competitions' => [
                'attribut' => 'sport_id',
                'orderby' => [
                    'champ' => 'nom', 
                    'sens' => 'asc'
                ]
            ],
            'saisons' => [
                'attribut' => 'competition_id',
                'orderby' => [
                    'champ' => 'annee_debut', 
                    'sens' => 'desc'
                ]
            ],
            'journees' => [
                'attribut' => 'saison_id',
                'orderby' => [
                    'champ' => 'numero', 
                    'sens' => 'asc'
                ]
            ],
        ];

        if(! key_exists($table, $tables))
            abort(404);

        $attribut = $tables[$table]['attribut'];
        $orderby = $tables[$table]['orderby'];
        $id = $request[$attribut];
        $index = index($table)->where($attribut, $id);

        if($orderby['sens'] == 'asc')
            $index = $index->sortBy($orderby['champ']);
        else 
            $index = $index->sortByDesc($orderby['champ']);

        foreach ($index as $id => $instance) {
            // Pour les saisons, on les n'affiche que les saisons avec des journées
            if($table == 'saisons' && isset($request['avec_journees'])){
                $journees = index('journees')->where('saison_id', $id);
                if(count($journees))
                    $result[] = [
                        'id' => $instance->id,
                        'nom' => $instance->nom,
                    ];
            }else
                $result[] = [
                    'id' => $instance->id,
                    'nom' => $instance->nom,
                ];
        }
        return $result ?? [];
    }
}

<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Terrain extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'ville_id'];

    /**
     * Définition de l'affichage d'un élément de la table
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? '';
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Terrain $terrain
     * @return array
     */
    public static function rules(Request $request, Terrain $terrain = null)
    {
        $nom = $request['nom'] ?? '';
        $villeId = $request['ville_id'] ?? '';
        $unique = Rule::unique('terrains')->where(function ($query) use ($nom, $villeId) {
            return $query->whereNom($nom)->whereVilleId($villeId);
        });

        if($terrain){
            $id = $terrain->id;
            $unique = $unique->ignore($id);
        }

        $rules = [
            'nom' => ['required','string','max:50',$unique],
            'ville_id' => 'required|exists:villes,id'
        ];
        $messages = ['nom.unique' => "Ce nom de terrain, associé à cette ville, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

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
     * Les règles de validations
     *
     * @param Terrain $terrain
     * @return array
     */
    public static function rules(Terrain $terrain = null)
    {
        $unique = Rule::unique('terrains')->where(function ($query) {
            return $query->whereNom(request()['nom'])->whereVilleId(request()['ville_id']);
        })->ignore($terrain);

        $rules = [
            'nom' => ['required','string','max:50',$unique],
            'ville_id' => 'required|exists:villes,id'
        ];
        $messages = ['nom.unique' => "Ce nom de terrain, associé à cette ville, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $ville = index('villes')[$this->ville_id];
        return $ville->nom . ' - ' . $this->nom;
    }
}

<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom'];

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
     * @param Region $region
     * @param Request $request
     * @return array
     */
    public static function rules(Request $request, Region $region = null)
    {
        $nom = $request['nom'] ?? '';
        $unique = Rule::unique('regions')->where(function ($query) use ($nom) {
            return $query->whereNom($nom);
        });

        if($region){
            $id = $region->id;
            $unique = $unique->ignore($id);
        }

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

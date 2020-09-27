<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */{
    protected $fillable = ['nom'];

    public function __toString()
    {
        return $this->nom ?? '';
    }

    /**
     * Les règles de validations
     *
     * @param Ville $ville
     * @param Request $request
     * @return array
     */
    public static function rules(Request $request, Ville $ville = null)
    {
        $nom = $request['nom'] ?? '';
        $unique = Rule::unique('villes')->where(function ($query) use ($nom) {
            return $query->whereNom($nom);
        });

        if($ville){
            $id = $ville->id;
            $unique = $unique->ignore($id);
        }

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

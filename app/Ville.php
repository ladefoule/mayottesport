<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom'];

    /**
     * Les règles de validations
     *
     * @param Ville $ville
     * @return array
     */
    public static function rules(Ville $ville = null)
    {
        $unique = Rule::unique('villes')->where(function ($query) {
            return $query->whereNom(request()['nom']);
        })->ignore($ville);

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

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
     * Les règles de validations
     *
     * @param Region $region
     * @return array
     */
    public static function rules(Region $region = null)
    {
        $nom = request()->nom ?? '';
        $unique = Rule::unique('regions')->where(function ($query) use ($nom) {
            return $query->whereNom($nom);
        })->ignore($region);

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

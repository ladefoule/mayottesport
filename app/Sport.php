<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category - active record
 *
 * Recipe categories
 */
class Sport extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'home_position'];

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public static function crudName($id)
    {
        return index('sports')[$id]->nom;
    }

    /**
     * Les competitions associés à ce sport
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function competitions()
    {
        return $this->hasMany('App\Competition');
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Sport $sport
     * @return array
     */
    public static function rules(Sport $sport = null)
    {
        $rules = [
            'nom' => ['required','string','max:50','min:3',Rule::unique('sports')->ignore($sport)],
            'home_position' => 'nullable|integer|min:1',
        ];
        return ['rules' => $rules];
    }
}

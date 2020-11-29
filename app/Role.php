<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'niveau'];

    public $timestamps = false;

    /**
     * Tous les utilisateurs de la catégorie
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public static function crudName($id)
    {
        return index('roles')[$id]->nom;
    }

    /**
     * Les règles de validations
     *
     * @param Role $role
     * @return array
     */
    public static function rules(Role $role = null)
    {
        $unique = Rule::unique('villes')->where(function ($query) {
            return $query->whereNom(request()['nom']);
        })->ignore($role);

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

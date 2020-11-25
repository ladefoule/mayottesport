<?php

namespace App;

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
     * Définition de l'attribut nom pour un objet de la class Role
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return $this->nom;
    }
}

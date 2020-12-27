<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modif extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['user_id', 'match_id', 'note', 'type'];

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $user = index('users')[$this->user_id];
        $match = index('matches')[$this->match_id];
        return $match->uniqid . ' - ' . $user->nom;
    }
}

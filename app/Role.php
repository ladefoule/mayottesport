<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

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

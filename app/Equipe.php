<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'nom_complet', 'sport_id', 'ville_id', 'slug', 'slug_complet'];

    /**
     * Les règles de validations
     *
     * @param Equipe $equipe
     * @return array
     */
    public static function rules(Equipe $equipe = null)
    {
        $uniqid = Rule::unique('equipes')->ignore($equipe);

        $rules = [
            'nom_complet' => 'required|min:3|max:50',
            'sport_id' => 'required|integer|exists:sports,id',
            'ville_id' => 'required|integer|exists:villes,id',
            'nom' => ['required','max:50','min:3',$$uniqid],
            'uniqid' => ['required','max:50','min:3',$$uniqid],
            'slug' => ['required','alpha_dash','max:50','min:3',$$uniqid],
            'slug_complet' => ['required','alpha_dash','max:50','min:3',$$uniqid],
        ];
        return ['rules' => $rules];
    }
    
    /**
     * Le sport lié à cette équipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * La ville de l'équipe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ville()
    {
        return $this->belongsTo('App\Ville');
    }

    /**
     * Définition de l'affichage dans le CRUD
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return indexCrud('sports')[$this->sport_id]->nom . ' - ' . $this->nom;
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function saisons()
    {
        return $this->belongsToMany('App\Saison')
                    ->using('App\EquipeSaison');
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function matches()
    {
        return $this->hasMany('App\Match', 'equipe_id_dom')
                    ->union($this->hasMany('App\Match', 'equipe_id_ext'));
    }
}

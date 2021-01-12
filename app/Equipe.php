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
    protected $fillable = ['nom', 'nom_complet', 'sport_id', 'feminine', 'non_mahoraise', 'ville_id', 'slug'];

    /**
     * Les règles de validations
     *
     * @param Equipe $equipe
     * @return array
     */
    public static function rules(Equipe $equipe = null)
    {
        request()['feminine'] = request()->has('feminine');
        request()['non_mahoraise'] = request()->has('non_mahoraise');

        $uniqueNomEtSportId = Rule::unique('equipes')->where(function ($query) {
            return $query->whereNom(request()['nom'])->whereSportId(request()['sport_id']);
        })->ignore($equipe);

        $uniqueSlug = Rule::unique('equipes')->where(function ($query) {
            return $query->whereSlug(request()['slug'])->whereSportId(request()['sport']);
        })->ignore($equipe);

        $uniqid = Rule::unique('equipes')->ignore($equipe);

        $rules = [
            'nom_complet' => 'nullable|min:3|max:50',
            'sport_id' => 'required|integer|exists:sports,id',
            'ville_id' => 'required|integer|exists:villes,id',
            'feminine' => 'boolean',
            'non_mahoraise' => 'boolean',
            'nom' => ['required','max:50','min:3',$uniqueNomEtSportId],
            'uniqid' => ['required','max:50','min:3',$uniqid],
            'slug' => ['required','alpha_dash','max:50','min:3',$uniqueSlug],
        ];
        $messages = ['nom.unique' => "Ce nom d'équipe, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
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

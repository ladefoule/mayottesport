<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'type', 'nom_complet', 'sport_id', 'home_position', 'index_position'];

    /**
     * Les règles de validations
     *
     * @param Competition $competition
     * @return array
     */
    public static function rules(Competition $competition = null)
    {
        $unique = Rule::unique('competitions')->where(function ($query) {
            return $query->whereNom(request()['nom'])->whereSportId(request()['sport']);
        })->ignore($competition);

        $rules = [
            'sport_id' => 'required|exists:sports,id',
            'type' => 'required|integer|min:1',
            'home_position' => 'nullable|integer|min:1',
            'index_position' => 'nullable|integer|min:1',
            'nom_complet' => 'nullable|string|max:50',
            'nom' => ['required','string','max:50','min:3',$unique]
        ];
        $messages = ['nom.unique' => "Ce nom de compétition, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return indexCrud('sports')[$this->sport_id]->nom . ' - ' . $this->nom;
    }

    /**
     * Le sport lié à cette compétition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * Les saisons associées à la compétition
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saisons()
    {
        return $this->hasMany('App\Saison');
    }

    /**
     * Le palmarès
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champions()
    {
        return $this->hasMany('App\Champion');
    }

    /**
     * Les articles liés à la compétition
     */
    public function articles()
    {
        return $this->belongsToMany('App\Article')->using('App\ArticleCompetition');
    }
}

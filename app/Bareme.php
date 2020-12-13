<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Bareme extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'victoire', 'nul', 'defaite', 'sport_id'];

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
     * Toutes les saisons possédant le barème
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saisons()
    {
        return $this->hasMany('App\Saison');
    }

    /**
     * Le sport lié au barème
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Bareme $bareme
     * @return array
     */
    public static function rules(Bareme $bareme = null)
    {
        $unique = Rule::unique('baremes')->where(function ($query) {
            return $query->whereNom(request()['nom'])->whereSportId(request()['sport_id']);
        })->ignore($bareme);

        $rules = [
            'victoire' => 'required|integer|min:0|max:30',
            'nul' => 'nullable|integer|min:0|max:30',
            'defaite' => 'required|integer|min:0|max:30',
            'sport_id' => 'required|exists:sports,id',
            'nom' => ['required','string','max:50','min:3',$unique]
        ];
        $messages = ['nom.unique' => "Ce nom de barème, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

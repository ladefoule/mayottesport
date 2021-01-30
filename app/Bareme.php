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
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'victoire', 'nul', 'defaite', 'sport_id', 'forfait'];

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
        $uniqueNomEtSportId = Rule::unique('baremes', 'nom', 'sport_id')->ignore($bareme);

        $rules = [
            'victoire' => 'nullable|integer|min:0|max:30',
            'nul' => 'nullable|integer|min:0|max:30',
            'defaite' => 'nullable|integer|min:0|max:30',
            'forfait' => 'nullable|integer|min:0',
            'sport_id' => 'required|exists:sports,id',
            'nom' => ['required','string','max:50','min:3',$uniqueNomEtSportId]
        ];
        $messages = ['nom.unique' => "Ce nom de barème, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Les infos supplémentaires liées au barème
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function baremeInfos()
    {
        return $this->hasMany('App\BaremeInfo');
    }
}

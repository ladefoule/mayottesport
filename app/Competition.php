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
    protected $fillable = ['nom', 'type', 'nom_complet', 'sport_id', 'home_position', 'index_position', 'slug'];

    /**
     * Les règles de validations
     *
     * @param Competition $competition
     * @return array
     */
    public static function rules(Competition $competition = null)
    {
        $unique = Rule::unique('competitions', 'nom', 'sport_id')->ignore($competition);
        $uniqueSlug = Rule::unique('competitions', 'slug', 'sport_id')->ignore($competition);

        $rules = [
            'sport_id' => 'required|exists:sports,id',
            'type' => 'required|integer|min:1',
            'home_position' => 'nullable|integer|min:0',
            'index_position' => 'nullable|integer|min:0',
            'nom_complet' => 'nullable|max:50',
            'nom' => ['required','max:50','min:3',$unique],
            'slug' => ['required','alpha_dash','max:50','min:3',$uniqueSlug],
        ];
        $messages = ['nom.unique' => "Ce nom de compétition, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
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
     * Les articles liés à la compétition
     */
    public function articles()
    {
        return $this->belongsToMany('App\Article')->using('App\ArticleCompetition');
    }
}

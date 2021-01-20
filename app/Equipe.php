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
        $sportId = request()->input('sport_id');

        $unique = Rule::unique('equipes')->ignore($equipe);
        $uniqueWithSportId = Rule::unique('equipes')->where(function ($query) use ($sportId) {
            return $query->whereSportId($sportId);
        })->ignore($equipe);

        $rules = [
            'nom_complet' => ['required','min:3','max:50',$uniqueWithSportId],
            'sport_id' => 'required|integer|exists:sports,id',
            'ville_id' => 'required|integer|exists:villes,id',
            'nom' => ['required','max:50','min:3',$uniqueWithSportId],
            'uniqid' => ['required','max:50','min:3',$unique],
            'slug' => ['required','alpha_dash','max:50','min:3',$uniqueWithSportId],
            'slug_complet' => ['required','alpha_dash','max:50','min:3',$uniqueWithSportId],
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

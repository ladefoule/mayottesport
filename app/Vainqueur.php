<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Vainqueur extends Model
{
    public $fillable = ['equipe_id', 'saison', 'competition_id'];

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipe()
    {
        return $this->belongsTo('App\Equipe');
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }

    /**
     * Définition de l'attribut nom
     */
    public function getNomAttribute()
    {
        return $this->competition->nom . ' ' . $this->saison . ' : ' . $this->equipe->nom;
    }

    /**
     * Les règles de validations
     *
     * @param Region $region
     * @return array
     */
    public static function rules(Region $vainqueur = null)
    {
        $equipeId = request()->equipe_id ?? '';
        $competitionId = request()->competition_id ?? '';
        $saison = request()->saison ?? '';
        $unique = Rule::unique('vainqueurs')->where(function ($query) use ($equipeId, $competitionId, $saison) {
            return $query->whereEquipeId($equipeId)->whereCompetitionId($competitionId)->where('saison', 'like', $saison);
        })->ignore($vainqueur);

        $rules = [
            'saison' => ['required','max:20',$unique],
            'equipe_id' => 'required|integer|exists:equipes,id',
            'competition_id' => 'required|integer|exists:competitions,id',
        ];
        return ['rules' => $rules];
    }
}

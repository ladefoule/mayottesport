<?php

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CompetitionSport extends Pivot
{
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['competition_id', 'sport_id', 'position'];

    /**
     * Les règles de validations
     *
     * @param CompetitionSport $competitionSport
     * @return array
     */
    public static function rules(CompetitionSport $competitionSport = null)
    {
        $competitionId = request()->input('competition_id');
        $position = request()->input('position');

        $unique = Rule::unique('competition_sport')->where(function ($query) use ($competitionId, $position) {
            return $query->whereCompetitionId($competitionId)->wherePosition($position);
        })->ignore($competitionSport);

        $rules = [
            'sport_id' => ['required','integer','exists:sports,id',$unique],
            'competition_id' => 'required|exists:competitions,id',
            'position' => 'required|integer|min:1',
        ];

        return ['rules' => $rules];
    }
}

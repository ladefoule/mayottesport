<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class FootMatch extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['match_id', 'buts_equipe1', 'buts_equipe2', 'avec_prolongation', 'avec_tab', 'tab_equipe1', 'tab_equipe2'];

    /**
     * Définition de l'affichage d'un élément de la table
     *
     * @return string
     */
    public function __toString()
    {
        return ($this->buts_equipe1 && $this->buts_equipe2) ? $this->buts_equipe1 . ' - ' . $this->buts_equipe2 : ' - ';
    }

    /**
     * La match (dans la tables matches) lié à ce match de football
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match()
    {
        return $this->belongsTo('App\Match');
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param FootMatch $footMatch
     * @return array
     */
    public static function rules(Request $request, FootMatch $footMatch = null)
    {
        $matchId = $request['match_id'] ?? '';
        $unique = Rule::unique('foot_matches')->where(function ($query) use ($matchId) {
            return $query->whereMatchId($matchId);
        });

        if($footMatch){
            $id = $footMatch->id;
            $unique = $unique->ignore($id);
        }

        $rules = [
            'match_id' => ['required','exists:matches,id',$unique],
            'buts_equipe1' => 'nullable|required_with:buts_equipe2|integer|min:0|max:255',
            'buts_equipe2' => 'nullable|required_with:buts_equipe1|integer|min:0|max:255',
            'tab_equipe1' => 'nullable|required_if:avec_tab|integer|min:0|max:50',
            'tab_equipe2' => 'nullable|required_if:avec_tab|integer|min:0|max:50',
        ];

        $request['avec_tab'] = $request->has('avec_tab');
        $request['avec_prolongation'] = $request->has('avec_prolongation');
        $messages = ['match_id.unique' => "Ce match est déjà lié à un match football ou un autre sport."];
        return ['rules' => $rules, 'messages' => $messages, 'request' => $request];
    }
}

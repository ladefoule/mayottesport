<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Championnat extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'nom_complet', 'sport_id'];

    /**
     * Le sport dont appartient ce championnat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }

    /**
     * Les saisons associées au championnat
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champSaisons()
    {
        return $this->hasMany('App\ChampSaison');
    }

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param Championnat $championnat
     * @return array
     */
    public static function rules(Request $request, Championnat $championnat = null)
    {
        $nom = $request['nom'] ?? '';
        $sportId = $request['sport_id'] ?? '';
        $unique = Rule::unique('championnats')->where(function ($query) use ($nom, $sportId) {
            return $query->whereNom($nom)->whereSportId($sportId);
        });

        if($championnat){
            $id = $championnat->id;
            $unique = $unique->ignore($id);
        }

        $rules = [
            'sport_id' => 'required|exists:sports,id',
            'nom_complet' => 'nullable|string|max:50',
            'nom' => ['required','string','max:50','min:3',$unique]
        ];
        $messages = ['nom.unique' => "Ce nom de championnat, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

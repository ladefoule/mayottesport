<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ChampBareme extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'victoire', 'nul', 'defaite', 'sport_id'];

    /**
     * Définition de l'affichage d'un barème
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nom ?? '';
    }

    /**
     * Toutes les saisons possédant le barème
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function champ_saisons()
    {
        return $this->hasMany('App\ChampSaison');
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
     * @param ChampBareme $champBareme
     * @return array
     */
    public static function rules(Request $request, ChampBareme $champBareme = null)
    {
        $nom = $request['nom'] ?? '';
        $sportId = $request['sport_id'] ?? '';
        $unique = Rule::unique('champ_baremes')->where(function ($query) use ($nom, $sportId) {
            return $query->whereNom($nom)->whereSportId($sportId);
        });

        if($champBareme){
            $id = $champBareme->id;
            $unique = $unique->ignore($id);
        }

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

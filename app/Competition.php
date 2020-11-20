<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'type', 'nom_complet', 'sport_id'];

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return awesome('sports')[$this->sport_id]['crud_name'] . ' - ' . $this->nom;
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
     * Les règles de validations
     *
     * @param Competition $competition
     * @return array
     */
    public static function rules(Competition $competition = null)
    {
        $nom = request()->nom ?? '';
        $sportId = request()->sport_id ?? '';
        $unique = Rule::unique('competitions')->where(function ($query) use ($nom, $sportId) {
            return $query->whereNom($nom)->whereSportId($sportId);
        })->ignore($competition);

        $rules = [
            'sport_id' => 'required|exists:sports,id',
            'type' => 'required|integer|min:1',
            'nom_complet' => 'nullable|string|max:50',
            'nom' => ['required','string','max:50','min:3',$unique]
        ];
        $messages = ['nom.unique' => "Ce nom de compétition, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

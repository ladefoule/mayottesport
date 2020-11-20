<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Equipe extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'nom_complet', 'sport_id', 'feminine', 'non_mahoraise'];

    /**
     * Définition de l'affichage d'un objet dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return $this->sport->crud_name . ' - ' . $this->nom;
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
     * Teste si l'équipe possède un fanion présent dans le repertoire app/public/img/fanion.
     * Dans le cas ou il existe on renvoie le lien complet vers celui-ci.
     * Sinon on renvoie le lien vers le fanion par défaut.
     *
     * @return string
     */
    public function fanion()
    {
        $fanion = 'foot-' . $this->id;
        $exists = Storage::disk('public')->exists('img/fanion/' . $fanion . '.png');
        if($exists == false)
            $fanion = "defaut-2";

        return "/storage/img/fanion/" . $fanion . '.png';
    }

    /**
     * Les règles de validations
     *
     * @param Equipe $equipe
     * @return array
     */
    public static function rules(Equipe $equipe = null)
    {
        request()->feminine = request()->has('feminine');
        request()->non_mahoraise = request()->has('non_mahoraise');

        $nom = request()->nom ?? '';
        $sportId = request()->sport_id ?? '';
        $unique = Rule::unique('equipes')->where(function ($query) use ($nom, $sportId) {
            return $query->whereNom($nom)->whereSportId($sportId);
        })->ignore($equipe);

        $rules = [
            'nom_complet' => 'nullable|string|min:3|max:50',
            'sport_id' => 'required|integer|exists:sports,id',
            'feminine' => 'boolean',
            'non_mahoraise' => 'boolean',
            'nom' => ['required','max:50','min:3',$unique]
        ];
        $messages = ['nom.unique' => "Ce nom d'équipe, associé à ce sport, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

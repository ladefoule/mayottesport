<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EquipeSaison extends Pivot
{
    public $timestamps = false;
    // protected $table = 'champ_saison_equipe';

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['equipe_id', 'saison_id'];

    /**
     * Les règles de validations
     *
     * @param EquipeSaison $equipeSaison
     * @return array
     */
    public static function rules(EquipeSaison $equipeSaison = null)
    {
        $equipeId = request()->equipe_id ?? '';
        $saisonId = request()->saison_id ?? '';
        $unique = Rule::unique('equipe_saison')->where(function ($query) use ($equipeId, $saisonId) {
            return $query->whereEquipeId($equipeId)->whereSaisonId($saisonId);
        })->ignore($equipeSaison);

        $rules =[
            'equipe_id' => ['required','exists:equipes,id',$unique],
            'saison_id' => ['required','exists:saisons,id']
        ];
        return ['rules' => $rules];
    }

    /**
     * Définition de l'affichage d'un objet dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return indexCrud('saisons')[$this->saison_id]['crud_name'] . ' - ' . $this->equipe->nom;
    }

    public function getNomAttribute()
    {
        return $this->saison->nom . ' - ' . $this->equipe->nom;
    }

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
    public function saison()
    {
        return $this->belongsTo('App\Saison');
    }
}

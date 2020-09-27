<?php

namespace App;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ChampSaisonEquipe extends Pivot
{
    public $timestamps = false;
    // protected $table = 'champ_saison_equipe';

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['equipe_id', 'champ_saison_id'];
}

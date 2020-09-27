<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChampModif extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['user_id', 'champ_match_id', 'note'];
}

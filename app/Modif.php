<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modif extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['user_id', 'match_id', 'note'];
}

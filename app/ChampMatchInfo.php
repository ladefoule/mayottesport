<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChampMatchInfo extends Model
{
    protected $fillable = ['information', 'champ_match_id', 'valeur'];
    public $timestamps = false;
}

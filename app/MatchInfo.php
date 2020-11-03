<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchInfo extends Model
{
    protected $fillable = ['information', 'match_id', 'valeur'];
    public $timestamps = false;
}

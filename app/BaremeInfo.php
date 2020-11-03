<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeremeInfo extends Model
{
    protected $fillable = ['information', 'bareme_id', 'valeur'];
    public $timestamps = false;
}

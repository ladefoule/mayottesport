<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaremeInfo extends Model
{
    protected $fillable = ['information', 'bareme_id', 'valeur'];
    public $timestamps = false;
}

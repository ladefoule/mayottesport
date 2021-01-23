<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Modif extends Model
{
    const UPDATED_AT = NULL;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['user_id', 'match_id', 'note', 'type'];
}

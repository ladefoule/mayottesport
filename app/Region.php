<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $timestamps = false;
    
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom'];

    /**
     * Les règles de validations
     *
     * @param Region $region
     * @return array
     */
    public static function rules(Region $region = null)
    {
        $unique = Rule::unique('regions')->ignore($region);

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }
}

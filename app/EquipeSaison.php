<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Pivot;

class EquipeSaison extends Pivot
{
    public $timestamps = false;

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
        $unique = Rule::unique('equipe_saison', 'equipe_id', 'saison_id')->ignore($equipeSaison);

        $rules =[
            'equipe_id' => ['required','exists:equipes,id',$unique],
            'saison_id' => ['required','exists:saisons,id']
        ];
        return ['rules' => $rules];
    }
}

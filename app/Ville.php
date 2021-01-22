<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom'];

    public $timestamps = false;

    /**
     * Les règles de validations
     *
     * @param Ville $ville
     * @return array
     */
    public static function rules(Ville $ville = null)
    {
        $unique = Rule::unique('villes')->ignore($ville);

        $rules['nom'] = ['required','string','max:50','min:3',$unique];
        return ['rules' => $rules];
    }

    /**
     * Les équipes appartenant à la ville
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipes()
    {
        return $this->hasMany('App\Equipe');
    }
}

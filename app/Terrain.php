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

class Terrain extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['nom', 'ville_id'];

    /**
     * Les règles de validations
     *
     * @param Terrain $terrain
     * @return array
     */
    public static function rules(Terrain $terrain = null)
    {
        $unique = Rule::unique('terrains', 'nom', 'ville_id')->ignore($terrain);

        $rules = [
            'nom' => ['required','string','max:50',$unique],
            'ville_id' => 'required|exists:villes,id'
        ];
        $messages = ['nom.unique' => "Ce nom de terrain, associé à cette ville, existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * Le ville du terrain
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ville()
    {
        return $this->belongsTo('App\Ville');
    }
}

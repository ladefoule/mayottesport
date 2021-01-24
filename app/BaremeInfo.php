<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class BaremeInfo extends Model
{
    protected $fillable = ['propriete_id', 'bareme_id', 'valeur'];
    public $timestamps = false;

    /**
     * Les règles de validations
     *
     * @param Bareme $bareme
     * @return array
     */
    public static function rules(Bareme $bareme = null)
    {
        $proprieteId = request()->input('propriete_id');

        $unique = Rule::unique('baremes')->where(function ($query) use ($proprieteId) {
            return $query->whereProprieteId($proprieteId);
        })->ignore($bareme);

        $rules = [
            'bareme_id' => ['required','integer','exists:baremes,id',$unique],
            'propriete_id' => 'required|integer|min:0',
            'valeur' => 'nullable|string|max:255',
        ];
        $messages = ['bareme_id.unique' => "Cette propriété existe déjà."];
        return ['rules' => $rules, 'messages' => $messages];
    }

    /**
     * L'attribut lié à cette information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bareme()
    {
        return $this->belongsTo('App\Bareme');
    }
}

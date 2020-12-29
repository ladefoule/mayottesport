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
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $crudProprietes = config('listes.proprietes-baremes');
        return indexCrud('baremes')[$this->bareme_id]->crud_name . ' - ' . $crudProprietes[$this->propriete_id][0];
    }

    /**
     * Les règles de validations
     *
     * @param Bareme $bareme
     * @return array
     */
    public static function rules(Bareme $bareme = null)
    {
        $unique = Rule::unique('bareme_infos')->where(function ($query){
            return $query->whereProprieteId(request()['propriete_id'])->whereBaremeId(request()['bareme_id']);
        })->ignore($bareme);

        $rules = [
            'bareme_id' => ['required','integer','exists:baremes,id',$unique],
            'propriete_id' => 'required|integer|min:0',
            'valeur' => 'nullable|string|max:255',
        ];
        $messages = ['attribut.unique' => "Cet attribut est déjà présent."];
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

<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class CrudAttributInfo extends Model
{
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = [
        'crud_attribut_id', 'propriete_id', 'valeur'
    ];

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        $crudProprietes = config('listes.proprietes-crud-attributs');
        return indexCrud('crud_attributs')[$this->crud_attribut_id]->crud_name . ' - ' . $crudProprietes[$this->propriete_id][0];
    }

    /**
     * Les règles de validations
     *
     * @param CrudAttributInfo $crudAttributInfo
     * @return array
     */
    public static function rules(CrudAttributInfo $crudAttributInfo = null)
    {
        $unique = Rule::unique('crud_attribut_infos')->where(function ($query) {
            return $query->whereProprieteId(request()['propriete_id'])->whereCrudAttributId(request()['crud_attribut_id']);
        })->ignore($crudAttributInfo);

        $rules = [
            'crud_attribut_id' => ['required','integer','exists:crud_attributs,id',$unique],
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
    public function crudAttribut()
    {
        return $this->belongsTo('App\CrudAttribut');
    }
}

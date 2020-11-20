<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class CrudAttribut extends Model
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = [
        'attribut', 'attribut_crud_table_id', 'crud_table_id', 'label', 'optionnel', 'data_msg'
    ];
    public $timestamps = false;

    /**
     * Définition de l'affichage dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return awesome('crud_tables')[$this->crud_table_id]['crud_name'] . '/' . $this->attribut;
    }

    /**
     * La table liée cet attribut
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function crudTable()
    {
        return $this->belongsTo('App\CrudTable');
    }

    /**
     * Les règles de validations
     *
     * @param CrudAttribut $crudAttribut
     * @return array
     */
    public static function rules(CrudAttribut $crudAttribut = null)
    {
        $attribut = request()->attribut ?? '';
        $crudTableId = request()->crud_table_id ?? '';
        $unique = Rule::unique('crud_attributs')->where(function ($query) use ($attribut, $crudTableId) {
            return $query->whereAttribut($attribut)->wherecrudTableId($crudTableId);
        })->ignore($crudAttribut);

        request()->optionnel = request()->has('optionnel');
        $rules = [
            'attribut' => ['required','string','max:50',$unique],
            'crud_table_id' => 'required|integer|exists:crud_tables,id',
            'attribut_crud_table_id' => 'nullable|integer|exists:crud_tables,id',
            'label' => 'required|string|max:50',
            'optionnel' => 'boolean',
            'data_msg' => 'nullable|string|max:300',
        ];
        $messages = ['attribut.unique' => "Cet attribut est déjà présent."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

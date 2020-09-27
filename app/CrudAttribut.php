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
     * Définition de l'attribut nom de l'objet
     *
     * @return string
     */
    public function getNomAttribute()
    {
        return $this->crudTable->nom . '/' . $this->attribut;
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
     * @param Request $request
     * @param CrudAttribut $crudAttribut
     * @return array
     */
    public static function rules(Request $request, CrudAttribut $crudAttribut = null)
    {
        $attribut = $request['attribut'] ?? '';
        $crudTableId = $request['crud_table_id'] ?? '';

        $unique = Rule::unique('crud_attributs')->where(function ($query) use ($attribut, $crudTableId) {
            return $query->whereAttribut($attribut)->wherecrudTableId($crudTableId);
        });

        if($crudAttribut)
            $unique = $unique->ignore($crudAttribut->id);

        $request['optionnel'] = $request->has('optionnel');
        $rules = [
            'attribut' => ['required','string','max:50',$unique],
            'crud_table_id' => 'required|integer|exists:crud_tables,id',
            'attribut_crud_table_id' => 'nullable|integer|exists:crud_tables,id',
            'label' => 'required|string|max:50',
            'optionnel' => 'boolean',
            'data_msg' => 'nullable|string|max:300',
        ];
        $messages = ['attribut.unique' => "Cet attribut est déjà présent."];
        return ['rules' => $rules, 'messages' => $messages, 'request' => $request];
    }
}

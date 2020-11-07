<?php

namespace App;

use Illuminate\Http\Request;
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
        'crud_attribut_id', 'information_id', 'valeur'
    ];

    /**
     * Les règles de validations
     *
     * @param Request $request
     * @param CrudAttributInfo $crudAttributInfo
     * @return array
     */
    public static function rules(Request $request, CrudAttributInfo $crudAttributInfo = null)
    {
        $informationId = $request['information_id'] ?? '';
        $crudAttributId = $request['crud_attribut_id'] ?? '';

        $unique = Rule::unique('crud_attribut_infos')->where(function ($query) use ($informationId, $crudAttributId) {
            return $query->whereInformationId($informationId)->whereCrudAttributId($crudAttributId);
        });

        if($crudAttributInfo)
            $unique = $unique->ignore($crudAttributInfo->id);

        $rules = [
            'crud_attribut_id' => ['required','integer','exists:crud_attributs,id',$unique],
            'information_id' => 'required|integer|min:0',
            'valeur' => 'required|string|max:255',
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

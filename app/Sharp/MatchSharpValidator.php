<?php


namespace App\Sharp;
use App\Match;
use Code16\Sharp\Http\WithSharpContext;
use Illuminate\Foundation\Http\FormRequest;

class MatchSharpValidator extends FormRequest
{
    use WithSharpContext;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
        // return Match::rules();
        // return [
        //     'journee_id' => 'required|exists:journees,id',
        //     'terrain_id' => 'nullable|exists:terrains,id',
        //     'user_id' => 'nullable|exists:users,id',
        //     'date' => 'nullable|date|date_format:Y-m-d',
        //     'heure' => 'nullable|string|size:5',
        //     'equipe_id_dom' => ['required','integer','exists:equipes,id'],
        //     'equipe_id_ext' => ['required','integer','exists:equipes,id'],
        //     'score_eq_dom' => 'nullable|integer|min:0|required_with:score_eq_ext',
        //     'score_eq_ext' => 'nullable|integer|min:0|required_with:score_eq_dom',
        //     'acces_bloque' => 'boolean'
        // ];
    }
}

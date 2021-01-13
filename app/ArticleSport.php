<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleSport extends Pivot
{
    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    // protected $fillable = ['article_id', 'sport_id', 'position'];

    public $timestamps = false;

    /**
     * Les règles de validations
     *
     * @param ArticleSport $articleSport
     * @return array
     */
    public static function rules(ArticleSport $articleSport = null, array $ignore = [])
    {
        $unique = Rule::unique('article_sport', 'article_id')->ignore($articleSport);

        $rules = [
            'sport_id' => ['required','integer','min:1','exists:sports,id',$unique],
            'visible' => 'boolean',
            'priorite' => 'nullable|integer|min:1|max:5',
            'article_id' => 'required|exists:articles,id',
        ];

        foreach ($ignore as $key)
            unset($rules[$key]);

        $messages = ['unique' => "Ce sport est déjà lié à l'article."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

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
    public $timestamps = false;

    /**
     * Les règles de validations
     *
     * @param ArticleSport $articleSport
     * @return array
     */
    public static function rules(ArticleSport $articleSport = null)
    {
        $articleId = request()->input('article_id');

        $unique = Rule::unique('article_sport')->where(function ($query) use ($articleId) {
            return $query->whereArticleId($articleId);
        })->ignore($articleSport);

        $rules = [
            'sport_id' => ['required','integer','exists:sports,id',$unique],
            'article_id' => 'required|exists:articles,id',
            'visible' => 'nullable|boolean',
            'priorite' => 'nullable|integer|min:1|max:5',
        ];

        $messages = ['unique' => "Ce sport est déjà lié à l'article."];
        return ['rules' => $rules, 'messages' => $messages];
    }
}

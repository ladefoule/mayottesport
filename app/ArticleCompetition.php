<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

namespace App;

// use Illuminate\Database\Eloquent\Model;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ArticleCompetition extends Pivot
{
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['article_id', 'competition_id'];

    /**
     * Les règles de validations
     *
     * @param ArticleCompetition $articleCompetition
     * @return array
     */
    public static function rules(ArticleCompetition $articleCompetition = null)
    {
        $unique = Rule::unique('article_competition')->where(function ($query) {
            return $query->whereArticleId(request()['article_id'])->whereCompetitionId(request()['competition_id']);
        })->ignore($articleCompetition);

        $rules =[
            'article_id' => ['required','exists:articles,id',$unique],
            'competition_id' => ['required','exists:competitions,id']
        ];
        return ['rules' => $rules];
    }

    /**
     * Définition de l'affichage d'un objet dans le CRUD (back-office)
     *
     * @return string
     */
    public function getCrudNameAttribute()
    {
        return indexCrud('articles')[$this->competition_id]->uniqid . ' - ' . index('competition')[$this->competition_id]->crud_name;
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article()
    {
        return $this->belongsTo('App\Article');
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo('App\Competition');
    }
}

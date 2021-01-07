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

class ArticleSport extends Pivot
{
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['article_id', 'sport_id'];

    /**
     * Les règles de validations
     *
     * @param ArticleSport $articleSport
     * @return array
     */
    public static function rules(ArticleSport $articleSport = null)
    {
        $unique = Rule::unique('article_sport')->where(function ($query) {
            return $query->whereArticleId(request()['article_id'])->whereSportId(request()['sport_id']);
        })->ignore($articleSport);

        $rules =[
            'article_id' => ['required','exists:articles,id',$unique],
            'sport_id' => ['required','exists:sports,id']
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
        return indexCrud('articles')[$this->sport_id]->uniqid . ' - ' . index('sport')[$this->sport_id]->crud_name;
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
    public function sport()
    {
        return $this->belongsTo('App\Sport');
    }
}

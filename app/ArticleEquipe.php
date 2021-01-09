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

class ArticleEquipe extends Pivot
{
    public $timestamps = false;

    /**
     * Champs autorisés lors de la création
     *
     * @var array
     */
    protected $fillable = ['article_id', 'equipe_id'];
}

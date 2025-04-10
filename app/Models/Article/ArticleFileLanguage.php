<?php

namespace App\Models\Article;

use App\Models\Alt\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ArticleFileLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'article_file_languages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * Add a where "article_file_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('article_file_id', $foreignKey);
    }
}

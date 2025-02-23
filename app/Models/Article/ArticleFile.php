<?php

namespace App\Models\Article;

use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\FileTrait;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleFile extends Model implements Fileable
{
    use QueriesTrait, FileTrait, HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'article_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article_id', 'position', 'visible'
    ];

    /**
     * Get the mutated file default attribute.
     *
     * @param  string|null  $value
     * @return string
     */
    public function getFileDefaultAttribute(?string $value): string
    {
        return $value ?: asset('assets/libs/images/image-1.jpg');
    }

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Article\ArticleFileLanguage
     */
    public function languages(bool $relation = true): HasMany|ArticleFileLanguage
    {
        return $relation ? $this->hasMany(ArticleFileLanguage::class) : new ArticleFileLanguage;
    }

    /**
     * Add a where foreign id clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByForeign(Builder $query, int $foreignId): Builder
    {
        return $query->where('article_id', $foreignId);
    }
}

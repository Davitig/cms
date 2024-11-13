<?php

namespace App\Models\Article;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Contracts\Fileable;
use App\Models\Traits\FileTrait;
use App\Models\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ArticleFile extends Model implements Fileable
{
    use FileTrait, HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var null|string
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
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'article_id'
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
     * @param  int  $foreignId
     * @return \App\Models\Base\Builder|static
     */
    public function byForeign(int $foreignId): Builder|static
    {
        return $this->where('article_id', $foreignId);
    }
}

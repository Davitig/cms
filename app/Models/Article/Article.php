<?php

namespace App\Models\Article;

use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\FileableTrait;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use HasCollection, HasLanguage, FileableTrait;

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'articles';

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'position', 'visible', 'image', 'created_at'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Article\ArticleLanguage
     */
    public function languages(bool $relation = true): HasMany|ArticleLanguage
    {
        return $relation ? $this->hasMany(ArticleLanguage::class) : new ArticleLanguage;
    }
}

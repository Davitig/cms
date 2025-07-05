<?php

namespace App\Models\Article;

use App\Models\Alt\Contracts\Collection;
use App\Models\Alt\Traits\FileableTrait;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model implements Collection
{
    use QueriesTrait, HasCollection, HasLanguage, FileableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'visible', 'position', 'image', 'created_at'
    ];

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'articles';

    /**
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(ArticleLanguage::class);
    }
}

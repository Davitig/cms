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
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(ArticleLanguage::class);
    }
}

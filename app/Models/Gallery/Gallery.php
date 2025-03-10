<?php

namespace App\Models\Gallery;

use App\Models\Alt\Contracts\Collection;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model implements Collection
{
    use QueriesTrait, HasCollection, HasLanguage;

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'galleries';

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'galleries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'position', 'visible', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page',
        'web_order_by', 'web_sort', 'web_per_page', 'image'
    ];

    /**
     * Set languages a one-to-many relationship.
     *
     * @param  bool  $relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Gallery\GalleryLanguage
     */
    public function languages(bool $relation = true): HasMany|GalleryLanguage
    {
        return $relation ? $this->hasMany(GalleryLanguage::class) : new GalleryLanguage;
    }

    /**
     * Add a new where 'type' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}

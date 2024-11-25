<?php

namespace App\Models\Gallery;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Contracts\Collection;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gallery extends Model implements Collection
{
    use HasCollection, HasLanguage;

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
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'type'
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
     * @param  string  $type
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function byType(string $type): Builder|static
    {
        return $this->where('type', $type);
    }
}

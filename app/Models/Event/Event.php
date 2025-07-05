<?php

namespace App\Models\Event;

use App\Models\Alt\Contracts\Collection;
use App\Models\Alt\Traits\FileableTrait;
use App\Models\Alt\Traits\HasCollection;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model implements Collection
{
    use QueriesTrait, HasCollection, HasLanguage, FileableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'visible', 'position', 'image'
    ];

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'events';

    /**
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(EventLanguage::class);
    }
}

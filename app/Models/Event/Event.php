<?php

namespace App\Models\Event;

use App\Concerns\Models\ExtendsQueries;
use App\Concerns\Models\Fileable;
use App\Concerns\Models\QueriesLanguageRelationship;
use App\Concerns\Models\QueriesWithCollection;
use App\Contracts\Models\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model implements Collection
{
    use ExtendsQueries, QueriesWithCollection, QueriesLanguageRelationship, Fileable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'visible', 'position', 'image'
    ];

    /**
     * Language one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(EventLanguage::class);
    }
}

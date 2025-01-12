<?php

namespace App\Models\Event;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\FileTrait;
use App\Models\Alt\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventFile extends Model implements Fileable
{
    use FileTrait, HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'event_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'position', 'visible'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'event_id'
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Event\EventFileLanguage
     */
    public function languages(bool $relation = true): HasMany|EventFileLanguage
    {
        return $relation ? $this->hasMany(EventFileLanguage::class) : new EventFileLanguage;
    }

    /**
     * Add a where foreign id clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $foreignId
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByForeign(Builder $query, int $foreignId): Builder
    {
        return $query->where('event_id', $foreignId);
    }
}

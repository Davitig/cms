<?php

namespace App\Models\Event;

use App\Models\Base\Builder;
use App\Models\Base\Model;
use App\Models\Traits\FileTrait;
use App\Models\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventFile extends Model
{
    use FileTrait, HasLanguage;

    /**
     * The table associated with the model.
     *
     * @var null|string
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
     * @param  int  $foreignId
     * @return \App\Models\Base\Builder|static
     */
    public function byForeign(int $foreignId): Builder|static
    {
        return $this->where('event_id', $foreignId);
    }
}

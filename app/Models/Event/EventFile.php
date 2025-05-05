<?php

namespace App\Models\Event;

use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\FileTrait;
use App\Models\Alt\Traits\HasLanguage;
use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventFile extends Model implements Fileable
{
    use QueriesTrait, FileTrait, HasLanguage;

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
     * Languages' one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function languages(): HasMany
    {
        return $this->hasMany(EventFileLanguage::class);
    }

    /**
     * Add a where file foreign key clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFileForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('event_id', $foreignKey);
    }
}

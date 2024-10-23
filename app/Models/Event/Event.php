<?php

namespace App\Models\Event;

use App\Models\Base\Model;
use App\Models\Traits\FileableTrait;
use App\Models\Traits\HasCollection;
use App\Models\Traits\HasLanguage;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasCollection, HasLanguage, FileableTrait;

    /**
     * Type of the collection.
     *
     * @var string
     */
    const TYPE = 'events';

    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'collection_id', 'slug', 'position', 'visible', 'image'
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
    public function languages(bool $relation = true): HasMany|EventLanguage
    {
        return $relation ? $this->hasMany(EventLanguage::class) : new EventLanguage;
    }
}

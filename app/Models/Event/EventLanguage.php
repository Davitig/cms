<?php

namespace App\Models\Event;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use App\Models\Alt\Traits\LanguageTrait;

class EventLanguage extends Model
{
    use LanguageTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'event_languages';

    /**
     * The attributes that are mass assignable for the Language model.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'language_id', 'title', 'short_title', 'description', 'content', 'meta_title', 'meta_desc'
    ];

    /**
     * The attributes that are not updatable for the Language model.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'event_id', 'language_id'
    ];

    /**
     * Add a where "event_id" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForeignId(Builder $query, int $id): Builder
    {
        return $query->where('event_id', $id);
    }
}

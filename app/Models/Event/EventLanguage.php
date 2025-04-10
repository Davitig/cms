<?php

namespace App\Models\Event;

use App\Models\Alt\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 'language_id', 'title', 'description', 'content', 'meta_title', 'meta_desc'
    ];

    /**
     * Add a where "event_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('event_id', $foreignKey);
    }
}

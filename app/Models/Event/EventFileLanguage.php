<?php

namespace App\Models\Event;

use App\Models\Alt\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class EventFileLanguage extends Model
{
    use LanguageTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_file_id', 'language_id', 'title', 'file'
    ];

    /**
     * Add a where "event_file_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLanguageForeignKey(Builder $query, int $foreignKey): Builder
    {
        return $query->where('event_file_id', $foreignKey);
    }
}

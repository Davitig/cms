<?php

namespace App\Models;

use App\Models\Alt\Traits\PositionableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use PositionableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'language', 'visible', 'position', 'main', 'short_name', 'full_name'
    ];

    /**
     * Add a where "language" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $language
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereLanguage(Builder $query, string $language): Builder
    {
        return $query->where('language', $language);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereVisible(Builder $query, int $value = 1): Builder
    {
        return $query->where('visible', $value);
    }
}

<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;

class Collection extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'collections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'title', 'type', 'admin_order_by', 'admin_sort', 'admin_per_page',
        'web_order_by', 'web_sort', 'web_per_page', 'description'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [
        'type'
    ];

    /**
     * Add a new where 'type' clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $type
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }
}

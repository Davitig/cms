<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;

class Note extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_id', 'title', 'description', 'content'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Add a where 'cms_user_id' clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('cms_user_id', $userId);
    }
}

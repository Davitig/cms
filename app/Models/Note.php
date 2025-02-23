<?php

namespace App\Models;

use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use QueriesTrait;

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
     * Add a where 'cms_user_id' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByUserId(Builder $query, int $userId): Builder
    {
        return $query->where('cms_user_id', $userId);
    }
}

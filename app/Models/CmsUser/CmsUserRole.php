<?php

namespace App\Models\CmsUser;

use App\Models\Alt\Traits\QueriesTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsUserRole extends Model
{
    use QueriesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'full_access'
    ];

    /**
     * Add a where 'full_access' to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool|int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFullAccess(Builder $query, bool|int $value = 1): Builder
    {
        return $query->where('full_access', (int) $value);
    }

    /**
     * Add a where not 'full_access' to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomAccess(Builder $query): Builder
    {
        return $query->where('full_access', 0);
    }
}

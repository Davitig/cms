<?php

namespace App\Models\CmsUser;

use App\Concerns\Models\ExtendsQueries;
use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsUserRole extends Model
{
    use ExtendsQueries;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'full_access'
    ];

    /**
     * CmsUser one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cmsUsers(): HasMany
    {
        return $this->hasMany(CmsUser::class);
    }

    /**
     * Permission one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

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

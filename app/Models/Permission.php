<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_role_id', 'route_name'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Hidden route group/resource names.
     *
     * @var array
     */
    public static array $routeGroupsHidden = [
        'cmsUserRoles', 'permissions', 'webSettings', 'translations'
    ];

    /**
     * Hidden route names.
     *
     * @var array
     */
    public static array $routeNamesHidden = [];

    /**
     * Allowed route group/resource names.
     *
     * @var array
     */
    public static array $routeGroupsAllowed = [
        'login', 'logout', 'lockscreen', 'cmsUsers', 'cmsSettings'
    ];

    /**
     * Allowed route names.
     *
     * @var array
     */
    public static array $routeNamesAllowed = [
        'dashboard'
    ];

    /**
     * Add a where 'cms_user_role_id' clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $value
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeRoleId(Builder $query, int $value): Builder
    {
        return $query->where('cms_user_role_id', $value);
    }

    /**
     * Add a where 'route_name' clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $routeName
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeRouteName(Builder $query, string $routeName): Builder
    {
        return $query->where('route_name', $routeName);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $routeName
     * @return bool
     */
    public function scopeHasAccess(Builder $query, string $routeName): bool
    {
        return $query->routeName($routeName)->exists();
    }

    /**
     * Clear permissions from the database.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $roleId
     * @return mixed
     */
    public function scopeClear(Builder $query, int $roleId): mixed
    {
        return $query->roleId($roleId)->delete();
    }
}

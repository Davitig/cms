<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cms_user_role_id', 'route_name'
    ];

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
        'login', 'logout', 'dashboard', 'cmsUsers', 'cmsSettings'
    ];

    /**
     * Allowed route names.
     *
     * @var array
     */
    public static array $routeNamesAllowed = [];

    /**
     * Add a where 'cms_user_role_id' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoleId(Builder $query, int $value): Builder
    {
        return $query->where('cms_user_role_id', $value);
    }

    /**
     * Add a where 'route_name' clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $routeName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRouteName(Builder $query, string $routeName): Builder
    {
        return $query->where('route_name', $routeName);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $roleId
     * @return mixed
     */
    public function scopeClear(Builder $query, int $roleId): mixed
    {
        return $query->roleId($roleId)->delete();
    }
}

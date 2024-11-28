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
        'login', 'logout', 'lockscreen', 'cmsUsers', 'settings'
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
     * @param  int  $value
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function roleId(int $value): Builder|static
    {
        return $this->where('cms_user_role_id', $value);
    }

    /**
     * Add a where 'route_name' clause to the query.
     *
     * @param  string  $routeName
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function routeName(string $routeName): Builder|static
    {
        return $this->where('route_name', $routeName);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  string  $routeName
     * @return bool
     */
    public function hasAccess(string $routeName): bool
    {
        return $this->routeName($routeName)->exists();
    }

    /**
     * Clear permissions from the database.
     *
     * @param  int  $roleId
     * @return mixed
     */
    public function clear(int $roleId): mixed
    {
        return $this->roleId($roleId)->delete();
    }
}

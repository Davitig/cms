<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role', 'route_name'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Route group names that are hidden from a list.
     *
     * @var array
     */
    public static array $routeGroupsHidden = ['cmsUsers', 'permissions', 'login', 'logout', 'lockscreen'];

    /**
     * Route names that are hidden from a list.
     *
     * @var array
     */
    public static array $routeNamesHidden = ['login', 'logout', 'lockscreen'];

    /**
     * Route names that are not allowed.
     *
     * @var array
     */
    public static array $routeNamesAllowed = ['cmsUsers.index', 'cmsUsers.edit', 'cmsUsers.show', 'cmsUsers.update'];

    /**
     * Get the list of permissions by user id.
     *
     * @param  string  $value
     * @return \App\Models\Base\Builder|static
     */
    public function role(string $value): Builder|static
    {
        return $this->where('role', $value);
    }

    /**
     * Determine if the user has access to the given route.
     *
     * @param  string  $routeName
     * @return bool
     */
    public function hasAccess(string $routeName): bool
    {
        return $this->where('route_name', $routeName)->exists();
    }

    /**
     * Clear permissions from the database.
     *
     * @param  string  $value
     * @return mixed
     */
    public function clear(string $value): mixed
    {
        return $this->role($value)->delete();
    }
}

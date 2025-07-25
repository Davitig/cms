<?php

namespace App\Models\CmsUser;

use App\Concerns\Models\ExtendsQueries;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CmsUser extends Model implements AuthenticatableContract
{
    use ExtendsQueries, Authenticatable {
        getAuthIdentifierName as baseAuthIdentifierName;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'cms_user_role_id', 'first_name', 'last_name', 'phone', 'address',
        'suspended', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifierName()
    {
        return $this->qualifyColumn($this->baseAuthIdentifierName());
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->baseAuthIdentifierName()};
    }

    /**
     * Determine if the user has full access.
     *
     * @return bool
     */
    public function hasFullAccess(): bool
    {
        return (bool) $this->full_access;
    }

    /**
     * Determine if the user has custom access.
     *
     * @return bool
     */
    public function hasCustomAccess(): bool
    {
        return ! $this->hasFullAccess();
    }

    /**
     * Add a where "cms_user_role_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $roleId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoleId(Builder $query, int $roleId): Builder
    {
        return $query->where('cms_user_role_id', $roleId);
    }

    /**
     * Add a 'cms_user_roles' join to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinRole(Builder $query): Builder
    {
        return $query->leftJoin(
            'cms_user_roles', 'cms_user_roles.id', 'cms_users.cms_user_role_id'
        )->addSelect(['role', 'full_access', 'cms_users.*']);
    }
}

<?php

namespace App\Models;

use App\Models\Alt\Traits\ModelBuilderTrait;
use App\Models\Alt\User\User as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Exceptions\HttpResponseException;

class CmsUser extends Model
{
    use ModelBuilderTrait;

    /**
     * The table associated with the model.
     *
     * @var string|null
     */
    protected $table = 'cms_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'cms_user_role_id', 'first_name', 'last_name', 'phone', 'address',
        'blocked', 'password'
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
     * Set the lockscreen.
     *
     * @param  bool  $forceLock
     * @return void
     */
    public function lockScreen(bool $forceLock = false): void
    {
        session()->put('lockscreen', 1);

        if ($forceLock) {
            throw new HttpResponseException(redirect(cms_route('lockscreen')));
        }
    }

    /**
     * Determine if the screen is locked.
     *
     * @return bool
     */
    public function hasLockScreen(): bool
    {
        return session()->has('lockscreen');
    }

    /**
     * Remove the lockscreen.
     *
     * @return mixed
     */
    public function unlockScreen(): mixed
    {
        return session()->remove('lockscreen');
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

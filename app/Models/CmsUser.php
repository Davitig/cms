<?php

namespace App\Models;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Alt\User\User as Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CmsUser extends Model
{
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
        'email', 'cms_user_role_id', 'first_name', 'last_name', 'phone', 'address', 'blocked', 'photo', 'password'
    ];

    /**
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

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
     * Get the mutated attribute.
     *
     * @param  string|null  $value
     * @return string
     */
    public function getPhotoAttribute(?string $value): string
    {
        return $value ?: asset('assets/libs/images/user-2.png');
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
     * @param  int  $roleId
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function roleId(int $roleId): Builder|static
    {
        return $this->where('cms_user_role_id', $roleId);
    }

    /**
     * Add a 'cms_user_roles' join to the query.
     *
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function joinRole(): Builder|static
    {
        return $this->leftJoin(
            'cms_user_roles', 'cms_user_roles.id', 'cms_users.cms_user_role_id'
        )->addSelect(['role', 'full_access', 'cms_users.*']);
    }

    /**
     * Filter a query by specific parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function adminFilter(Request $request): Builder
    {
        $blockedValue = (int) $request->boolean('blocked');

        return $this->when($request->get('name'), function ($q, $value) {
            return $q->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$value}%"]);
        })->when($request->get('email'), function ($q, $value) {
            return $q->where('email', 'like', "%{$value}%");
        })->when($request->get('role'), function ($q, $value) {
            return $q->where('cms_user_role_id', $value);
        })->when($request->filled('blocked'), function ($q) use ($blockedValue) {
            return $q->where('blocked', $blockedValue);
        });
    }
}

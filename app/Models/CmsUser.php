<?php

namespace App\Models;

use App\Models\Base\Builder;
use App\Models\Base\User as Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

class CmsUser extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'cms_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'phone', 'address', 'role', 'blocked', 'photo', 'password'
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
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];

    /**
     * Get the mutated attribute.
     *
     * @return string|null
     */
    public function getRoleTextAttribute(): ?string
    {
        return ! is_null($this->role) ? user_roles($this->role) : $this->role;
    }

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
     * Determine if the user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->role == 'admin';
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
     * Filter a query by specific parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Base\Builder
     */
    public function adminFilter(Request $request): Builder
    {
        return $this->when($request->get('name'), function ($q, $value) {
            return $q->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$value}%"]);
        })->when($request->get('email'), function ($q, $value) {
            return $q->where('email', 'like', "%{$value}%");
        })->when($request->get('role'), function ($q, $value) {
            return $q->where('role', $value);
        })->when(! is_null($value = $request->get('blocked')), function ($q) use ($value) {
            return $q->when($value, function ($q) {
                return $q->where('blocked', 1);
            }, function ($q) {
                return $q->where('blocked', 0);
            });
        });
    }
}

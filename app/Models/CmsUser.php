<?php

namespace App\Models;

use App\Models\Base\User as Model;
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
    protected $notUpdatable = [];

    /**
     * Get the mutated attribute.
     *
     * @return string
     */
    public function getRoleTextAttribute()
    {
        return (! is_null($this->role)) ? user_roles($this->role) : $this->role;
    }

    /**
     * Get the mutated attribute.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoAttribute($value)
    {
        return $value ?: asset('assets/libs/images/user-2.png');
    }

    /**
     * Determine if the user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role == 'admin';
    }

    /**
     * Set the lockscreen.
     *
     * @param  bool  $forceLock
     * @return \Illuminate\Session\Store
     */
    public function lockScreen($forceLock = false)
    {
        $lockscreen = session()->put('lockscreen', 1);

        if ($forceLock) {
            throw new HttpResponseException(redirect(cms_route('lockscreen')));
        }

        return $lockscreen;
    }

    /**
     * Determine if screen is locked.
     *
     * @return \Illuminate\Session\Store
     */
    public function hasLockScreen()
    {
        return session()->has('lockscreen');
    }

    /**
     * Remove the lockscreen.
     *
     * @return \Illuminate\Session\Store
     */
    public function unlockScreen()
    {
        return session()->remove('lockscreen');
    }

    /**
     * Filter a query by specific parameters.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\Eloquent\Builder
     */
    public function adminFilter(Request $request)
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

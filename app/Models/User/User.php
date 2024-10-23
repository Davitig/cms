<?php

namespace App\Models\User;

use App\Models\Base\User as Model;

class User extends Model
{
    /**
     * The table associated with the model.
     *
     * @var null|string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'first_name', 'last_name', 'phone', 'address', 'blocked', 'password'
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
     * The attributes that are not updatable.
     *
     * @var array
     */
    protected array $notUpdatable = [];
}

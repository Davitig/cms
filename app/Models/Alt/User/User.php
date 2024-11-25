<?php

namespace App\Models\Alt\User;

use App\Models\Alt\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

abstract class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
}

<?php

namespace App\Models\Base;

use App\Models\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

abstract class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
}

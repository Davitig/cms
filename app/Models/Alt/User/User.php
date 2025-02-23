<?php

namespace App\Models\Alt\User;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;

abstract class User extends Model implements AuthenticatableContract
{
    use Authenticatable {
        getAuthIdentifierName as getAuthIdentifierNameTrait;
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifierName()
    {
        return $this->qualifyColumn($this->getAuthIdentifierNameTrait());
    }

    /**
     * {@inheritDoc}
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierNameTrait()};
    }
}

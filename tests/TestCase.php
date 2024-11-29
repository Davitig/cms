<?php

namespace Tests;

use App\Models\CmsUser;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getUser(): CmsUser
    {
        return (new CmsUser)->joinRole()->firstOrFail();
    }
}

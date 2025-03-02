<?php

namespace Tests;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! (new CmsUser)->whereEmail('test-full-access@example.com')->exists()) {
            if (! $roleId = (new CmsUserRole)->fullAccess()->value('id')) {
                $roleId = (new CmsUser)->create([
                    'role' => 'Test Full Access',
                    'full_access' => 1
                ])->id;
            }

            (new CmsUser)->create([
                'email' => 'test-full-access@example.com',
                'cms_user_role_id' => $roleId,
                'first_name' => 'Test',
                'last_name' => 'Test',
                'password' => bcrypt('password')
            ]);
        }

        if (! (new CmsUser)->whereEmail('test-custom-access@example.com')->exists()) {
            if (! $roleId = (new CmsUserRole)->customAccess()->value('id')) {
                $roleId = (new CmsUser)->create([
                    'role' => 'Test Custom Access',
                    'full_access' => 0
                ])->id;
            }

            (new CmsUser)->create([
                'email' => 'test-custom-access@example.com',
                'cms_user_role_id' => $roleId,
                'first_name' => 'Test',
                'last_name' => 'Test',
                'password' => bcrypt('password')
            ]);
        }
    }

    protected function getFullAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->whereEmail('test-full-access@example.com')
            ->joinRole()
            ->firstOrFail();
    }

    protected function getCustomAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->whereEmail('test-custom-access@example.com')
            ->joinRole()
            ->firstOrFail();
    }
}

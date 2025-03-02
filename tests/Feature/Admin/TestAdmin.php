<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser;
use App\Models\CmsUserRole;
use InvalidArgumentException;
use Tests\TestCase;

abstract class TestAdmin extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']['_cms.activated'] = true;

        $this->createCmsUser('full');

        $this->createCmsUser('custom');
    }

    protected function getFullAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->whereEmail('full-access-test@example.com')
            ->joinRole()
            ->firstOrFail();
    }

    protected function getCustomAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->whereEmail('custom-access-test@example.com')
            ->joinRole()
            ->firstOrFail();
    }

    protected function createCmsUser(string $access): CmsUser
    {
        if (! in_array($access, ['full', 'custom'])) {
            throw new InvalidArgumentException('Invalid access value');
        }

        $model = (new CmsUser)->whereEmail($access . '-access-test@example.com')->first();

        if (is_null($model)) {
            if (! $roleId = (new CmsUserRole)->when(
                $access == 'full', fn ($q) => $q->fullAccess(), fn ($q) => $q->customAccess()
            )->value('id')) {
                $roleId = (new CmsUserRole)->create([
                    'role' => $access . ' access test',
                    'full_access' => (int) ($access == 'full')
                ])->id;
            }

            return (new CmsUser)->create([
                'email' => $access . '-access-test@example.com',
                'cms_user_role_id' => $roleId,
                'first_name' => 'Test',
                'last_name' => 'Test',
                'password' => bcrypt('password')
            ]);
        }

        return $model;
    }
}

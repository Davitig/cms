<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser\CmsUser;
use App\Models\CmsUser\CmsUserRole;
use Database\Factories\CmsUserFactory;
use Database\Factories\CmsUserRoleFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

abstract class TestAdmin extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        DB::update('ALTER TABLE cms_user_roles AUTO_INCREMENT = 1');
        DB::update('ALTER TABLE cms_users AUTO_INCREMENT = 1');

        $this->app['config']['_cms.booted'] = true;

        $this->createCmsUser();

        $this->createCmsUser(false);
    }

    protected function tearDown(): void
    {
        (new CmsUser)->newQuery()->delete();
        (new CmsUserRole)->newQuery()->delete();

        parent::tearDown();
    }

    protected function getFullAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->roleId((new CmsUserRole)->fullAccess()->valueOrFail('id'))
            ->joinRole()
            ->firstOrFail();
    }

    protected function getCustomAccessCmsUser(): CmsUser
    {
        return (new CmsUser)->roleId((new CmsUserRole)->customAccess()->valueOrFail('id'))
            ->joinRole()
            ->firstOrFail();
    }

    protected function createCmsUser(bool $fullAccess = true, ?int $times = null): CmsUser|Collection
    {
        if (! $roleId = (new CmsUserRole)->when(
            $fullAccess, fn ($q) => $q->fullAccess(), fn ($q) => $q->customAccess()
        )->value('id')) {
            $roleId = CmsUserRoleFactory::new()->fullAccess($fullAccess)->create()->id;
        }

        return CmsUserFactory::new()->count($times)->role($roleId)->create();
    }
}

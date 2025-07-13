<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUser\CmsUser;
use App\Models\CmsUser\CmsUserRole;
use Database\Factories\CmsUserFactory;
use Database\Factories\CmsUserRoleFactory;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

abstract class TestAdmin extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']['_cms.booted'] = true;

        $this->createCmsUser();

        $this->createCmsUser(false);
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

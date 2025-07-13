<?php

namespace Tests\Feature\Admin\Resources\CmsUser;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Feature\Admin\TestAdmin;
use Tests\Feature\InteractsWithRouter;

class AdminPermissionTest extends TestAdmin
{
    use RefreshDatabase, InteractsWithRouter;

    public function test_admin_permissions_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get(cms_route('permissions.index'));

        $response->assertOk();
    }

    public function test_admin_permissions_store()
    {
        $model = $this->getCustomAccessCmsUser();

        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post(cms_route('permissions.index', [
            'role_id' => $model->cms_user_role_id,
            'permissions' => [current(Arr::flatten($this->getAllCMSRouteNames()))]
        ]));

        $response->assertFound()->assertSessionHas('alert', function (array $value) {
            return isset($value['result']) && $value['result'] == 'success';
        });
    }
}

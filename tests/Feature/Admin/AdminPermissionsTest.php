<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Arr;

class AdminPermissionsTest extends TestAdmin
{
    use AdminRoutesTrait;

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

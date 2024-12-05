<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUserRole;
use Illuminate\Support\Arr;
use Tests\TestCase;

class AdminPermissionsTest extends TestCase
{
    use AdminRoutesTrait;

    public function test_admin_permissions_index()
    {
        $response = $this->actingAs($this->getUser())->get(cms_route('permissions.index'));

        $response->assertOk();
    }

    public function test_admin_permissions_store()
    {
        $response = $this->actingAs($this->getUser())->post(cms_route('permissions.index', [
            'role_id' => (new CmsUserRole)->customAccess()->valueOrFail('id'),
            'permissions' => [current(Arr::flatten($this->getAllCMSRouteNames()))]
        ]));

        $response->assertFound()->assertSessionHas('alert', function (array $value) {
            return isset($value['result']) && $value['result'] == 'success';
        });
    }
}

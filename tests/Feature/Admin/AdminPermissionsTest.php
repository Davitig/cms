<?php

namespace Tests\Feature\Admin;

use App\Models\CmsUserRole;
use Exception;
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

    /**
     * @throws \Exception
     */
    public function test_admin_permissions_store()
    {
        if (is_null($roleId = (new CmsUserRole)->customAccess()->valueOrFail('id'))) {
            throw new Exception('User role with custom access not found');
        }

        $response = $this->actingAs($this->getUser())->post(cms_route('permissions.index', [
            'role_id' => $roleId,
            'permissions' => [current(Arr::flatten($this->getAllCMSRouteNames()))]
        ]));

        $response->assertFound()->assertSessionHas('alert', function (array $value) {
            return isset($value['result']) && $value['result'] == 'success';
        });
    }
}

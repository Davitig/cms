<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\Setting\AdminSettingCacheController;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Mockery\ExpectationInterface;

class AdminSettingCacheTest extends TestAdmin
{
    use RefreshDatabase;

    public function test_admin_settings_index()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->get($this->cmsRoute('settings.cache.index'));

        $response->assertOk();
    }

    public function test_admin_settings_execute_view_cache_clear_command()
    {
        $response = $this->actingAs(
            $this->getFullAccessCmsUser(), 'cms'
        )->post($this->cmsRoute('settings.cache.view_clear'));

        $response->assertFound();
    }

    public function test_admin_settings_execute_config_cache_command()
    {
        $mock = $this->mock(Application::class);

        $mock->expects('configurationIsCached')->once()->andReturn(false);

        $this->settingCacheCommand('updateConfig', 'config:cache', $mock);
    }

    public function test_admin_settings_execute_config_cache_clear_command()
    {
        $mock = $this->mock(Application::class);

        $mock->expects('configurationIsCached')->once()->andReturn(true);

        $this->settingCacheCommand('updateConfig', 'config:clear', $mock);
    }

    public function test_admin_settings_execute_route_cache_command()
    {
        $mock = $this->mock(Application::class);

        $mock->expects('routesAreCached')->once()->andReturn(false);

        $this->settingCacheCommand('updateRoutes', 'route:cache', $mock);
    }

    public function test_admin_settings_execute_route_cache_clear_command()
    {
        $mock = $this->mock(Application::class);

        $mock->expects('routesAreCached')->once()->andReturn(true);

        $this->settingCacheCommand('updateRoutes', 'route:clear', $mock);
    }

    protected function settingCacheCommand(string $method, string $command, mixed $appMock): void
    {
        $controller = $this->app->make(AdminSettingCacheController::class);

        $kernelMock = $this->mock(Kernel::class);

        $kernelMock->expects('call')->once()->withSomeOfArgs($command);

        $this->assertInstanceOf(
            RedirectResponse::class, $controller->$method($appMock, $kernelMock)
        );
    }
}

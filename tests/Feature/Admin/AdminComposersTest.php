<?php

namespace Tests\Feature\Admin;

use App\View\Composers\Admin\AdminCalendarComposer;
use App\View\Composers\Admin\AdminCmsSettingsComposer;
use App\View\Composers\Admin\AdminMenuComposer;
use App\View\Composers\Admin\AdminRouteMatchesComposer;
use App\View\Composers\Admin\AdminSitemapXmlComposer;
use App\View\Composers\Admin\AdminUserRouteAccessComposer;
use Illuminate\Contracts\View\View;
use Tests\TestCase;

class AdminComposersTest extends TestCase
{
    protected function testAdminComposer(string $composerClass, ...$someOfArgs)
    {
        $composer = $this->app->make($composerClass);

        $mock = $this->mock(View::class);

        $mock->shouldReceive('with')->once()->withSomeOfArgs(...$someOfArgs);

        $composer->compose($mock);
    }

    public function test_admin_cms_settings_composer()
    {
        $this->testAdminComposer(AdminCmsSettingsComposer::class, 'cmsSettings');
    }

    public function test_admin_menus_composer()
    {
        $this->testAdminComposer(AdminMenuComposer::class, 'menus');
    }

    public function test_admin_calendar_composer()
    {
        $this->testAdminComposer(AdminCalendarComposer::class, 'calendarEvents');
    }

    public function test_admin_route_matcher_composer()
    {
        $this->testAdminComposer(AdminRouteMatchesComposer::class, 'routeMatches');
    }

    public function test_admin_route_access_composer()
    {
        $this->testAdminComposer(AdminUserRouteAccessComposer::class, 'userRouteAccess');
    }

    public function test_admin_sitemap_xml_composer()
    {
        $this->testAdminComposer(AdminSitemapXmlComposer::class, 'sitemapXmlTime');
    }
}

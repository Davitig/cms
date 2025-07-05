<?php

namespace Tests\Feature\Admin;

use App\View\Composers\Admin\AdminCmsUserPreferencesComposer;
use App\View\Composers\Admin\AdminMenuComposer;
use App\View\Composers\Admin\AdminRouteMatchesComposer;
use App\View\Composers\Admin\AdminSitemapXmlComposer;
use App\View\Composers\Admin\AdminUserRouteAccessComposer;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Route;

class AdminComposerTest extends TestAdmin
{
    protected function testComposer(string|object $composer, ...$someOfArgs): void
    {
        if (is_string($composer)) {
            $composer = $this->app->make($composer);
        }

        $mock = $this->mock(View::class);

        $mock->expects('with')->once()->withSomeOfArgs(...$someOfArgs);

        $composer->compose($mock);
    }

    public function test_admin_cms_user_preferences_composer()
    {
        $this->testComposer(AdminCmsUserPreferencesComposer::class, 'preferences');
    }

    public function test_admin_menus_composer()
    {
        $this->testComposer(AdminMenuComposer::class, 'menus');
    }

    public function test_admin_route_matcher_composer()
    {
        $this->testComposer(new AdminRouteMatchesComposer(
            (new Route(['GET'], '/', fn () => null))->bind($this->app['request'])
        ), 'routeMatches');
    }

    public function test_admin_route_access_composer()
    {
        $this->testComposer(AdminUserRouteAccessComposer::class, 'userRouteAccess');
    }

    public function test_admin_sitemap_xml_composer()
    {
        $this->testComposer(AdminSitemapXmlComposer::class, 'sitemapXmlTime');
    }
}

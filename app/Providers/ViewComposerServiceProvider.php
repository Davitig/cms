<?php

namespace App\Providers;

use App\View\Composers\Admin\AdminCalendarComposer;
use App\View\Composers\Admin\AdminCmsSettingsComposer;
use App\View\Composers\Admin\AdminMenuComposer;
use App\View\Composers\Admin\AdminRouteMatchesComposer;
use App\View\Composers\Admin\AdminSitemapXmlComposer;
use App\View\Composers\Admin\AdminUserRouteAccessComposer;
use App\View\Composers\Web\WebBreadcrumbComposer;
use App\View\Composers\Web\WebCurrentDataComposer;
use App\View\Composers\Web\WebPagesComposer;
use App\View\Composers\Web\WebSettingsComposer;
use App\View\Composers\Web\WebTranslationsComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * All the singletons that should be registered
     *
     * @property array<array-key, string>.
     */
    public $singletons = [
        // cms
        AdminMenuComposer::class,
        AdminCmsSettingsComposer::class,
        AdminCalendarComposer::class,
        AdminRouteMatchesComposer::class,
        AdminUserRouteAccessComposer::class,
        AdminSitemapXmlComposer::class,
        // web
        WebSettingsComposer::class,
        WebTranslationsComposer::class,
        WebCurrentDataComposer::class,
        WebPagesComposer::class,
        WebBreadcrumbComposer::class
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerAdminViewComposers();

        $this->registerWebViewComposers();
    }

    /**
     * Register an admin view composer events.
     *
     * @return void
     */
    protected function registerAdminViewComposers(): void
    {
        // compose CMS settings
        View::composer('admin.*', AdminCmsSettingsComposer::class);

        // compose menus
        View::composer([
            'admin._partials.sidebar_menu',
            'admin._partials.horizontal_menu',
            'admin.menus.index',
            'admin.pages.index'
        ], AdminMenuComposer::class);

        // compose calendar
        View::composer([
            'admin._partials.user',
            'admin._partials.horizontal_menu',
            'admin.dashboard.index'
        ], AdminCalendarComposer::class);

        // compose route matcher
        View::composer('admin._partials.menu', AdminRouteMatchesComposer::class);

        // compose user route access
        View::composer('admin.*', AdminUserRouteAccessComposer::class);

        // compose sitemap xml
        View::composer([
            'admin._partials.user',
            'admin._partials.horizontal_menu'
        ], AdminSitemapXmlComposer::class);
    }

    /**
     * Register a web view composer events.
     *
     * @return void
     */
    protected function registerWebViewComposers(): void
    {
        // compose settings
        View::composer('web.*', WebSettingsComposer::class);

        // compose translations
        View::composer('web.*', WebTranslationsComposer::class);

        // compose current data
        View::composer('web.*', WebCurrentDataComposer::class);

        // compose pages
        View::composer('web._partials.pages', WebPagesComposer::class);

        // compose breadcrumb
        View::composer('web._partials.breadcrumb', WebBreadcrumbComposer::class);
    }
}

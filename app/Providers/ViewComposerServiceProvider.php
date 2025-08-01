<?php

namespace App\Providers;

use App\View\Composers\Admin\AdminCmsUserPreferencesComposer;
use App\View\Composers\Admin\AdminMenuComposer;
use App\View\Composers\Admin\AdminRouteMatchesComposer;
use App\View\Composers\Admin\AdminSitemapXmlComposer;
use App\View\Composers\Admin\AdminUserRouteAccessComposer;
use App\View\Composers\Web\ContactSettingComposer;
use App\View\Composers\Web\WebBreadcrumbComposer;
use App\View\Composers\Web\WebCurrentDataComposer;
use App\View\Composers\Web\WebPageComposer;
use App\View\Composers\Web\WebTranslationComposer;
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
        AdminCmsUserPreferencesComposer::class,
        AdminRouteMatchesComposer::class,
        AdminUserRouteAccessComposer::class,
        AdminSitemapXmlComposer::class,
        // web
        ContactSettingComposer::class,
        WebTranslationComposer::class,
        WebCurrentDataComposer::class,
        WebPageComposer::class,
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
        View::composer([
            'admin.*', 'errors.admin.layout'
        ], AdminCmsUserPreferencesComposer::class);

        // compose menus
        View::composer([
            'admin.-partials.menu',
            'admin.-partials.navbar',
        ], AdminMenuComposer::class);

        // compose route matcher
        View::composer([
            'admin.-partials.menu', 'admin.cms-users.-partials.navbar', 'errors.admin.layout'
        ], AdminRouteMatchesComposer::class);

        // compose user route access
        View::composer([
            'admin.*', 'errors.admin.layout'
        ], AdminUserRouteAccessComposer::class);

        // compose sitemap xml
        View::composer(['admin.-partials.navbar'], AdminSitemapXmlComposer::class);
    }

    /**
     * Register a web view composer events.
     *
     * @return void
     */
    protected function registerWebViewComposers(): void
    {
        // compose settings
        View::composer('web.*', ContactSettingComposer::class);

        // compose translations
        View::composer('web.*', WebTranslationComposer::class);

        // compose current data
        View::composer('web.-partials.head', WebCurrentDataComposer::class);

        // compose pages
        View::composer('web.-partials.pages', WebPageComposer::class);

        // compose breadcrumb
        View::composer('web.-partials.breadcrumb', WebBreadcrumbComposer::class);
    }
}

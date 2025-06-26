<?php

use App\Http\Controllers\Admin\AdminArticlesController;
use App\Http\Controllers\Admin\AdminCmsSettingsController;
use App\Http\Controllers\Admin\AdminCollectionsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventsController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminFileManagerController;
use App\Http\Controllers\Admin\AdminLanguagesController;
use App\Http\Controllers\Admin\AdminMenusController;
use App\Http\Controllers\Admin\AdminPagesController;
use App\Http\Controllers\Admin\AdminPermissionsController;
use App\Http\Controllers\Admin\AdminProductsController;
use App\Http\Controllers\Admin\AdminSitemapXmlController;
use App\Http\Controllers\Admin\AdminTranslationsController;
use App\Http\Controllers\Admin\AdminWebSettingsController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserRoleController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserSecurityController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Authentication
Route::controller(AdminLoginController::class)->group(function (Router $router) {
    // login
    $router->middleware('cms.guest')->group(function (Router $router) {
        $router->get('login', 'showLoginForm')->name('login');
        $router->post('login', 'login')->name('login.post');
    });

    // logout
    $router->post('logout', 'logout')->name('logout');
});

// Authenticated
Route::middleware('cms.auth')->group(function (Router $router) {
    // dashboard
    $router->get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // languages
    $router->controller(AdminLanguagesController::class)->group(function (Router $router) {
        $router->put('languages/update-main', 'updateMain')
            ->name('languages.updateMain');
        $router->put('languages/{id}/visibility', 'visibility')
            ->name('languages.visibility');
        $router->put('languages/position',  'updatePosition')
            ->name('languages.updatePosition');
        $router->resource('languages', AdminLanguagesController::class)
            ->names(resource_names('languages'))
            ->except(['show']);
    });

    // menus
    $router->put('menus/update-main', [AdminMenusController::class, 'updateMain'])
        ->name('menus.updateMain');
    $router->resource('menus', AdminMenusController::class)
        ->names(resource_names('menus'))
        ->except(['show']);

    // pages
    $router->controller(AdminPagesController::class)->group(function (Router $router) {
        $router->get('pages/listable-types', 'getListableTypes')
            ->name('pages.getListableTypes');
        $router->put('pages/{id}/visibility', 'visibility')->name('pages.visibility');
        $router->put('pages/position', 'updatePosition')->name('pages.updatePosition');
        $router->put('pages/transfer/{menu}', 'transfer')->name('pages.transfer');
        $router->put('pages/collapse', 'collapse')->name('pages.collapse');
        $router->resource('menus.pages', AdminPagesController::class)
            ->names(resource_names('pages'))
            ->except(['show']);
    });

    // products
    $router->controller(AdminProductsController::class)->group(function (Router $router) {
        $router->put('products/{id}/visibility', 'visibility')->name('products.visibility');
        $router->put('products/position', 'updatePosition')->name('products.updatePosition');
        $router->resource('products', AdminProductsController::class)
            ->names(resource_names('products'))
            ->except(['show']);
    });

    // collections
    $router->resource('collections', AdminCollectionsController::class)
        ->names(resource_names('collections'))
        ->except(['show']);

    // collection articles
    $router->controller(AdminArticlesController::class)->group(function (Router $router) {
        $router->put('articles/{id}/visibility', 'visibility')->name('articles.visibility');
        $router->put('articles/position', 'updatePosition')->name('articles.updatePosition');
        $router->put('articles/transfer/{collection}', 'transfer')->name('articles.transfer');
        $router->resource('collections.articles', AdminArticlesController::class)
            ->names(resource_names('articles'))
            ->except(['show']);
    });

    // collection events
    $router->controller(AdminEventsController::class)->group(function (Router $router) {
        $router->put('events/{id}/visibility', 'visibility')->name('events.visibility');
        $router->put('events/position', 'updatePosition')->name('events.updatePosition');
        $router->put('events/transfer/{collection}', 'transfer')->name('events.transfer');
        $router->resource('collections.events', AdminEventsController::class)
            ->names(resource_names('events'))
            ->except(['show']);
    });

    // collection faq
    $router->controller(AdminFaqController::class)->group(function (Router $router) {
        $router->put('faq/{id}/visibility', 'visibility')->name('faq.visibility');
        $router->put('faq/position', 'updatePosition')->name('faq.updatePosition');
        $router->put('faq/transfer/{collection}', 'transfer')->name('faq.transfer');
        $router->resource('collections.faq', AdminFaqController::class)
            ->names(resource_names('faq'))
            ->except(['show']);
    });

    // file routes from config
    foreach ((array) cms_config('file_routes') as $route => $controller) {
        $router->put($route . '/files/{id}/visibility', [$controller, 'visibility'])
            ->name($route . '.files.visibility');
        $router->put($route . '/files/position/update', [$controller, 'updatePosition'])
            ->name($route . '.files.updatePosition');
        $router->delete($route . '/{product}/files', [$controller, 'destroyMany'])
            ->name($route . '.files.destroyMany');
        $router->resource($route . '.files', $controller)
            ->names(resource_names($route . '.files'))
            ->except(['show']);
    }

    // CMS user roles
    $router->resource('cms-user-roles', AdminCmsUserRoleController::class)
        ->names(resource_names('cmsUserRoles'))
        ->except(['show']);

    // role permissions
    $router->get('permissions', [AdminPermissionsController::class, 'index'])
        ->name('permissions.index');
    $router->post('permissions', [AdminPermissionsController::class, 'store'])
        ->name('permissions.store');

    // CMS users
    $router->get('cms-users/{cmsUser}/photo', [AdminCmsUserController::class, 'getPhoto'])
        ->name('cmsUsers.photo');
    $router->get('cms-users/{cmsUser}/security', [AdminCmsUserSecurityController::class, 'index'])
        ->name('cmsUsers.security');
    $router->put('cms-users/{cmsUser}/security/password', [
        AdminCmsUserSecurityController::class, 'updatePassword'
    ])->name('cmsUsers.password');
    $router->resource('cms-users', AdminCmsUserController::class)
        ->names(resource_names('cmsUsers'));

    // file manager
    $router->get('file-manager', [AdminFileManagerController::class, 'index'])
        ->name('fileManager');

    // translations
    $router->controller(AdminTranslationsController::class)->group(function (Router $router) {
        $router->get('translations/form', 'getForm')->name('translations.form');
        $router->post('translations/form', 'setData')->name('translations.form.post');
        $router->resource('translations', AdminTranslationsController::class)
            ->names(resource_names('translations'))
            ->except(['show']);
    });

    // CMS settings
    $router->get('cms-settings', [AdminCmsSettingsController::class, 'index'])
        ->name('cmsSettings.index');
    $router->put('cms-settings', [AdminCmsSettingsController::class, 'update'])
        ->name('cmsSettings.update');
    // web settings
    $router->get('web-settings', [AdminWebSettingsController::class, 'index'])
        ->name('webSettings.index');
    $router->put('web-settings', [AdminWebSettingsController::class, 'update'])
        ->name('webSettings.update');

    // sitemap XML
    $router->post('sitemap/xml/store', [AdminSitemapXmlController::class, 'store'])
        ->name('sitemap.xml.store');
});

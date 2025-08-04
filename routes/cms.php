<?php

use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminCollectionController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminFileManagerController;
use App\Http\Controllers\Admin\AdminLanguageController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminSitemapXmlController;
use App\Http\Controllers\Admin\AdminTranslationController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserImageController;
use App\Http\Controllers\Admin\CmsUser\AdminCmsUserPreferenceController;
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
    $router->get('/', [AdminDashboardController::class, 'index'])->name('dashboard.index');

    // languages
    $router->controller(AdminLanguageController::class)->group(function (Router $router) {
        $router->put('languages/update-main', 'updateMain')
            ->name('languages.update_main');
        $router->put('languages/{id}/visibility', 'visibility')
            ->name('languages.visibility');
        $router->put('languages/positions',  'positions')
            ->name('languages.positions');
        $router->resource('languages', AdminLanguageController::class)
            ->names(resource_names('languages'))
            ->except(['show']);
    });

    // menus
    $router->put('menus/update-main', [AdminMenuController::class, 'updateMain'])
        ->name('menus.update_main');
    $router->resource('menus', AdminMenuController::class)
        ->names(resource_names('menus'))
        ->except(['show']);

    // pages
    $router->controller(AdminPageController::class)->group(function (Router $router) {
        $router->get('pages/listable-types', 'getListableTypes')
            ->name('pages.get_listable_types');
        $router->put('pages/{id}/visibility', 'visibility')->name('pages.visibility');
        $router->put('pages/positions', 'positions')->name('pages.positions');
        $router->put('pages/transfer/{menu}', 'transfer')->name('pages.transfer');
        $router->resource('menus.pages', AdminPageController::class)
            ->names(resource_names('pages'))
            ->except(['show']);
    });

    // products
    $router->controller(AdminProductController::class)->group(function (Router $router) {
        $router->put('products/{id}/visibility', 'visibility')->name('products.visibility');
        $router->put('products/positions', 'positions')->name('products.positions');
        $router->resource('products', AdminProductController::class)
            ->names(resource_names('products'))
            ->except(['show']);
    });

    // collections
    $router->resource('collections', AdminCollectionController::class)
        ->names(resource_names('collections'))
        ->except(['show']);

    // collection articles
    $router->controller(AdminArticleController::class)->group(function (Router $router) {
        $router->put('articles/{id}/visibility', 'visibility')->name('articles.visibility');
        $router->put('articles/positions', 'positions')->name('articles.positions');
        $router->put('articles/transfer/{collection}', 'transfer')->name('articles.transfer');
        $router->resource('collections.articles', AdminArticleController::class)
            ->names(resource_names('articles'))
            ->except(['show']);
    });

    // collection events
    $router->controller(AdminEventController::class)->group(function (Router $router) {
        $router->put('events/{id}/visibility', 'visibility')->name('events.visibility');
        $router->put('events/positions', 'positions')->name('events.positions');
        $router->put('events/transfer/{collection}', 'transfer')->name('events.transfer');
        $router->resource('collections.events', AdminEventController::class)
            ->names(resource_names('events'))
            ->except(['show']);
    });

    // file routes from config
    foreach ((array) cms_config('file_routes') as $route => $controller) {
        $router->put($route . '/files/{id}/visibility', [$controller, 'visibility'])
            ->name($route . '.files.visibility');
        $router->put($route . '/files/positions', [$controller, 'positions'])
            ->name($route . '.files.positions');
        $router->delete($route . '/{'.$route.'}/files', [$controller, 'destroyMany'])
            ->name($route . '.files.destroy_many');
        $router->resource($route . '.files', $controller)
            ->names(resource_names($route . '.files'))
            ->except(['show']);
    }

    // CMS user roles
    $router->resource('cms-user-roles', AdminCmsUserRoleController::class)
        ->names(resource_names('cms_user_roles'))
        ->except(['show']);

    // role permissions
    $router->get('permissions', [AdminPermissionController::class, 'index'])
        ->name('permissions.index');
    $router->post('permissions', [AdminPermissionController::class, 'store'])
        ->name('permissions.store');

    // CMS user security
    $router->get('cms-users/{cms_user}/security', [AdminCmsUserSecurityController::class, 'index'])
        ->name('cms_users.security');
    $router->put('cms-users/{cms_user}/security/password', [
        AdminCmsUserSecurityController::class, 'updatePassword'
    ])->name('cms_users.password');
    // CMS user preferences
    $router->get('cms-users/{cms_user}/preferences', [AdminCmsUserPreferenceController::class, 'index'])
        ->name('cms_users.preferences.index');
    $router->put('cms-users/{cms_user}/preferences', [AdminCmsUserPreferenceController::class, 'save'])
        ->name('cms_users.preferences.save');
    // CMS user photo
    $router->get('cms-users/{cms_user}/photo', [AdminCmsUserImageController::class, 'getPhoto'])
        ->name('cms_users.photo');
    // CMS user cover
    $router->get('cms-users/{cms_user}/cover', [AdminCmsUserImageController::class, 'getCover'])
        ->name('cms_users.cover');
    // CMS user image store
    $router->post('cms-users/{cms_user}/image', [AdminCmsUserImageController::class, 'store'])
        ->name('cms_users.image.store');
    // CMS user image delete
    $router->delete('cms-users/{cms_user}/image', [AdminCmsUserImageController::class, 'destroy'])
        ->name('cms_users.image.destroy');
    // CMS users
    $router->resource('cms-users', AdminCmsUserController::class)
        ->names(resource_names('cms_users'));

    // file manager
    $router->get('file-manager', [AdminFileManagerController::class, 'index'])
        ->name('file_manager');

    // translations
    $router->controller(AdminTranslationController::class)->group(function (Router $router) {
        $router->get('translations/form', 'getForm')->name('translations.form');
        $router->post('translations/form', 'setData')->name('translations.form.post');
        $router->resource('translations', AdminTranslationController::class)
            ->names(resource_names('translations'))
            ->except(['show']);
    });

    // sitemap XML
    $router->post('sitemap/xml/store', [AdminSitemapXmlController::class, 'store'])
        ->name('sitemap.xml.store');
});

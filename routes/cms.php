<?php

use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminCmsUsersController;
use App\Http\Controllers\Admin\AdminCollectionsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFilemanagerController;
use App\Http\Controllers\Admin\AdminLanguagesController;
use App\Http\Controllers\Admin\AdminMenusController;
use App\Http\Controllers\Admin\AdminNotesController;
use App\Http\Controllers\Admin\AdminPagesController;
use App\Http\Controllers\Admin\AdminPermissionsController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminSitemapXmlController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminTranslationsController;
use App\Http\Controllers\Admin\AdminWebSettingsController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'cms.viewData',
    'prefix' => cms_slug(null, true),
    'as' => cms_route_name_prefix('')
], function ($router) {
    // login
    $router->controller(AdminLoginController::class)->group(function ($router) {
        $router->get('login', 'showLoginForm')->name('login')->middleware('cms.guest');
        $router->post('login', 'login')->name('login.post')->middleware('cms.guest');
        $router->post('logout', 'logout')->name('logout');

        // lockscreen
        $router->middleware('cms.lockscreen')->group(function ($router) {
            $router->get('lockscreen', 'getLockscreen')->name('lockscreen')
                ->middleware('cms.guest');
            $router->post('lockscreen', 'postLockscreen')->name('lockscreen.post')
                ->middleware(['cms.guest', 'throttle:3,2']);
            $router->put('lockscreen', 'setLockscreen')->name('lockscreen.put');
        });
    });

    // Authentication
    $router->middleware('cms.auth')->group(function ($router) {
        // dashboard
        $router->get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // languages
        $router->post('languages/set-main', [AdminLanguagesController::class, 'setMain' ])
            ->name('languages.setMain');
        $router->resource('languages', AdminLanguagesController::class)
            ->names(resource_names('languages'))
            ->except(['show']);

        // menus
        $router->post('menus/set-main', [AdminMenusController::class, 'setMain'])->name('menus.setMain');
        $router->resource('menus', AdminMenusController::class)
            ->names(resource_names('menus'))
            ->except(['show']);

        // pages
        $router->controller(AdminPagesController::class)->group(function ($router) {
            $router->post('pages/{id}/visibility', 'visibility')->name('pages.visibility');
            $router->put('pages/position', 'updatePosition')->name('pages.updatePosition');
            $router->get('pages/templates', 'getTemplates')->name('pages.templates');
            $router->get('pages/listable-types', 'getListableTypes')->name('pages.listableTypes');
            $router->put('pages/transfer/{menuId}', 'transfer')->name('pages.transfer');
            $router->put('pages/collapse', 'collapse')->name('pages.collapse');
            $router->post('pages/{id}/clone-language', 'cloneLanguage')->name('pages.cloneLanguage');
            $router->resource('menus.pages', AdminPagesController::class)
                ->names(resource_names('pages'))
                ->except(['show']);
        });

        // collections
        $router->resource('collections', AdminCollectionsController::class)
            ->names(resource_names('collections'))
            ->except(['show']);

        // type routes from config
        foreach ((array) cms_config('type_routes') as $prefix => $routes) {
            foreach ((array) $routes as $route => $controller) {
                $router->post($route . '/{id}/visibility', [$controller, 'visibility'])
                    ->name($route . '.visibility');
                $router->put($route . '/position', [$controller, 'updatePosition'])
                    ->name($route . '.updatePosition');
                $router->put($route . '/transfer/{id}', [$controller, 'transfer'])
                    ->name($route . '.transfer');
                $router->post($route . '/{id}/clone-language', [$controller, 'cloneLanguage'])
                    ->name($route . '.cloneLanguage');
                $router->resource($prefix . '.' . $route, $controller)
                    ->names(resource_names($route))
                    ->except(['show']);
            }
        }

        // file routes from config
        foreach ((array) cms_config('file_routes') as $route => $controller) {
            $router->post($route . '/files/{id}/visibility', [$controller, 'visibility'])
                ->name($route . '.files.visibility');
            $router->put($route . '/files/position', [$controller, 'updatePosition'])
                ->name($route . '.files.updatePosition');
            $router->resource($route . '.files', $controller)
                ->names(resource_names($route . '.files'))
                ->except(['show']);
        }

        // permissions
        $router->get('permissions', [AdminPermissionsController::class, 'index'])
            ->name('permissions.index');
        $router->post('permissions', [AdminPermissionsController::class, 'store'])
            ->name('permissions.store');

        // CMS users
        $router->resource('cms-users', AdminCmsUsersController::class)
            ->names(resource_names('cmsUsers'));

        // file manager
        $router->get('filemanager', [AdminFilemanagerController::class, 'index'])
            ->name('filemanager');

        // slider
        $router->post('slider/{id}/visibility', [AdminSliderController::class, 'visibility'])
            ->name('slider.visibility');
        $router->put('slider/position', [AdminSliderController::class, 'updatePosition'])
            ->name('slider.updatePosition');
        $router->resource('slider', AdminSliderController::class)
            ->names(resource_names('slider'))
            ->except(['show']);

        // translations
        $router->controller(AdminTranslationsController::class)->group(function ($router) {
            $router->get('translations/form', 'getForm')->name('translations.form');
            $router->post('translations/form', 'setData')->name('translations.form.post');
            $router->post('translations/{id}/clone-language', 'cloneLanguage')
                ->name('translations.cloneLanguage');
            $router->resource('translations', AdminTranslationsController::class)
                ->names(resource_names('translations'))
                ->except(['show']);
        });

        // notes
        $router->controller(AdminNotesController::class)->group(function ($router) {
            $router->get('notes', 'index')->name('notes.index');
            $router->put('notes', 'save')->name('notes.save');
            $router->post('notes', 'destroy')->name('notes.destroy');
            $router->post('notes-calendar', 'calendar')->name('notes.calendar');
        });

        // calendar
        $router->controller(AdminCalendarController::class)->group(function ($router) {
            $router->get('calendar', 'index')->name('calendar.index');
            $router->post('calendar/events', 'events')->name('calendar.events');
            $router->put('calendar', 'save')->name('calendar.save');
            $router->post('calendar', 'destroy')->name('calendar.destroy');
        });

        // CMS settings
        $router->get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        $router->put('settings', [AdminSettingsController::class, 'update'])->name('settings.update');
        // web settings
        $router->get('web-settings', [AdminWebSettingsController::class, 'index'])
            ->name('webSettings.index');
        $router->put('web-settings', [AdminWebSettingsController::class, 'update'])
            ->name('webSettings.update');

        // sitemap XML
        $router->post('sitemap/xml/store', [AdminSitemapXmlController::class, 'store'])
            ->name('sitemap.xml.store');
    });
});

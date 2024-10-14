<?php

use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminCmsUsersController;
use App\Http\Controllers\Admin\AdminCollectionsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminFilemanagerController;
use App\Http\Controllers\Admin\AdminFilesController;
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

/*
|--------------------------------------------------------------------------
| CMS Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application CMS.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'cms.data', 'prefix' => cms_slug()], function ($router) {
    // login
    $router->get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    $router->post('login', [AdminLoginController::class, 'login'])->name('login');
    $router->post('logout', [AdminLoginController::class, 'logout'])->name('logout');

    // lockscreen
    $router->group(['middleware' => ['cms.lockscreen']], function ($router) {
        $router->get('lockscreen', [
            AdminLoginController::class, 'getLockscreen'
        ])->name('lockscreen');
        $router->post('lockscreen', [
            AdminLoginController::class, 'postLockscreen'
        ])->name('lockscreen')->middleware('throttle:3,2');
        $router->put('lockscreen', [
            AdminLoginController::class, 'setLockscreen'
        ])->name('lockscreen');
    });

    // CMS
    $router->group(['middleware' => ['cms.auth']], function ($router) {
        // dashboard
        $router->get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // menus
        $router->post('menus/set-main', [
            AdminMenusController::class, 'setMain'
        ])->name('menus.setMain');
        $router->resource('menus', AdminMenusController::class)
            ->names(resource_names('menus'))
            ->except(['show']);

        // pages
        $router->post('pages/{id}/visibility', [
            AdminPagesController::class, 'visibility'
        ])->name('pages.visibility');
        $router->put('pages/position', [
            AdminPagesController::class, 'updatePosition'
        ])->name('pages.updatePosition');
        $router->get('pages/templates', [
            AdminPagesController::class, 'getTemplates'
        ])->name('pages.templates');
        $router->get('pages/listable-types', [
            AdminPagesController::class, 'getListableTypes'
        ])->name('pages.listableTypes');
        $router->put('pages/transfer/{menuId}', [
            AdminPagesController::class, 'transfer'
        ])->name('pages.transfer');
        $router->put('pages/collapse', [
            AdminPagesController::class, 'collapse'
        ])->name('pages.collapse');
        $router->resource('menus.pages', AdminPagesController::class)
            ->names(resource_names('pages'))
            ->except(['show']);

        // collections
        $router->resource('collections', AdminCollectionsController::class)
            ->names(resource_names('collections'))
            ->except(['show']);
        // routes from config
        foreach ((array) cms_config('routes') as $prefix => $routes) {
            foreach ((array) $routes as $route => $controller) {
                $router->post($route . '/{id}/visibility', [$controller, 'visibility'])
                    ->name($route . '.visibility');
                $router->put($route . '/position', [$controller, 'updatePosition'])
                    ->name($route . '.updatePosition');
                $router->put($route . '/transfer/{id}', [$controller, 'transfer'])
                    ->name($route . '.transfer');
                $router->resource($prefix . '.' . $route, $controller)
                    ->names(resource_names($route))
                    ->except(['show']);
            }
        }

        // permissions
        $router->get('permissions', [
            AdminPermissionsController::class, 'index'
        ])->name('permissions.index');
        $router->post('permissions', [
            AdminPermissionsController::class, 'store'
        ])->name('permissions.store');

        // cms users
        $router->resource('cms-users', AdminCmsUsersController::class)
            ->names(resource_names('cmsUsers'));

        // file manager
        $router->get('filemanager', [
            AdminFilemanagerController::class, 'index'
        ])->name('filemanager');

        // files
        $router->post('files/{id}/visibility', [
            AdminFilesController::class, 'visibility'
        ])->name('files.visibility');
        $router->put('files/position', [
            AdminFilesController::class, 'updatePosition'
        ])->name('files.updatePosition');
        $router->resource('{routeName}/{routeId}/files', AdminFilesController::class)
            ->names(resource_names('files'))
            ->except(['show']);

        // slider
        $router->post('slider/{id}/visibility', [
            AdminSliderController::class, 'visibility'
        ])->name('slider.visibility');
        $router->put('slider/position', [
            AdminSliderController::class, 'updatePosition'
        ])->name('slider.updatePosition');
        $router->resource('slider', AdminSliderController::class)
            ->names(resource_names('slider'))
            ->except(['show']);

        // translations
        $router->get('translations/form', [
            AdminTranslationsController::class, 'getModal'
        ])->name('translations.popup');
        $router->post('translations/form', [
            AdminTranslationsController::class, 'postData'
        ])->name('translations.popup');
        $router->resource('translations', AdminTranslationsController::class)
            ->names(resource_names('translations'))
            ->except(['show']);

        // notes
        $router->get('notes', [
            AdminNotesController::class, 'index'
        ])->name('notes.index');
        $router->put('notes', [
            AdminNotesController::class, 'save'
        ])->name('notes.save');
        $router->post('notes', [
            AdminNotesController::class, 'destroy'
        ])->name('notes.destroy');
        $router->post('notes-calendar', [
            AdminNotesController::class, 'calendar'
        ])->name('notes.calendar');

        // calendar
        $router->get('calendar', [
            AdminCalendarController::class, 'index'
        ])->name('calendar.index');
        $router->post('calendar/events', [
            AdminCalendarController::class, 'events'
        ])->name('calendar.events');
        $router->put('calendar', [
            AdminCalendarController::class, 'save'
        ])->name('calendar.save');
        $router->post('calendar', [
            AdminCalendarController::class, 'destroy'
        ])->name('calendar.destroy');

        // cms settings
        $router->get('settings', [
            AdminSettingsController::class, 'index'
        ])->name('settings.index');
        $router->put('settings', [
            AdminSettingsController::class, 'update'
        ])->name('settings.update');
        // web settings
        $router->get('web-settings', [
            AdminWebSettingsController::class, 'index'
        ])->name('webSettings.index');
        $router->put('web-settings', [
            AdminWebSettingsController::class, 'update'
        ])->name('webSettings.update');

        // sitemap xml
        $router->get('sitemap/xml/store', [
            AdminSitemapXmlController::class, 'store'
        ])->name('sitemap.xml.store');
    });
});

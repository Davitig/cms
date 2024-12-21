<?php

use App\Http\Controllers\Admin\AdminArticlesController;
use App\Http\Controllers\Admin\AdminCalendarController;
use App\Http\Controllers\Admin\AdminCmsUserRolesController;
use App\Http\Controllers\Admin\AdminCmsUsersController;
use App\Http\Controllers\Admin\AdminCollectionsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventsController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminFilemanagerController;
use App\Http\Controllers\Admin\AdminGalleriesController;
use App\Http\Controllers\Admin\AdminLanguagesController;
use App\Http\Controllers\Admin\AdminLockscreenController;
use App\Http\Controllers\Admin\AdminMenusController;
use App\Http\Controllers\Admin\AdminNotesController;
use App\Http\Controllers\Admin\AdminPagesController;
use App\Http\Controllers\Admin\AdminPermissionsController;
use App\Http\Controllers\Admin\AdminPhotosController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminSitemapXmlController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminTranslationsController;
use App\Http\Controllers\Admin\AdminVideosController;
use App\Http\Controllers\Admin\AdminWebSettingsController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

Route::name(cms_route_name())->group(function ($router) {
    // Authentication
    $router->controller(AdminLoginController::class)->group(function ($router) {
        // login
        $router->middleware('cms.guest')->group(function ($router) {
            $router->get('login', 'showLoginForm')->name('login');
            $router->post('login', 'login')->name('login.post');
        });

        // logout
        $router->post('logout', 'logout')->name('logout');
    });

    // lockscreen
    $router->middleware('cms.lockscreen')->controller(AdminLockscreenController::class)
        ->group(function ($router) {
            $router->get('lockscreen', 'index')->name('lockscreen');
            $router->post('lockscreen', 'store')->name('lockscreen.post');
            $router->put('lockscreen', 'update')->name('lockscreen.put');
        });

    // Authenticated
    $router->middleware('cms.auth')->group(function ($router) {
        // dashboard
        $router->get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // languages
        $router->controller(AdminLanguagesController::class)->group(function ($router) {
            $router->put('languages/{id}/visibility', 'visibility')
                ->name('languages.visibility');
            $router->put('languages/position',  'updatePosition')
                ->name('languages.updatePosition');
            $router->resource('languages', AdminLanguagesController::class)
                ->names(resource_names('languages'))
                ->except(['show']);
        });

        // menus
        $router->post('menus/set-main', [AdminMenusController::class, 'setMain'])
            ->name('menus.setMain');
        $router->resource('menus', AdminMenusController::class)
            ->names(resource_names('menus'))
            ->except(['show']);

        // pages
        $router->controller(AdminPagesController::class)->group(function ($router) {
            $router->get('pages/listable-types', 'getListableTypes')
                ->name('pages.getListableTypes');
            $router->put('pages/{id}/visibility', 'visibility')->name('pages.visibility');
            $router->put('pages/position', 'updatePosition')->name('pages.updatePosition');
            $router->get('pages/templates', 'getTemplates')->name('pages.templates');
            $router->put('pages/transfer/{menu}', 'transfer')->name('pages.transfer');
            $router->put('pages/collapse', 'collapse')->name('pages.collapse');
            $router->resource('menus.pages', AdminPagesController::class)
                ->names(resource_names('pages'))
                ->except(['show']);
        });

        // collections
        $router->resource('collections', AdminCollectionsController::class)
            ->names(resource_names('collections'))
            ->except(['show']);

        // collection articles
        $router->controller(AdminArticlesController::class)->group(function ($router) {
            $router->put('articles/{id}/visibility', 'visibility')->name('articles.visibility');
            $router->put('articles/position', 'updatePosition')->name('articles.updatePosition');
            $router->put('articles/transfer/{collection}', 'transfer')->name('articles.transfer');
            $router->resource('collections.articles', AdminArticlesController::class)
                ->names(resource_names('articles'))
                ->except(['show']);
        });

        // collection events
        $router->controller(AdminEventsController::class)->group(function ($router) {
            $router->put('events/{id}/visibility', 'visibility')->name('events.visibility');
            $router->put('events/position', 'updatePosition')->name('events.updatePosition');
            $router->put('events/transfer/{collection}', 'transfer')->name('events.transfer');
            $router->resource('collections.events', AdminEventsController::class)
                ->names(resource_names('events'))
                ->except(['show']);
        });

        // collection faq
        $router->controller(AdminFaqController::class)->group(function ($router) {
            $router->put('faq/{id}/visibility', 'visibility')->name('faq.visibility');
            $router->put('faq/position', 'updatePosition')->name('faq.updatePosition');
            $router->put('faq/transfer/{collection}', 'transfer')->name('faq.transfer');
            $router->resource('collections.faq', AdminFaqController::class)
                ->names(resource_names('faq'))
                ->except(['show']);
        });

        // collection galleries
        $router->controller(AdminGalleriesController::class)->group(function ($router) {
            $router->put('galleries/{id}/visibility', 'visibility')->name('galleries.visibility');
            $router->put('galleries/position', 'updatePosition')->name('galleries.updatePosition');
            $router->put('galleries/transfer/{collection}', 'transfer')->name('galleries.transfer');
            $router->resource('collections.galleries', AdminGalleriesController::class)
                ->names(resource_names('galleries'))
                ->except(['show']);
        });

        // gallery photos
        $router->controller(AdminPhotosController::class)->group(function ($router) {
            $router->put('photos/{id}/visibility', 'visibility')->name('photos.visibility');
            $router->put('photos/position', 'updatePosition')->name('photos.updatePosition');
            $router->resource('galleries.photos', AdminPhotosController::class)
                ->names(resource_names('photos'))
                ->except(['show']);
        });

        // gallery videos
        $router->controller(AdminVideosController::class)->group(function ($router) {
            $router->put('videos/{id}/visibility', 'visibility')->name('videos.visibility');
            $router->put('videos/position', 'updatePosition')->name('videos.updatePosition');
            $router->resource('galleries.videos', AdminVideosController::class)
                ->names(resource_names('videos'))
                ->except(['show']);
        });

        // file routes from config
        foreach ((array) cms_config('file_routes') as $route => $controller) {
            $router->put($route . '/files/{id}/visibility', [$controller, 'visibility'])
                ->name($route . '.files.visibility');
            $router->put($route . '/files/position', [$controller, 'updatePosition'])
                ->name($route . '.files.updatePosition');
            $router->resource($route . '.files', $controller)
                ->names(resource_names($route . '.files'))
                ->except(['show']);
        }

        // CMS user roles
        $router->resource('cms-user-roles', AdminCmsUserRolesController::class)
            ->names(resource_names('cmsUserRoles'))
            ->except(['show']);

        // role permissions
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
        $router->controller(AdminSliderController::class)->group(function ($router) {
            $router->put('slider/{id}/visibility', 'visibility')->name('slider.visibility');
            $router->put('slider/position', 'updatePosition')->name('slider.updatePosition');
            $router->resource('slider', AdminSliderController::class)
                ->names(resource_names('slider'))
                ->except(['show']);
        });

        // translations
        $router->controller(AdminTranslationsController::class)->group(function ($router) {
            $router->get('translations/form', 'getForm')->name('translations.form');
            $router->post('translations/form', 'setData')->name('translations.form.post');
            $router->resource('translations', AdminTranslationsController::class)
                ->names(resource_names('translations'))
                ->except(['show']);
        });

        // notes
        $router->controller(AdminNotesController::class)->group(function ($router) {
            $router->get('notes', 'index')->name('notes.index');
            $router->put('notes', 'save')->name('notes.save');
            $router->delete('notes', 'destroy')->name('notes.destroy');
        });

        // calendar
        $router->controller(AdminCalendarController::class)->group(function ($router) {
            $router->get('calendar', 'index')->name('calendar.index');
            $router->post('calendar/events', 'events')->name('calendar.events');
            $router->put('calendar', 'save')->name('calendar.save');
            $router->delete('calendar', 'destroy')->name('calendar.destroy');
        });

        // CMS settings
        $router->get('settings', [AdminSettingsController::class, 'index'])
            ->name('settings.index');
        $router->put('settings', [AdminSettingsController::class, 'update'])
            ->name('settings.update');
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

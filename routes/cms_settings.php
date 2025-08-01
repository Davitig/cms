<?php

use App\Http\Controllers\Admin\AdminContactSettingController;
use App\Http\Controllers\Admin\Setting\AdminSettingCacheController;
use App\Http\Controllers\Admin\Setting\AdminSettingController;
use App\Http\Controllers\Admin\Setting\AdminMetaSettingController;
use App\Http\Controllers\Admin\Setting\AdminSettingSystemInformationController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Authenticated
Route::middleware('cms.auth')->group(function (Router $router) {

    // settings
    $router->get('settings', [AdminSettingController::class, 'index'])
        ->name('settings.index');

    // contact
    $router->get('settings/contact', [AdminContactSettingController::class, 'index'])
        ->name('settings.contact.index');
    $router->post('settings/contact', [AdminContactSettingController::class, 'save'])
        ->name('settings.contact.save');

    // meta
    $router->get('settings/meta', [AdminMetaSettingController::class, 'index'])
        ->name('settings.meta.index');
    $router->post('settings/meta', [AdminMetaSettingController::class, 'save'])
        ->name('settings.meta.save');

    // cache
    $router->controller(AdminSettingCacheController::class)->group(function ($router) {
        $router->get('settings/cache', 'index')->name('settings.cache.index');
        $router->post('settings/cache/views', 'clearViews')
            ->name('settings.cache.view_clear');
        $router->post('settings/cache/config', 'updateConfig')
            ->name('settings.cache.config');
        $router->post('settings/cache/routes', 'updateRoutes')
            ->name('settings.cache.routes');
    });

    // system
    $router->get('settings/system', [AdminSettingSystemInformationController::class, 'index'])
        ->name('settings.system.index');
});

<?php

use App\Http\Controllers\Web\WebGlideServerController;
use Illuminate\Support\Facades\Route;

Route::namespace('Web')->middleware('web.viewData')->group(function ($router) {
    // web routes

    // glide server for image manipulation
    // $router->get(
    //     config('web.glide_base_url') . '/{path}',
    //     [WebGlideServerController::class, 'show']
    // )->name('glide')->where('path', '.+');
});

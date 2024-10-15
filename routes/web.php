<?php

use App\Http\Controllers\Web\WebGlideServerController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Web'], function ($router) {
    // web routes

    // glide server for image manipulation
    // $router->get(
    //     config('web.glide_base_url') . '/{path}',
    //     [WebGlideServerController::class, 'show']
    // )->name('glide')->where('path', '.+');
});

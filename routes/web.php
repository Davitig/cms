<?php

use App\Http\Controllers\Web\WebGlideServerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Web'], function ($router) {
    // web routes

    // glide server for image manipulation
    // $router->get(
    //     $this->app['config']->get('web.glide_base_url', '!img') . '/{path}',
    //     [WebGlideServerController::class, 'show']
    // )->name('glide')->where('path', '.+');
});

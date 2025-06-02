<?php

use App\Http\Controllers\Web\WebHomeController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (Router $router) {
    // home
    $router->get('/', [WebHomeController::class, 'index'])->name('home');
});

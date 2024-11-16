<?php

use App\Http\Controllers\Web\WebHomeController;

Route::get('/', [WebHomeController::class, 'index'])->name('home');

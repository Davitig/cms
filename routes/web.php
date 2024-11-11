<?php

use Illuminate\Support\Facades\Route;

Route::namespace('Web')->middleware('web.viewData')->group(function ($router) {
    // web routes
});

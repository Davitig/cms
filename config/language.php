<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Route Name Prefix
    |--------------------------------------------------------------------------
    |
    | Here you may specify route name prefix, which will be applied to the
    | routes with the stored language codes as a route parameters.
    |
    */

    'route_name' => 'lang',

    /*
    |--------------------------------------------------------------------------
    | Query String Key
    |--------------------------------------------------------------------------
    |
    | This key is used to retrieve a query string parameter from the request.
    |
    */

    'query_string_key' => 'lang',

    /*
    |--------------------------------------------------------------------------
    | Setting Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default language setting model.
    |
    */

    'setting_model' => App\Models\Language\LanguageSetting::class,

];

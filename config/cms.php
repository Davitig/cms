<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CMS Version
    |--------------------------------------------------------------------------
    |
    | Version of the content management system (CMS).
    |
    */

    'version' => '3.1.2',

    /*
    |--------------------------------------------------------------------------
    | CMS Slug
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CMS slug for the application.
    |
    */

    'slug' => '!cms',

    /*
    |--------------------------------------------------------------------------
    | Pages
    |--------------------------------------------------------------------------
    |
    | This array used to specify types of the page.
    |
    */

    'pages' => [
        'types' => [
            'page' => 'Page',
            'feedback' => 'Feedback',
            'search' => 'Search',
            'products' => 'Products',
            'articles' => 'Articles',
            'events' => 'Events'
        ],
        'listable' => [
            'collections' => [
                'articles' => App\Models\Article\Article::class,
                'events' => App\Models\Event\Event::class,
            ]
        ],
        'extended' => [
            'products' => App\Models\Product\Product::class
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers
    |--------------------------------------------------------------------------
    |
    | This array used to specify custom controllers for any types.
    | Default controller: "App\Http\Controllers\Web\Web{Type}Controller"
    |
    */
    'controllers' => [],

    /*
    |--------------------------------------------------------------------------
    | File Routes
    |--------------------------------------------------------------------------
    |
    | Here you can specify file routes, which also inherits additional routes:
    | "visibility", "position"
    |
    */

    'file_routes' => [
        'pages' => App\Http\Controllers\Admin\AdminPageFileController::class,
        'products' => App\Http\Controllers\Admin\AdminProductFileController::class,
        'articles' => App\Http\Controllers\Admin\AdminArticleFileController::class,
        'events' => App\Http\Controllers\Admin\AdminEventFileController::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Listable
    |--------------------------------------------------------------------------
    |
    | This array used to specify listable types and its settings.
    | Listable types data will be separated by its holder.
    |
    */

    'listable' => [
        'collections' => [
            'model' => App\Models\Collection::class,
            'types' => [
                'articles' => 'Articles',
                'events' => 'Events'
            ],
            'order_by' => [
                'id' => 'Default',
                'position' => 'Position',
                'created_at' => 'Creation date'
            ],
            'sort' => [
                'desc' => 'Descending',
                'asc' => 'Ascending'
            ]
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Type Request Methods
    |--------------------------------------------------------------------------
    |
    | This array used to specify request methods, that will allow to send a
    | specified requests on the selected type.
    |
    | [request method] => [type@method => newMethod]
    |
    | Note: Default type method [index] will be applied if not specified.
    | Note: Request method names must be uppercase.
    */

    'type_request_methods' => [
        'POST' => [
            'feedback@index' => 'send'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Tabs
    |--------------------------------------------------------------------------
    |
    | This array used to specify types, that will allow additional
    | tab like URIs.
    |
    | [request methods] => [type@method] => [URI => newMethod]
    |
    | Note: Default type method [index] will be applied if not specified.
    | Note: Request method must be uppercase.
    */

    'tabs' => [
        // 'GET' => [
        //     'articles@show' => [
        //         'comments' => 'getComments',
        //         'comments/{id}' => 'getComments'
        //     ]
        // ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Types
    |--------------------------------------------------------------------------
    |
    | The list of types that will group translations.
    |
    */

    'trans_types' => [],

    /*
    |--------------------------------------------------------------------------
    | Translation Query Limit
    |--------------------------------------------------------------------------
    |
    | Here you may specify the limit for the translation query, which
    | will be used in views to collect all the data in the limit range.
    | If there is more data, then queries will be executed separately.
    |
    */

    'trans_query_limit' => 80,

];

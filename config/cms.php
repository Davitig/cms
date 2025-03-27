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

    'version' => '3.0.2',

    /*
    |--------------------------------------------------------------------------
    | CMS Slug
    |--------------------------------------------------------------------------
    |
    | Here you should specify the CMS slug for the application.
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
            'articles' => 'Articles',
            'events' => 'Events',
            'faq' => 'FAQ'
        ],
        'listable' => [
            'collections' => [
                'articles' => App\Models\Article\Article::class,
                'events' => App\Models\Event\Event::class,
                'faq' => App\Models\Faq::class
            ]
        ],
        'extended' => [],
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
        'pages' => App\Http\Controllers\Admin\AdminPageFilesController::class,
        'articles' => App\Http\Controllers\Admin\AdminArticleFilesController::class,
        'events' => App\Http\Controllers\Admin\AdminEventFilesController::class
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
                'events' => 'Events',
                'faq' => 'FAQ'
            ],
            'order_by' => [
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
    | The limit of the translation query.
    |
    */

    'trans_query_limit' => 80,

    /*
    |--------------------------------------------------------------------------
    | Type Icons
    |--------------------------------------------------------------------------
    |
    | Set icons for types.
    |
    */

    'icons' => [
        'dashboard' => 'fa fa-dashboard',

        'languages' => 'fa fa-sort-alpha-asc',

        'menus' => 'fa fa-list',
        'pages' => 'fa fa-indent',

        'collections' => 'fa fa-list-alt',
        'articles' => 'fa fa-newspaper',
        'events' => 'fa fa-globe',
        'faq' => 'fa fa-question-circle',

        'galleries' => 'fa fa-th',
        'photos' => 'fa fa-image',
        'videos' => 'fa fa-play-circle',

        'roles' => 'fa fa-key',
        'permissions' => 'fa fa-lock',
        'cmsUsers' => 'fa fa-user-secret',
        'users' => 'fa fa-user',

        'translations' => 'fa fa-language',
        'files' => 'fa fa-paperclip',
    ],

];

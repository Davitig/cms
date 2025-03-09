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
            'collections' => 'Collections',
            'feedback' => 'Feedback',
            'search' => 'Search'
        ],
        'templates' => [
            // 'page' => [
            //     'about' => 'About'
            // ]
        ],
        'listable' => [
            'collections'
        ],
        'implicit' => [
            'collections' => App\Models\Collection::class,
            'galleries' => App\Models\Gallery\Gallery::class
        ],
        'explicit' => [
            //
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Controllers
    |--------------------------------------------------------------------------
    |
    | This array used to specify custom controllers for any types.
    | Default controller: "App\Http\Controllers\Web\Web`Type`Controller"
    |
    */
    'controllers' => [
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS File Routes
    |--------------------------------------------------------------------------
    |
    | Here you can specify file routes, which will also get additional routes.
    | Additional routes: "visibility", "position"
    |
    */

    'file_routes' => [
        'pages' => App\Http\Controllers\Admin\AdminPageFilesController::class,
        'articles' => App\Http\Controllers\Admin\AdminArticleFilesController::class,
        'events' => App\Http\Controllers\Admin\AdminEventFilesController::class
    ],

    /*
    |--------------------------------------------------------------------------
    | Collections
    |--------------------------------------------------------------------------
    |
    | This array used to specify collection types and its settings.
    |
    */

    'collections' => [
        'types' => [
            'articles' => 'Articles',
            'events' => 'Events',
            'galleries' => 'Galleries',
            'faq' => 'FAQ'
        ],
        'models' => [
            'articles' => App\Models\Article\Article::class,
            'events' => App\Models\Event\Event::class,
            'galleries' => App\Models\Gallery\Gallery::class,
            'faq' => App\Models\Faq::class
        ],
        'order_by' => [
            'position' => 'Position',
            'created_at' => 'Creation date'
        ],
        'sort' => [
            'desc' => 'Descending',
            'asc' => 'Ascending'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Inner Collections
    |--------------------------------------------------------------------------
    |
    | The array of the collection types that has a parent collection.
    |
    */

    'deep_collections' => [
        'galleries' => [
            'types' => [
                'photos' => 'Photos',
                'videos' => 'Videos'
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
    | Request Methods For Types
    |--------------------------------------------------------------------------
    |
    | This array used to specify request methods with types, that will allow
    | to send a specified requests.
    |
    | [request methods] => [type => controller method]
    |
    | E.g. "post", "put", "delete"
    |
    */

    'type_methods' => [
        'post' => [
            'feedback' => 'send'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Tabs
    |--------------------------------------------------------------------------
    |
    | This array used to specify types, that will allow adding tab like URIs.
    |
    | [request methods] => [types@method] => [URI => controller method]
    |
    */

    'tabs' => [
        // 'get' => [
        //     'articles@show' => [
        //         'comments' => 'getComments'
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
    | CMS Route Type Icons
    |--------------------------------------------------------------------------
    |
    | Set icons for all CMS route types.
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

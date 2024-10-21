<?php

use App\Http\Controllers\Admin\AdminArticleFilesController;
use App\Http\Controllers\Admin\AdminArticlesController;
use App\Http\Controllers\Admin\AdminEventFilesController;
use App\Http\Controllers\Admin\AdminEventsController;
use App\Http\Controllers\Admin\AdminFaqController;
use App\Http\Controllers\Admin\AdminGalleriesController;
use App\Http\Controllers\Admin\AdminPageFilesController;
use App\Http\Controllers\Admin\AdminPhotosController;
use App\Http\Controllers\Admin\AdminVideosController;

return [

    /*
    |--------------------------------------------------------------------------
    | CMS Version
    |--------------------------------------------------------------------------
    |
    | Version of the content management system (CMS).
    |
    */

    'version' => '2.3.0',

    /*
    |--------------------------------------------------------------------------
    | CMS Slug
    |--------------------------------------------------------------------------
    |
    | Here you should specify the cms slug for the application.
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
            //     'template_name' => 'Template Name'
            // ]
        ],
        'listable' => [
            'collections'
        ],
        'implicit' => [
            'collections' => App\Models\Collection::class,
            'galleries' => App\Models\Gallery::class
        ],
        'explicit' => [
            //
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS Type Routes
    |--------------------------------------------------------------------------
    |
    | Here you can specify routes, which will also get additional routes.
    | Additional routes: "visibility", "position", "transfer"
    |
    */

    'type_routes' => [
        'collections' => [
            'articles' => AdminArticlesController::class,
            'events' => AdminEventsController::class,
            'galleries' => AdminGalleriesController::class,
            'faq' => AdminFaqController::class
        ],
        'galleries' => [
            'photos' => AdminPhotosController::class,
            'videos' => AdminVideosController::class
        ]
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
        'pages' => AdminPageFilesController::class,
        'articles' => AdminArticleFilesController::class,
        'events' => AdminEventFilesController::class
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
            'articles' => \App\Models\Article::class,
            'events' => \App\Models\Event::class,
            'galleries' => \App\Models\Gallery::class,
            'faq' => \App\Models\Faq::class
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
    | Request Methods
    |--------------------------------------------------------------------------
    |
    | This array used to specify types with methods, that will allow to
    | send a specific requests.
    |
    | E.g. "post", "put", "delete".
    |
    */

    'methods' => [
        'post' => [
            'feedback@index' => 'send'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Tabs
    |--------------------------------------------------------------------------
    |
    | This array used to specify types, that will allow additional tab URIs.
    |
    | type => [
    |     'uri' => 'method'
    | ]
    |
    */

    'tabs' => [],

    /*
    |--------------------------------------------------------------------------
    | CMS User Roles
    |--------------------------------------------------------------------------
    |
    | This array used to specify CMS user roles.
    |
    */

    'user_roles' => [
        'admin' => 'Administrator',
        'member' => 'Member'
    ],

    /*
    |--------------------------------------------------------------------------
    | Translation Types
    |--------------------------------------------------------------------------
    |
    | The list of types that will filter translations.
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
        'articles' => 'fa fa-newspaper-o',
        'events' => 'fa fa-globe',
        'faq' => 'fa fa-question-circle',

        'galleries' => 'fa fa-th',
        'photos' => 'fa fa-photo',
        'videos' => 'fa fa-video-camera',

        'permissions' => 'fa fa-lock',
        'cmsUsers' => 'fa fa-user-secret',
        'users' => 'fa fa-user',

        'translations' => 'fa fa-language',
        'files' => 'fa fa-paperclip',
    ],

];

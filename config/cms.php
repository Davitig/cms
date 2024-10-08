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
            'collections' => Models\Collection::class,
            'galleries' => Models\Gallery::class
        ],
        'explicit' => []
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS Resource Routes
    |--------------------------------------------------------------------------
    |
    | Here you can specify routes, which will also obtain additional routes.
    |
    */

    'routes' => [
        'collections' => [
            'articles' => 'AdminArticlesController',
            'events' => 'AdminEventsController',
            'galleries' => 'AdminGalleriesController',
            'faq' => 'AdminFaqController'
        ],
        'galleries' => [
            'photos' => 'AdminPhotosController',
            'videos' => 'AdminVideosController'
        ]
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
            'articles' => \Models\Article::class,
            'events' => \Models\Event::class,
            'galleries' => \Models\Gallery::class,
            'faq' => \Models\Faq::class
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
    | For example, "post", "put", "delete".
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
    | File Routes
    |--------------------------------------------------------------------------
    |
    | The array of file route names, that has access to the attached files.
    | Route names can also contain a foreign key.
    |
    */

    'files' => [
        'pages' => [
            'model' => \Models\Page::class,
            'foreign_key' => 'menu_id'
        ],
        'articles' => [
            'model' => \Models\Article::class,
            'foreign_key' => 'collection_id'
        ],
        'events' => [
            'model' => \Models\Event::class,
            'foreign_key' => 'collection_id'
        ]
    ],

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

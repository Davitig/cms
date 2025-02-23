<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    /**
     * Display a Dashboard.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $db = app('db');

        // main
        $data['menusTotal'] = $db->table('menus')->count();

        $data['pagesTotal'] = $db->table('pages')->count();
        $data['mainPage'] = $db->table('menus')->where('main', 1)->first(['id']);
        $data['mainPagesTotal'] = $db->table('pages')->where('menu_id',
            is_null($data['mainPage']) ? 1 : $data['mainPage']->id
        )->count();

        $data['collectionsTotal'] = $db->table('collections')->count();
        $data['usersTotal'] = $db->table('cms_users')->count();

        // events
        $data['eventsTotalDistinct'] = $db->table('events')->count($db->raw('DISTINCT collection_id'));
        $data['eventsTotal'] = $db->table('events')->count();

        // articles
        $data['articlesTotalDistinct'] = $db->table('articles')->count($db->raw('DISTINCT collection_id'));
        $data['articlesTotal'] = $db->table('articles')->count();

        // galleries
        $data['galleriesTotal'] = $db->table('collections')->where('type', 'galleries')->count();

        // photos
        $data['photoAlbumTotal'] = $db->table('galleries')->where('type', 'photos')->count();
        $data['photosTotal'] = $db->table('photos')->count();

        // videos
        $data['videoAlbumTotal'] = $db->table('galleries')->where('type', 'videos')->count();
        $data['videosTotal'] = $db->table('videos')->count();

        // calendar
        $data['calendarTotal'] = $db->table('calendar')->count();

        // notes
        $data['notes'] = $db->table('notes')->orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard.index', $data);
    }
}

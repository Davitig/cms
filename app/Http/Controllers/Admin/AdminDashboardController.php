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
        $data['mainMenuId'] = $db->table('menus')
            ->where('main', 1)
            ->union($db->table('menus')->where('main', 0)->select('id'))
            ->limit(1)
            ->value('id');
        $data['mainPagesTotal'] = $db->table('pages')
            ->where('menu_id', $data['mainMenuId'])
            ->count();

        $data['collectionsTotal'] = $db->table('collections')->count();
        $data['usersTotal'] = $db->table('cms_users')->count();

        // events
        $data['eventsTotalDistinct'] = $db->table('events')->count($db->raw('DISTINCT collection_id'));
        $data['eventsTotal'] = $db->table('events')->count();

        // articles
        $data['articlesTotalDistinct'] = $db->table('articles')->count($db->raw('DISTINCT collection_id'));
        $data['articlesTotal'] = $db->table('articles')->count();

        // calendar
        $data['calendarTotal'] = $db->table('calendar')->count();

        // notes
        $data['notes'] = $db->table('notes')->orderBy('id', 'desc')->take(5)->get();

        return view('admin.dashboard.index', $data);
    }
}

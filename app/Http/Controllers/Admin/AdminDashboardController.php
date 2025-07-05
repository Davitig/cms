<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

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

        $data['menuId'] = $db->table('menus')
            ->where('main', 1)
            ->union($db->table('menus')->where('main', 0)->select('id'))
            ->limit(1)
            ->value('id');

        $data += Cache::remember('dashboard', 60, function () use ($db) {
            return [
                'menusTotal' => $db->table('menus')->count(),
                'pagesTotal' => $db->table('pages')->count(),
                'cmsUsersTotal' => $db->table('cms_users')->count(),
                'productsTotal' => $db->table('products')->count(),
                'collectionsTotal' => $db->table('collections')->count(),
                'articlesTotal' => $db->table('articles')->count(),
                'eventsTotal' => $db->table('events')->count(),
                'translationsTotal' => $db->table('translations')->count()
            ];
        });

        return view('admin.dashboard.index', $data);
    }
}

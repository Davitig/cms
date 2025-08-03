<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article\Article;
use App\Models\CmsUser\CmsUser;
use App\Models\Collection;
use App\Models\Event\Event;
use App\Models\Menu;
use App\Models\Page\Page;
use App\Models\Product\Product;
use App\Models\Translation\Translation;
use Illuminate\Contracts\Cache\Repository;

class AdminDashboardController extends Controller
{
    /**
     * Display a Dashboard.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Repository $cache)
    {
        $data['menuId'] = (new Menu)->whereMain(1)
            ->union((new Menu)->whereMain(0)->select('id'))
            ->value('id');

        $data += $cache->remember('dashboard', 60, function () {
            return [
                'menuCount' => (new Menu)->count(),
                'pageCount' => (new Page)->count(),
                'cmsUserCount' => (new CmsUser)->count(),
                'productCount' => (new Product)->count(),
                'collectionCount' => (new Collection)->count(),
                'articleCount' => (new Article)->count(),
                'eventCount' => (new Event)->count(),
                'translationCount' => (new Translation)->count()
            ];
        });

        return view('admin.dashboard.index', $data);
    }
}

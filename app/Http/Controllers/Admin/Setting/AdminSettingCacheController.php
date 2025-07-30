<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;

class AdminSettingCacheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $data['configCached'] = app()->configurationIsCached();

        $data['routesCached'] = app()->routesAreCached();

        return view('admin.settings.cache', $data);
    }

    /**
     * Run an Artisan console view:clear command.
     *
     * @param  \Illuminate\Contracts\Console\Kernel  $artisan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function clearViews(Kernel $artisan)
    {
        $artisan->call('view:clear');

        if (request()->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated')));
        }

        return back()->with('alert', fill_data('success', trans('general.updated')));
    }

    /**
     * Run an Artisan console config cache/clear command.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Console\Kernel  $artisan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateConfig(Application $app, Kernel $artisan)
    {
        // assigning the redirect before artisan cache command to keep the session flash data.
        $response = ($expectsJson = request()->expectsJson()) ? response() : back();

        if ($app->configurationIsCached()) {
            $artisan->call('config:clear');
        } else {
            $artisan->call('config:cache');
        }

        if ($expectsJson) {
            return $response->json(fill_data(true, trans('general.updated')));
        }

        return $response->with('alert', fill_data(true, trans('general.updated')));
    }


    /**
     * Run an Artisan console route cache/clear command.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Console\Kernel  $artisan
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateRoutes(Application $app, Kernel $artisan)
    {
        // assigning the redirect before artisan cache command to keep the session flash data.
        $response = ($expectsJson = request()->expectsJson()) ? response() : back();

        if ($app->routesAreCached()) {
            $artisan->call('route:clear');
        } else {
            $artisan->call('route:cache');
        }

        if ($expectsJson) {
            return $response->json(fill_data(true, trans('general.updated')));
        }

        return $response->with('alert', fill_data(true, trans('general.updated')));
    }
}

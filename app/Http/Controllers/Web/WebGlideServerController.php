<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Http\Request;
use League\Glide\Server;

class WebGlideServerController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected Server $server, protected Request $request) {}

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  string  $path
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function show(Config $config, string $path)
    {
        if (! $type = $this->request->get('type')) {
            return redirect(web_url([current(config('elfinder.dir')) . '/' . $path]));
        }

        $settings = $config['web.glide.' . $type];

        if (! is_array($settings)) {
            abort(404);
        }

        try {
            $this->server->outputImage($path, $settings);
        } catch (Exception) {
            abort(404);
        }
    }
}

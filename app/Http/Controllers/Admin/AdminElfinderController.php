<?php

/*
|--------------------------------------------------------------------------
| elFinder
|--------------------------------------------------------------------------
|
| Override base controller methods.
|
*/

namespace App\Http\Controllers\Admin;

use Barryvdh\Elfinder\Connector;
use Barryvdh\Elfinder\ElfinderController as Elfinder;
use Illuminate\Filesystem\FilesystemAdapter;

class AdminElfinderController extends Elfinder
{
    /**
     * Show connector.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function showConnector()
    {
        $roots = $this->app['config']->get('elfinder.roots', []);
        if (empty($roots)) {
            $globalOptions = $this->app['config']->get('elfinder.roots_options');

            $public = (array) $this->app['config']->get('elfinder.public', []);
            foreach ($public as $key => $root) {
                if (is_string($root)) {
                    $key = $root;
                    $root = [];
                }
                $defaults = [
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => public_path($key), // path to files (REQUIRED)
                    'URL' => url($key), // URL to files (REQUIRED)
                    'accessControl' => $this->app['config']->get('elfinder.access') // filter callback (OPTIONAL)
                ];
                $roots[] = array_merge($defaults, $root, $globalOptions);
            }

            if (! $this->app['request']->boolean('hide_disks')) {
                $disks = (array) $this->app['config']->get('elfinder.disks', []);
                foreach ($disks as $key => $root) {
                    if (! $this->cmsUserHasAccessToDisk()) {
                        continue;
                    }

                    if (is_string($root)) {
                        $key = $root;
                        $root = [];
                    }

                    $disk = app('filesystem')->disk($key);
                    if ($disk instanceof FilesystemAdapter) {
                        $defaults = [
                            'driver' => 'Flysystem',
                            'filesystem' => $disk->getDriver(),
                            'alias' => $key,
                        ];
                        $roots[] = array_merge($defaults, $root, $globalOptions);
                    }
                }
            }
        }

        $opts = $this->app['config']->get('elfinder.options', array());
        $opts = array_merge(['roots' => $roots], $opts);

        // run elFinder
        $connector = new Connector(new \elFinder($opts));
        $connector->run();
        return $connector->getResponse();
    }

    /**
     * Determine if the CMS user has access to the disk.
     *
     * @return bool
     */
    protected function cmsUserHasAccessToDisk(): bool
    {
        if (is_null($user = $this->app['auth']->guard('cms')->user())
            || ! $user->hasFullAccess()
        ) {
            return false;
        }

        return true;
    }
}

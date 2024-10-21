<?php

namespace App\Providers\Web;

use App\Http\Controllers\Web\WebHomeController;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use App\Models\Page;

final class DynamicRouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the dynamic routes.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers\Web';

    /**
     * The controller for home page.
     *
     * @var string
     */
    protected $homeController = WebHomeController::class;

    /**
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected $router;

    /**
     * The list of router binders.
     *
     * @var array
     */
    protected $binders = [];

    /**
     * The unbinder keyword.
     *
     * @var string
     */
    protected $unbinder = '{{unbind}}';

    /**
     * The prefix of the route path.
     *
     * @var string|null
     */
    protected $pathPrefix = null;

    /**
     * The list of URL segments.
     *
     * @var array
     */
    protected $segments = [];

    /**
     * The number of total URL segments.
     *
     * @var int
     */
    protected $segmentsCount = 0;

    /**
     * The array of model items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * The array of the listable types.
     *
     * @var array
     */
    protected $listableTypes = [];

    /**
     * The array of the implicit types.
     *
     * @var array
     */
    protected $implicitTypes = [];

    /**
     * The array of the explicit types.
     *
     * @var array
     */
    protected $explicitTypes = [];

    /**
     * The array of the types that will allow specific requests.
     *
     * @var array
     */
    protected $requestMethods = [];

    /**
     * Define a dynamic route.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function ($app) {
            $this->config = $app['config'];

            if (! $this->config->get('cms_is_booted')) {
                $this->request = $app['request'];

                $this->router = $app['router'];

                if ($this->config->get('language_in_url')) {
                    $this->pathPrefix = $this->config->get('app.language') . '/';
                }

                $routeMatches = 0;

                foreach ($this->router->getRoutes()->get($this->request->method()) as $route) {
                    if ($route->matches($this->request)) {
                        $routeMatches = 1;

                        break;
                    }
                }

                if (! $routeMatches) {
                    $this->build();
                }
            }
        });
    }

    /**
     * Set route configuration.
     *
     * @return void
     */
    protected function configure()
    {
        $this->segments = (array) $this->config->get('url_path_segments', []);

        $this->segmentsCount = $this->config->get('url_path_segments_count', 0);

        $this->listableTypes = (array) $this->config->get('cms.pages.listable', []);

        $this->implicitTypes = (array) $this->config->get('cms.pages.implicit', []);

        $this->explicitTypes = (array) $this->config->get('cms.pages.explicit', []);

        $this->requestMethods = (array) $this->config->get('cms.methods', []);
    }

    /**
     * Build a new route.
     *
     * @return void
     */
    public function build()
    {
        $this->configure();

        $this->router->group([
            'middleware' => ['web', 'web.data'],
            'namespace' => $this->namespace
        ], function () {
            $this->setRoute();
        });
    }

    /**
     * Set pages.
     *
     * @return void
     */
    protected function setPages()
    {
        $parentId = 0;

        for ($i = 0; $i < $this->segmentsCount; $i++) {
            $page = (new Page)->bySlugRoute($this->segments[$i], $parentId)->first();

            if (is_null($page)) {
                if (count($this->items) < 1
                    || (! in_array($type = $this->items[$i - 1]->type, $this->listableTypes)
                        && ! array_key_exists($type, $this->explicitTypes)
                    )
                ) {
                    return;
                }

                break;
            }

            if ($i > 0) {
                $page->parent_slug = $this->items[$i - 1]->full_slug;

                $page->full_slug = $page->parent_slug . '/' . $page->slug;
            } else {
                $page->full_slug = $page->slug;
            }

            $parentId = $page->id;

            $this->items[$i] = $page;
        }
    }

    /**
     * Set a dynamic route.
     *
     * @return void
     */
    protected function setRoute()
    {
        if (! $this->segmentsCount) {
            $this->router->get($this->pathPrefix, [$this->homeController, 'index']);

            return;
        }

        $this->setPages();

        if ($this->detectRoute()) {
            $this->setInstances();
        }
    }

    /**
     * Detect dynamic route.
     *
     * @return bool
     */
    protected function detectRoute()
    {
        if (empty($page = end($this->items))) {
            return false;
        }

        if ($this->setPageRoute($page)) {
            return true;
        }

        if ($this->setExplicitRoute($page)) {
            return true;
        }

        if ($this->setImplicitRoute($page)) {
            return true;
        }

        return false;
    }

    /**
     * Set the page route.
     *
     * @param  \App\Models\Page  $page
     * @return bool
     */
    protected function setPageRoute(Page $page)
    {
        if (array_key_exists($page->type, $this->implicitTypes)
            || $this->segmentsCount > ($itemsCount = count($this->items))
        ) {
            return false;
        }

        $this->binders = array_fill(0, $itemsCount, $this->unbinder);

        $this->binders[array_key_last($this->binders)] = $page;

        return $this->setCurrentRoute($page->type, 'index');
    }

    /**
     * Set the explicit route.
     *
     * @param  \App\Models\Page  $page
     * @return bool
     */
    protected function setExplicitRoute(Page $page)
    {
        if (! array_key_exists($page->type, $this->explicitTypes)
            || $this->segmentsCount - ($itemsCount = count($this->items)) > 1
        ) {
            return false;
        }

        $this->binders = array_fill(0, $itemsCount - 1, $this->unbinder);

        $this->binders[] = $page;
        $this->binders[] = last($this->segments);

        return $this->setCurrentRoute($page->type, 'show');
    }

    /**
     * Set the implicit route.
     *
     * @param  \App\Models\Page  $page
     * @return bool
     */
    protected function setImplicitRoute(Page $page)
    {
        if (! array_key_exists($page->type, $this->implicitTypes)
            || $this->segmentsCount - ($itemsCount = count($this->items)) > 1
        ) {
            return false;
        }

        $model = (new $this->implicitTypes[$page->type])->findOrFail($page->type_id);

        $this->binders = array_fill(0, count($this->items) - 1, $this->unbinder);

        $this->binders[] = [$page, $model];

        if ($this->segmentsCount == $itemsCount) {
            return $this->setCurrentRoute($model->type, 'index');
        }

        if (! array_key_exists($model->type, $this->implicitTypes)) {
            $this->binders[] = last($this->segments);

            return $this->setCurrentRoute($model->type, 'show');
        }

        return $this->setDeepImplicitRoute($model);
    }

    /**
     * Set the deep implicit route.
     *
     * @param  \App\Models\Abstracts\Model  $implicitModel
     * @return bool
     */
    protected function setDeepImplicitRoute($implicitModel)
    {
        $model = new $this->implicitTypes[$implicitModel->type];

        if (! method_exists($model, 'bySlug')) {
            return false;
        }

        $model = $model->bySlug(last($this->segments), $implicitModel->id)->firstOrFail();

        $this->binders[] = $model;

        return $this->setCurrentRoute($model->type, 'index');
    }

    /**
     * Set the current route.
     *
     * @param  string  $type
     * @param  string|null  $defaultMethod
     * @return bool
     */
    protected function setCurrentRoute($type, $defaultMethod = null)
    {
        $path = '';

        foreach ($this->binders as $key => $binder) {
            $path .= ($binder == $this->unbinder
                    ? $this->segments[$key]
                    : '{bind' . ($key) . '}'
                ) . '/';

            $key = 'bind' . $key;

            if ($binder != $this->unbinder) {
                $this->router->bind($key, function () use ($binder) {
                    return $binder;
                });
            }
        }

        $typeParts = explode('@', $type);

        $method = $typeParts[1] ?? $defaultMethod;

        $route = strtolower($this->request->method());

        if (array_key_exists($route, $this->requestMethods)
            && array_key_exists(
                $type = "{$typeParts[0]}@{$method}",
                $types = $this->requestMethods[$route]
            )
        ) {
            $method = $types[$type];
        } else {
            $route = 'get';
        }

        $this->router->$route($this->pathPrefix . $path, [
            'uses' => $this->getControllerPath($typeParts[0]) . '@' . $method
        ]);

        return true;
    }

    /**
     * Set route instances.
     *
     * @return void
     */
    protected function setInstances()
    {
        $this->app->instance('breadcrumb', new Collection($this->items));
    }

    /**
     * Get the controller path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getControllerPath($path)
    {
        $namespace = '';

        $path = explode('.', $path);

        if (($pathCount = count($path)) > 1) {
            for ($i = 1; $i <= $pathCount; $i++) {
                if ($i == $pathCount) {
                    $namespace .= '\\Web' . Str::studly($path[$i - 1]);
                } else {
                    $namespace .= '\\' . Str::studly($path[$i - 1]);
                }
            }
        } else {
            $namespace .= 'Web' . Str::studly($path[0]);
        }

        return ltrim($namespace . 'Controller', '\\');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

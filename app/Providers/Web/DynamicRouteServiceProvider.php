<?php

namespace App\Providers\Web;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Models\Page;

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
    protected $homeController = 'WebHomeController@index';

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
     * The config repository instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * The prefix of the routes URI.
     *
     * @var string
     */
    protected $uriPrefix = '/';

    /**
     * The list of URL segments.
     *
     * @var array
     */
    protected $segments = [], $segmentsLeft = [];

    /**
     * The number of total URL segments.
     *
     * @var int
     */
    protected $segmentsCount = 0, $segmentsLeftCount = 0;

    /**
     * The array of page instances.
     *
     * @var array
     */
    protected $pages = [];

    /**
     * The number of total page instances.
     *
     * @var int
     */
    protected $pagesCount = 0;

    /**
     * The array of the listable types of the Page.
     *
     * @var array
     */
    protected $listableTypes = [];

    /**
     * The array of the implicit types of the Page.
     *
     * @var array
     */
    protected $implicitTypes = [];

    /**
     * The array of the explicit types of the Page.
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
     * The array of the types with an additional URIs.
     *
     * @var array
     */
    protected $tabs = [];

    /**
     * Define a dynamic routes.
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

                if ($this->config->get('language_isset')) {
                    $this->uriPrefix = $this->config->get('app.language') . '/';
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
     * Set router configuration.
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

        $this->tabs = (array) $this->config->get('cms.tabs', []);
    }

    /**
     * Build a new routes.
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
                if (count($this->pages) < 1
                    || (! in_array($type = $this->pages[$i - 1]->type, $this->listableTypes)
                        && ! array_key_exists($type, $this->explicitTypes)
                        && ! array_key_exists($type, $this->tabs)
                    )
                ) {
                    return;
                }

                break;
            }

        $page->original_slug = $page->slug;

            if ($i > 0) {
                $page->parent_slug = $this->pages[$i - 1]->slug;

                $page->slug = $page->parent_slug . '/' . $page->slug;
            }

            $parentId = $page->id;

            $this->pages[$i] = $page;
        }
    }

    /**
     * Set route by URL segments.
     *
     * @return void
     */
    protected function setRoute()
    {
        if (! $this->segmentsCount) {
            $this->router->get($this->uriPrefix, [
                'uses' => $this->homeController
            ]);

            return;
        }

        $this->setPages();

        $this->detectRoute();
    }

    /**
     * Detect route by URL segments.
     *
     * @return void
     */
    protected function detectRoute()
    {
        if (empty($page = end($this->pages))) {
            return;
        }

        $this->segmentsLeft = array_slice(
            $this->segments, $this->pagesCount = count($this->pages)
        );

        if (($this->segmentsLeftCount = count($this->segmentsLeft)) > 2) {
            return;
        }

        if ($this->setPageRoute($page)) {
            return;
        }

        $slug = current($this->segmentsLeft);

        if ($this->setExplicitRoute($page, $slug)) {
            return;
        }

        $this->setImplicitRoute($page, $slug);
    }

    /**
     * Set the page route.
     *
     * @param  \Models\Page  $page
     * @return bool
     */
    protected function setPageRoute(Page $page)
    {
        if (! array_key_exists($page->type, $this->implicitTypes)
            && ! $this->segmentsLeftCount
        ) {
            return $this->setCurrentRoute($page->type, [$page], 'index');
        }

        return false;
    }

    /**
     * Set the explicit route.
     *
     * @param  \Models\Page  $page
     * @param  string  $slug
     * @return bool
     */
    protected function setExplicitRoute(Page $page, $slug)
    {
        if ($slug && array_key_exists($page->type, $this->explicitTypes)) {
            return $this->setCurrentRoute($page->type, [$page, $slug], 'show');
        }

        return false;
    }

    /**
     * Set the implicit route.
     *
     * @param  \Models\Page  $page
     * @param  string  $slug
     * @return bool
     */
    protected function setImplicitRoute(Page $page, $slug)
    {
        if (! array_key_exists($page->type, $this->implicitTypes)) {
            return false;
        }

        $model = (new $this->implicitTypes[$page->type])->findOrFail($page->type_id);

        if (! $slug) {
            return $this->setCurrentRoute($model->type, [
                $page, $model
            ], 'index', $this->pagesCount);
        }

        if (! array_key_exists($model->type, $this->implicitTypes)) {
            return $this->setCurrentRoute($model->type, [$page, $slug], 'show');
        }

        return $this->setDeepImplicitRoute($model, $slug);
    }

    /**
     * Set the deep implicit route.
     *
     * @param  \Models\Abstracts\Model  $implicitModel
     * @param  string  $slug
     * @return bool
     */
    protected function setDeepImplicitRoute($implicitModel, $slug)
    {
        $model = new $this->implicitTypes[$implicitModel->type];

        if (! method_exists($model, 'bySlug')) {
            return false;
        }

        $model = $model->bySlug($slug, $implicitModel->id)->firstOrFail();

        return $this->setCurrentRoute($model->type, [
            $model, $implicitModel
        ], 'index');
    }

    /**
     * Set the current route.
     *
     * @param  string  $type
     * @param  array  $parameters
     * @param  string|null  $defaultMethod
     * @param  int  $fakeBind
     * @return bool
     */
    protected function setCurrentRoute($type, array $parameters = [], $defaultMethod = null, $fakeBind = 0)
    {
        $paramsCount = count($parameters);

        if ($this->segmentsLeftCount == 2
            || ($this->segmentsLeftCount == $paramsCount
                && current($parameters) instanceof Page
            )
        ) {
            if (array_key_exists($type, $this->tabs)
                && (array_key_exists(
                        $tabKey = $tab = (string) end($this->segmentsLeft),
                        $tabs = (array) $this->tabs[$type]
                    )
                    || is_int($tabKey = key($tabs))
                )
            ) {
                $defaultMethod = $this->tabs[$type][$tabKey];

                $parameters[] = $tab;
            } else {
                return false;
            }
        }

        $typeParts = explode('@', $type);

        $controller = $this->getControllerPath($typeParts[0]);

        $method = count($typeParts) == 2 ? $typeParts[1] : $defaultMethod;

        $segments = '';

        for ($i = 0; $i <= ($this->segmentsCount - ($paramsCount + 1)); $i++) {
            $segments .= $this->segments[$i] . '/';
        }

        foreach ($parameters as $key => $binder) {
            $segments .= '{bind'.$key.'}'.(($paramsCount - $fakeBind - 1) == $key ? '' : '/');

            $key = 'bind' . $key;

            $this->router->bind($key, function () use ($binder) {
                return $binder;
            });
        }

        $this->app->instance('breadcrumb', new Collection($this->pages));

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

        $this->router->$route($this->uriPrefix . $segments, [
            'uses' => $controller . '@' . $method
        ]);

        return true;
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

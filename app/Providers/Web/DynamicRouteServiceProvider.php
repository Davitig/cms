<?php

namespace App\Providers\Web;

use App\Models\Alt\Base\Model;
use App\Models\Page\Page;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class DynamicRouteServiceProvider extends ServiceProvider
{
    /**
     * The controller namespace for the dynamic routes.
     *
     * @var string
     */
    protected string $namespace = 'App\Http\Controllers\Web';

    /**
     * The Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected Request $request;

    /**
     * The router instance.
     *
     * @var \Illuminate\Routing\Router
     */
    protected Router $router;

    /**
     * The list of router binders.
     *
     * @var array
     */
    protected array $binders = [];

    /**
     * The unbinder keyword.
     *
     * @var string
     */
    protected string $unbinder = '{{unbind}}';

    /**
     * The prefix of the route path.
     *
     * @var string
     */
    protected string $pathPrefix = '';

    /**
     * The list of URL segments.
     *
     * @var array
     */
    protected array $segments = [];

    /**
     * The number of total URL segments.
     *
     * @var int
     */
    protected int $segmentsCount = 0;

    /**
     * The array of model items.
     *
     * @var array
     */
    protected array $items = [];

    /**
     * The array of the listable types.
     *
     * @var array
     */
    protected array $listableTypes = [];

    /**
     * The array of the implicit types.
     *
     * @var array
     */
    protected array $implicitTypes = [];

    /**
     * The array of the explicit types.
     *
     * @var array
     */
    protected array $explicitTypes = [];

    /**
     * The array of the types that will allow specific requests.
     *
     * @var array
     */
    protected array $requestMethods = [];

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function boot(Repository $config): void
    {
        if ($config->get('_cms.activated')) {
            return;
        }

        $this->request = $this->app['request'];

        $this->segments = $this->request->segments();

        if (current($this->segments) == $config->get('_app.language')) {
            array_shift($this->segments);
        }

        if (! $this->segmentsCount = count($this->segments)) {
            return;
        }

        $this->router = $this->app['router'];

        foreach ($this->router->getRoutes()->getRoutes() as $route) {
            if ($route->matches($this->request)) {
                return;
            }
        }

        if ($config->get('_app.language_selected')) {
            $this->pathPrefix = $config->get('_app.language') . '/';
        }

        $this->listableTypes = $config->get('cms.pages.listable', []);

        $this->implicitTypes = $config->get('cms.pages.implicit', []);

        $this->explicitTypes = $config->get('cms.pages.explicit', []);

        $this->requestMethods = $config->get('cms.methods', []);

        $this->build();
    }

    /**
     * Build a new route.
     *
     * @return void
     */
    public function build(): void
    {
        $this->router->namespace($this->namespace)
            ->middleware('web')
            ->group(fn () => $this->setRoute());
    }

    /**
     * Set pages.
     *
     * @return void
     */
    protected function setPages(): void
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

                return;
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
    protected function setRoute(): void
    {
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
    protected function detectRoute(): bool
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
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    protected function setPageRoute(Page $page): bool
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
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    protected function setExplicitRoute(Page $page): bool
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
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    protected function setImplicitRoute(Page $page): bool
    {
        if (! array_key_exists($page->type, $this->implicitTypes)
            || ($this->segmentsCount - ($itemsCount = count($this->items))) > 1
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
     * @param  \App\Models\Alt\Base\Model  $implicitModel
     * @return bool
     */
    protected function setDeepImplicitRoute(Model $implicitModel): bool
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
    protected function setCurrentRoute(string $type, ?string $defaultMethod = null): bool
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
            && array_key_exists($typeParts[0], $types = $this->requestMethods[$route])
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
    protected function setInstances(): void
    {
        $this->app->instance('breadcrumb', new Collection($this->items));
    }

    /**
     * Get the controller path.
     *
     * @param  string  $path
     * @return string
     */
    protected function getControllerPath(string $path): string
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
}

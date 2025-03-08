<?php

namespace App\Support;

use App\Models\Alt\Contracts\Collection as CollectionContract;
use App\Models\Page\Page;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class DynamicRouteRegistrar
{
    /**
     * The controller namespace for the dynamic routes.
     *
     * @var string
     */
    protected string $namespace = 'App\Http\Controllers\Web';

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
     * The array of the types that will allow specified requests.
     *
     * @var array
     */
    protected array $typeMethods = [];

    /**
     * The request method.
     *
     * @var string
     */
    protected string $requestMethod;

    /**
     * The array of the types that will allow adding tab like URIs.
     *
     * @var array
     */
    protected array $tabs = [];

    /**
     * The action method of the requested active tab.
     *
     * @var string|null
     */
    protected ?string $tabActionMethod = null;

    /**
     * Create a dynamic route registrar.
     */
    public function __construct(protected Application $app) {}

    /**
     * Handle the dynamic routes.
     *
     * @return void
     */
    public function handle(): void
    {
        $config = $this->app['config'];

        if ($config->get('_cms.activated') || $this->app->runningInConsole()) {
            return;
        }

        $request = $this->app['request'];

        $this->segments = $request->segments();

        if (current($this->segments) == ($language = $config->get('_app.language'))) {
            array_shift($this->segments);
        }

        if (! $this->segmentsCount = count($this->segments)) {
            return;
        }

        $this->checkLanguageForMaintenance($language);

        $this->router = $this->app['router'];

        foreach ($this->router->getRoutes()->getRoutes() as $route) {
            if ($route->matches($request)) {
                return;
            }
        }

        if ($config->get('_app.language_selected')) {
            $this->pathPrefix = $language;
        }

        $this->listableTypes = $config->get('cms.pages.listable', []);

        $this->implicitTypes = $config->get('cms.pages.implicit', []);

        $this->explicitTypes = $config->get('cms.pages.explicit', []);

        $this->typeMethods = $config->get('cms.type_methods', []);

        $this->requestMethod = strtolower($request->method());

        $this->tabs = $config->get('cms.tabs', []);

        $this->build(fn () => $this->setRoute());
    }

    /**
     * Build a new route.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public function build(Closure $callback): void
    {
        $this->router->namespace($this->namespace)
            ->middleware('web')
            ->group($callback);
    }

    /**
     * Set a dynamic route.
     *
     * @return void
     */
    protected function setRoute(): void
    {
        $this->setPages();

        if ($this->detectPageRoute()) {
            $this->setInstances();
        }
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
     * Reset the binders' array by unbinder with the specified length.
     *
     * @param  int  $length
     * @return void
     */
    protected function resetBinders(int $length = 0): void
    {
        $this->binders = array_fill(0, $length, $this->unbinder);
    }

    /**
     * Detect dynamic page route.
     *
     * @return bool
     */
    protected function detectPageRoute(): bool
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
        if (array_key_exists($page->type, $this->implicitTypes)) {
            return false;
        }

        $segmentsLeft = ($this->segmentsCount - ($itemsCount = count($this->items)));

        $this->resetBinders($itemsCount - 1);

        $this->binders[] = $page;

        if ($segmentsLeft && ! $this->bindTab($page->type, 'index', $segmentsLeft)) {
            return false;
        }

        $this->setCurrentRoute($page->type, $page->template ?: 'index');

        return true;
    }

    /**
     * Set the explicit route.
     *
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    protected function setExplicitRoute(Page $page): bool
    {
        if (! array_key_exists($page->type, $this->explicitTypes)) {
            return false;
        }

        $segmentsLeft = ($this->segmentsCount - ($itemsCount = count($this->items)));

        $this->resetBinders($itemsCount - 1);

        $this->binders[] = $page;
        $this->binders[] = $this->segments[$this->segmentsCount - $segmentsLeft];

        if ($segmentsLeft > 1
            && ! $this->bindTab($page->type, 'show', $segmentsLeft - 1)
        ) {
            return false;
        }

        $this->setCurrentRoute($page->type, 'show');

        return true;
    }

    /**
     * Set the implicit route.
     *
     * @param  \App\Models\Page\Page  $page
     * @return bool
     */
    protected function setImplicitRoute(Page $page): bool
    {
        if (! array_key_exists($page->type, $this->implicitTypes)) {
            return false;
        }

        $model = (new $this->implicitTypes[$page->type])->findOrFail($page->type_id);

        $segmentsLeft = ($this->segmentsCount - ($itemsCount = count($this->items)));

        $this->resetBinders($itemsCount - 1);

        $this->binders[] = [$page, $model];

        if (! $segmentsLeft || $this->bindTab($model->type, 'index', $segmentsLeft)) {
            $this->setCurrentRoute($model->type, 'index');

            return true;
        }

        if (! array_key_exists($model->type, $this->implicitTypes)) {
            $this->binders[] = $this->segments[$this->segmentsCount - $segmentsLeft];

            if ($segmentsLeft > 1
                && ! $this->bindTab($model->type, 'show', $segmentsLeft - 1)
            ) {
                return false;
            }

            $this->setCurrentRoute($model->type, 'show');

            return true;
        }

        return $this->setDeepImplicitRoute($model, $segmentsLeft);
    }

    /**
     * Set the deep implicit route.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $implicitModel
     * @param  int  $segmentsLeft
     * @return bool
     */
    protected function setDeepImplicitRoute(Model $implicitModel, int $segmentsLeft): bool
    {
        $model = new $this->implicitTypes[$implicitModel->type];

        if (! $model instanceof CollectionContract) {
            return false;
        }

        $model = $model->bySlug(
            $this->segments[$this->segmentsCount - $segmentsLeft], $implicitModel->id
        )->firstOrFail();

        $this->binders[] = $model;

        if ($segmentsLeft > 1
            && ! $this->bindTab($model->type, 'index', $segmentsLeft - 1)
        ) {
            return true;
        }

        $this->setCurrentRoute($model->type, 'index');

        return true;
    }

    /**
     * Set the specified type tab into the binders.
     *
     * @param  string  $type
     * @param  string  $typeMethod
     * @param  int  $segmentsLeft
     * @return bool
     */
    protected function bindTab(string $type, string $typeMethod, int $segmentsLeft): bool
    {
        if (! array_key_exists($this->requestMethod, $this->tabs) || ! $segmentsLeft) {
            return false;
        }

        $type .= '@' . $typeMethod;

        foreach ($this->tabs[$this->requestMethod] as $key => $value) {
            if (! str_contains($key, '@')) {
                $this->tabs[$this->requestMethod][$key . '@index'] = $value;
                unset($this->tabs[$this->requestMethod][$key]);
            }
        }

        if (! array_key_exists($type, $this->tabs[$this->requestMethod])) {
            return false;
        }

        $publicTabs = array_slice(
            $this->segments, count($this->segments) - $segmentsLeft, $segmentsLeft
        );

        foreach ($this->tabs[$this->requestMethod][$type] as $path => $method) {
            $tabs = [];

            $optionalParam = (int) str($path)->afterLast('/')->contains('?');

            $localTabs = explode('/', $path);

            if (($paramsLeft = (count($localTabs) - count($publicTabs)))
                && $paramsLeft - $optionalParam
            ) {
                continue;
            }

            for ($i = 0; $i < $segmentsLeft; $i++) {
                if ($localTabs[$i] == $publicTabs[$i]
                    || str($localTabs[$i])->containsAll(['{', '}'])
                ) {
                    $tabs[] = $publicTabs[$i];
                }
            }

            if (($paramsLeft = (count($localTabs) - count($tabs)))
                && $paramsLeft - $optionalParam
            ) {
                continue;
            }

            foreach ($tabs as $tab) {
                $this->binders[] = $tab;
            }

            $this->tabActionMethod = $this->tabs[$this->requestMethod][$type][$path];

            return true;
        }

        return false;
    }

    /**
     * Set the current route.
     *
     * @param  string  $type
     * @param  string  $actionMethod
     * @return void
     */
    protected function setCurrentRoute(string $type, string $actionMethod): void
    {
        $routeMethod = $this->requestMethod;

        if (is_null($this->tabActionMethod)) {
            if (array_key_exists($routeMethod, $this->typeMethods)
                && array_key_exists($type, $types = $this->typeMethods[$routeMethod])
            ) {
                $actionMethod = $types[$type];
            } else {
                $routeMethod = strtolower(Request::METHOD_GET);
            }
        } else {
            $actionMethod = $this->tabActionMethod;
        }

        $this->router->$routeMethod(
            $this->bindRoutesAndGetPath(), $this->getControllerAction($type, $actionMethod)
        );
    }

    /**
     * Bind routes and get a path.
     *
     * @return string
     */
    protected function bindRoutesAndGetPath(): string
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

        return trim($this->pathPrefix . '/' . $path, '/');
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
     * Get a controller-based route action array.
     *
     * @param  string  $type
     * @param  string  $method
     * @return array
     */
    protected function getControllerAction(string $type, string $method): array
    {
        if (array_key_exists($type, $controllers = cms_config('controllers'))) {
            return [$controllers[$type], $method];
        }

        return ['uses' => 'Web' . str($type)->studly() . 'Controller@' . $method];
    }

    /**
     * Check the language for maintenance.
     *
     * @param  string|null  $language
     * @return void
     */
    protected function checkLanguageForMaintenance(?string $language): void
    {
        if (is_null($language) || ! array_key_exists($language, languages(true))) {
            throw new ServiceUnavailableHttpException;
        }
    }
}

<?php

namespace App\Http\Middleware\Web;

use App\Models\Page\Page;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class WebHandleDynamicRoute
{
    /**
     * The controller namespace for the dynamic route.
     *
     * @var string
     */
    protected string $namespace = 'App\Http\Controllers\Web';

    /**
     * The array of request path segments.
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
     * The current request method.
     *
     * @var string
     */
    protected string $requestMethod;

    /**
     * The array of types that will allow specified request methods.
     *
     * @var array
     */
    protected array $typeRequestMethods = [];

    /**
     * The array of types that will allow additional tab like URIs.
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
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(
        protected Router $router, protected Route $route, protected Repository $config
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // remove any parameter from being passed to controller
        $this->route->forgetParameter('any');

        $originalControllerClass = $this->route->getControllerClass();

        $this->build($request->segments(), $request->method());

        $request->setRouteResolver(fn () => $this->route);

        return $this->runRouteWithinMiddleware($request, $next, $originalControllerClass);
    }

    /**
     * Run the given request within middleware.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  $originalControllerClass
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function runRouteWithinMiddleware(
        Request $request, Closure $next, ?string $originalControllerClass
    ): Response
    {
        if ($originalControllerClass == $this->route->getControllerClass()) {
            return $next($request);
        }

        $middleware = $this->route->controllerMiddleware();

        if (empty($middleware)) {
            return $next($request);
        }

        $middleware = $this->router->resolveMiddleware($middleware);

        return app(Pipeline::class)
            ->send($request)
            ->through(Router::uniqueMiddleware($middleware))
            ->then(fn ($request) => $next($request));
    }

    /**
     * Build the dynamic route.
     *
     * @param  array  $segments
     * @param  string  $requestMethod
     * @return bool
     */
    protected function build(array $segments, string $requestMethod): bool
    {
        if (! $segmentsCount = count($segments)) {
            return false;
        }

        if (reset($segments) == language()->active()) {
            array_shift($segments);

            $segmentsCount--;
        }

        $this->segments = $segments;

        $this->segmentsCount = $segmentsCount;

        $this->requestMethod = $requestMethod;

        $this->typeRequestMethods = (array) $this->config->get('cms.type_request_methods');

        $this->tabs = (array) $this->config->get('cms.tabs');

        $this->setDynamicRoute();

        return true;
    }

    /**
     * Set a dynamic route.
     *
     * @return void
     */
    protected function setDynamicRoute(): void
    {
        if (empty($pages = $this->getPages())) {
            return;
        }

        if (($result = $this->setListableRoute($pages)) !== false) {
            if ($result === true) {
                $this->setSharedInstances($pages);
            }

            return;
        }

        if ($this->setPageRoute($pages) === true) {
            $this->setSharedInstances($pages);
        }
    }

    /**
     * Get the pages.
     *
     * @return array
     */
    protected function getPages(): array
    {
        $pages = [];

        $parentId = 0;

        for ($i = 0; $i < $this->segmentsCount; $i++) {
            $page = Page::publicDynamicRoute($this->segments[$i], $parentId)->first();

            if (is_null($page)) {
                return $pages;
            }

            if ($i > 0) {
                $page->parent_slug = $pages[$i - 1]->url_path;

                $page->url_path = $page->parent_slug . '/' . $page->slug;
            } else {
                $page->url_path = $page->slug;
            }

            $parentId = $page->id;

            $pages[$i] = $page;
        }

        return $pages;
    }

    /**
     * Set the page route.
     *
     * @param  array  $pages
     * @return bool|null
     */
    protected function setPageRoute(array $pages): ?bool
    {
        if (! ($page = end($pages)) instanceof Page) {
            return false;
        }

        $segmentsLeft = $this->segmentsCount - count($pages);

        $tabs = [];

        if ($segmentsLeft && ! $tabs = $this->bindTab($page->type, 'index', $segmentsLeft)) {
            if ($this->setExtendedPageRoute($pages) === true) {
                return true;
            }

            return null;
        }

        $this->setRoute($page->type, 'index', array_merge([$pages], $tabs));

        return true;
    }

    /**
     * Set the extended page route.
     *
     * @param  array  $pages
     * @return bool|null
     */
    protected function setExtendedPageRoute(array $pages): ?bool
    {
        if (! ($page = end($pages)) instanceof Page ||
            ! array_key_exists($page->type, (array) $this->config->get('cms.pages.extended')) ||
            ! $segmentsLeft = ($this->segmentsCount - count($pages))) {
            return false;
        }

        $tabs = [];

        if ($segmentsLeft > 1 &&
            ! $tabs = $this->bindTab($page->type, 'show', $segmentsLeft - 1)) {
            return null;
        }

        $this->setRoute($page->type, 'show', array_merge(
            [$pages, $this->segments[$this->segmentsCount - $segmentsLeft]], $tabs
        ));

        return true;
    }

    /**
     * Set the listable route.
     *
     * @param  array  $pages
     * @return bool|null
     */
    protected function setListableRoute(array $pages): ?bool
    {
        if (! ($page = end($pages)) instanceof Page) {
            return false;
        }

        $listableType = null;

        foreach ((array) $this->config->get('cms.pages.listable') as $type => $values) {
            if (array_key_exists($page->type, (array) $values)) {
                $listableType = $type;

                break;
            }
        }

        if (is_null($listableType)) {
            return false;
        }

        $model = $this->config->get('cms.listable')[$listableType]['model'] ?? null;

        if (is_null($model)) {
            throw new InvalidArgumentException("Listable type '$listableType.model' not provided.");
        }

        $model = (new $model)::publicDynamicRoute($page->type_id, $page->type)->first();

        if (is_null($model)) {
            return null;
        }

        if ($this->setListableTypeRoute($pages, $model) === true) {
            return true;
        }

        return null;
    }

    /**
     * Set the listable type route.
     *
     * @param  array  $pages
     * @param  \Illuminate\Database\Eloquent\Model  $listableModel
     * @return bool|null
     */
    protected function setListableTypeRoute(array $pages, Model $listableModel): ?bool
    {
        $segmentsLeft = $this->segmentsCount - count($pages);

        $tabs = [];

        $parameters = [$pages];
        $parameters[] = $listableModel;

        if (! $segmentsLeft ||
            $tabs = $this->bindTab($listableModel->type, 'index', $segmentsLeft)) {
            $parameters = array_merge($parameters, $tabs);

            $this->setRoute($listableModel->type, 'index', $parameters);

            return true;
        }

        $parameters[] = $this->segments[$this->segmentsCount - $segmentsLeft];

        if ($segmentsLeft > 1 &&
            ! $tabs = $this->bindTab($listableModel->type, 'show', $segmentsLeft - 1)) {
            return null;
        }

        $parameters = array_merge($parameters, $tabs);

        $this->setRoute($listableModel->type, 'show', $parameters);

        return true;
    }

    /**
     * Set the specified type tab.
     *
     * @param  string  $type
     * @param  string  $typeMethod
     * @param  int  $segmentsLeft
     * @return array
     */
    protected function bindTab(string $type, string $typeMethod, int $segmentsLeft): array
    {
        if (! array_key_exists($this->requestMethod, $this->tabs) || ! $segmentsLeft) {
            return [];
        }

        foreach ($this->tabs[$this->requestMethod] as $key => $value) {
            if (! str_contains($key, '@')) {
                $this->tabs[$this->requestMethod][$key . '@index'] = $value;

                unset($this->tabs[$this->requestMethod][$key]);
            }
        }

        $type .= '@' . $typeMethod;

        if (! array_key_exists($type, $this->tabs[$this->requestMethod])) {
            return [];
        }

        $publicTabs = array_slice(
            $this->segments, $this->segmentsCount - $segmentsLeft, $segmentsLeft
        );

        foreach ((array) $this->tabs[$this->requestMethod][$type] as $path => $method) {
            $tabs = [];

            if (is_numeric($path)) {
                $path = $method;

                $method = str($method)->camel()->toString();
            }

            $optionalParam = (int) str($path)->afterLast('/')->contains('?');

            $localTabs = explode('/', $path);

            if (($paramsLeft = (count($localTabs) - count($publicTabs))) &&
                $paramsLeft - $optionalParam) {
                continue;
            }

            for ($i = 0; $i < $segmentsLeft; $i++) {
                if ($localTabs[$i] == $publicTabs[$i] ||
                    str($localTabs[$i])->containsAll(['{', '}'])) {
                    $tabs[] = $publicTabs[$i];
                }
            }

            if (($paramsLeft = (count($localTabs) - count($tabs))) &&
                $paramsLeft - $optionalParam) {
                continue;
            }

            $this->tabActionMethod = $method;

            return $tabs;
        }

        return [];
    }

    /**
     * Get the type request method action.
     *
     * @param  string  $type
     * @param  string  $actionMethod
     * @return string|null
     */
    protected function getTypeRequestMethodAction(string $type, string $actionMethod): ?string
    {
        if (! array_key_exists($this->requestMethod, $this->typeRequestMethods) ||
            empty($types = $this->typeRequestMethods[$this->requestMethod])) {
            return null;
        }

        foreach ($types as $action => $typeRequestMethod) {
            if (! str_contains($action, '@')) {
                $action .= '@index';
            }

            if ($action == $type . '@' . $actionMethod) {
                return $typeRequestMethod;
            }
        }

        return null;
    }

    /**
     * Set the current route.
     *
     * @param  string  $type
     * @param  string  $actionMethod
     * @param  array  $parameters
     * @return void
     */
    protected function setRoute(string $type, string $actionMethod, array $parameters): void
    {
        if (is_null($this->tabActionMethod)) {
            if (! is_null($typeRequestMethodAction = $this->getTypeRequestMethodAction(
                $type, $actionMethod))) {
                $actionMethod = $typeRequestMethodAction;
            } elseif ($this->requestMethod != SymfonyRequest::METHOD_GET &&
                $this->requestMethod != SymfonyRequest::METHOD_HEAD) {
                throw new MethodNotAllowedHttpException([
                    SymfonyRequest::METHOD_GET, SymfonyRequest::METHOD_HEAD
                ]);
            }
        } else {
            $actionMethod = $this->tabActionMethod;
        }

        $this->route->setAction($this->getControllerAction($type, $actionMethod));

        foreach ($parameters as $key => $value) {
            $this->route->setParameter($key, $value);
        }
    }

    /**
     * Set a shared instance.
     *
     * @param  array  $items
     * @return void
     */
    protected function setSharedInstances(array $items): void
    {
        app()->instance('breadcrumb', collect($items));
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
        return [
            'uses' => $action = $this->getController($type) . '@' . $method,
            'controller' => $action
        ];
    }

    /**
     * Get the controller path.
     *
     * @param  string  $type
     * @return string
     */
    protected function getController(string $type): string
    {
        if (array_key_exists($type, $controllers = cms_config('controllers'))) {
            return $controllers[$type];
        }

        return $this->namespace . '\\' . 'Web' . str($type)->singular()->studly() . 'Controller';
    }
}

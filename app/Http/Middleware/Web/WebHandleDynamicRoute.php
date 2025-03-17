<?php

namespace App\Http\Middleware\Web;

use App\Models\Collection;
use App\Models\Gallery\Gallery;
use App\Models\Page\Page;
use Closure;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class WebHandleDynamicRoute
{
    /**
     * The controller namespace for the dynamic route.
     *
     * @var string
     */
    protected string $namespace = 'App\Http\Controllers\Web';

    /**
     * The current route instance.
     *
     * @var \Illuminate\Routing\Route
     */
    protected Route $route;

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
     * The array of types that will allow specified request methods.
     *
     * @var array
     */
    protected array $typeMethods = [];

    /**
     * The current request method.
     *
     * @var string
     */
    protected string $requestMethod;

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
     * @param  \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(protected Repository $config) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->route = $request->route();

        $this->route->forgetParameter('any');

        $this->build($request->segments(), $request->method());

        $request->setRouteResolver(fn () => $this->route);

        return $next($request);
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
        if (! $this->segmentsCount = count($this->segments = $segments)) {
            return false;
        }

        $this->checkLanguageForMaintenance($language = language()->active());

        if (current($this->segments) == $language) {
            array_shift($this->segments);

            $this->segmentsCount--;
        }

        $this->typeMethods = (array) $this->config->get('cms.type_methods');

        $this->tabs = (array) $this->config->get('cms.tabs');

        $this->requestMethod = $requestMethod;

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

        $this->setBreadcrumb($pages);

        if ($this->setCollectionRoute($pages)) {
            return;
        }

        $this->setPageRoute($pages);
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
            $page = Page::publicDynamicRoute($this->segments[$i], $parentId)
                ->addQualifiedSelect('id', 'slug', 'type', 'template')
                ->first();

            if (is_null($page)) {
                return $pages;
            }

            if ($i > 0) {
                $page->parent_slug = $pages[$i - 1]->full_slug;

                $page->full_slug = $page->parent_slug . '/' . $page->slug;
            } else {
                $page->full_slug = $page->slug;
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
     * @return bool
     */
    protected function setPageRoute(array $pages): bool
    {
        if (! ($page = end($pages)) instanceof Page) {
            return false;
        }

        $segmentsLeft = $this->segmentsCount - count($pages);

        $tabs = [];

        if ($segmentsLeft && ! $tabs = $this->bindTab($page->type, 'index', $segmentsLeft)) {
            if ($this->setExtendedPageRoute($pages)) {
                return true;
            }

            return false;
        }

        $this->setRoute(
            $page->type, $page->template ?: 'index', array_merge([$pages], $tabs)
        );

        return true;
    }

    /**
     * Set the extended page route.
     *
     * @param  array  $pages
     * @return bool
     */
    protected function setExtendedPageRoute(array $pages): bool
    {
        if (! ($page = end($pages)) instanceof Page
            || ! array_key_exists(
                $page->type, (array) $this->config->get('cms.pages.extended')
            )) {
            return false;
        }

        $segmentsLeft = $this->segmentsCount - count($pages);

        $tabs = [];

        if ($segmentsLeft > 1
            && ! $tabs = $this->bindTab($page->type, 'show', $segmentsLeft - 1)) {
            return false;
        }

        $this->setRoute($page->type, 'show', array_merge(
            [$pages, $this->segments[$this->segmentsCount - $segmentsLeft]], $tabs
        ));

        return true;
    }

    /**
     * Set the collection route.
     *
     * @param  array  $pages
     * @return bool
     */
    protected function setCollectionRoute(array $pages): bool
    {
        if (! ($page = end($pages)) instanceof Page
            || ! array_key_exists(
                $page->type, (array) $this->config->get('cms.pages.collections')
            )) {
            return false;
        }

        $segmentsLeft = $this->segmentsCount - count($pages);

        $model = Collection::publicDynamicRoute(
            $page->type_id, $page->type
        )->addQualifiedSelect('*')->firstOrFail();

        $tabs = [];

        $parameters = [$pages];

        if (! $segmentsLeft
            || $tabs = $this->bindTab($model->type, 'index', $segmentsLeft)) {
            $parameters[] = $model;
            $parameters = array_merge($parameters, $tabs);

            $this->setRoute($model->type, 'index', $parameters);

            return true;
        }

        if (! array_key_exists($page->type, (array) cms_config())) {
            $parameters[] = $model;
            $parameters[] = $this->segments[$this->segmentsCount - $segmentsLeft];

            if ($segmentsLeft > 1
                && ! $tabs = $this->bindTab($model->type, 'show', $segmentsLeft - 1)) {
                return false;
            }

            $parameters = array_merge($parameters, $tabs);

            $this->setRoute($model->type, 'show', $parameters);

            return true;
        }

        return $this->setGalleryRoute($model, $segmentsLeft, $parameters);
    }

    /**
     * Set the deep collection route.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $collectionModel
     * @param  int  $segmentsLeft
     * @param  array  $parameters
     * @return bool
     */
    protected function setGalleryRoute(
        Model $collectionModel, int $segmentsLeft, array $parameters = []
    ): bool
    {
        $tabs = [];

        $parameters[] = $model = Gallery::publicDynamicRoute(
            $this->segments[$this->segmentsCount - $segmentsLeft], $collectionModel->id
        )->addQualifiedSelect('*')->firstOrFail();

        if ($segmentsLeft > 1
            && ! $tabs = $this->bindTab($model->type, 'index', $segmentsLeft - 1)) {
            return true;
        }

        $parameters = array_merge($parameters, $tabs);

        $this->setRoute($model->type, 'index', $parameters);

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

        $type .= '@' . $typeMethod;

        foreach ($this->tabs[$this->requestMethod] as $key => $value) {
            if (! str_contains($key, '@')) {
                $this->tabs[$this->requestMethod][$key . '@index'] = $value;
                unset($this->tabs[$this->requestMethod][$key]);
            }
        }

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

            if (($paramsLeft = (count($localTabs) - count($publicTabs)))
                && $paramsLeft - $optionalParam) {
                continue;
            }

            for ($i = 0; $i < $segmentsLeft; $i++) {
                if ($localTabs[$i] == $publicTabs[$i]
                    || str($localTabs[$i])->containsAll(['{', '}'])) {
                    $tabs[] = $publicTabs[$i];
                }
            }

            if (($paramsLeft = (count($localTabs) - count($tabs)))
                && $paramsLeft - $optionalParam) {
                continue;
            }

            $this->tabActionMethod = $method;

            return $tabs;
        }

        return [];
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
            if (array_key_exists($this->requestMethod, $this->typeMethods)
                && array_key_exists($type, $types = $this->typeMethods[$this->requestMethod])) {
                $actionMethod = $types[$type];
            } elseif ($this->requestMethod != SymfonyRequest::METHOD_GET
                && $this->requestMethod != SymfonyRequest::METHOD_HEAD) {
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
     * Set a breadcrumb as shared instance.
     *
     * @param  array  $pages
     * @return void
     */
    protected function setBreadcrumb(array $pages): void
    {
        app()->instance('breadcrumb', collect($pages));
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

        return $this->namespace . '\\' . 'Web' . str($type)->studly() . 'Controller';
    }

    /**
     * Throw service unavailable http exception if non-visible language is selected.
     *
     * @param  string|null  $language
     * @return void
     */
    protected function checkLanguageForMaintenance(?string $language): void
    {
        if (is_null($language) || ! language()->allVisible()->offsetExists($language)) {
            throw new ServiceUnavailableHttpException;
        }
    }
}

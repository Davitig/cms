<?php

namespace Tests\Feature;

use App\Http\Middleware\Web\WebHandleDynamicRoute;
use App\Models\Page\Page;
use Closure;
use Database\Factories\MenuFactory;
use Database\Factories\Page\PageFactory;
use Database\Factories\Page\PageLanguageFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

trait DynamicRoutesTrait
{
    /**
     * Create a new pages.
     *
     * @param  int|null  $times
     * @param  \Closure|null  $callback
     * @return array
     */
    protected function createPages(?int $times = null, ?Closure $callback = null): array
    {
        $menu = MenuFactory::new()->create();

        return [$menu, $this->createPage($menu->id, $times, $callback)];
    }

    /**
     * Create a new page.
     *
     * @param  int  $menuId
     * @param  int|null  $times
     * @param  \Closure|null  $callback
     * @return \App\Models\Page\Page|\Illuminate\Database\Eloquent\Collection
     */
    protected function createPage(int $menuId, ?int $times = null, ?Closure $callback = null):
    Page|Collection
    {
        return PageFactory::new()->count($times)->menuId($menuId)->has(
            PageLanguageFactory::times(language()->count())
                ->sequence(...apply_languages([])),
            'languages'
        )->when(! is_null($callback), $callback)->create();
    }

    /**
     * Create a subpages.
     *
     * @param  int  $menuId
     * @param  \App\Models\Page\Page|null  $page
     * @param  \Closure|null  $callback
     * @param  int  $maxSub
     * @return array
     */
    protected function createSubPages(
        int $menuId, ?Page $page = null, Closure $callback = null, int $maxSub = 3
    ): array
    {
        if (! is_null($page)) {
            $pages[] = $page;
        } else {
            $pages = [];
        }

        if ($maxSub < 1) {
            return $pages;
        }

        return array_merge($pages, $this->createSubPages($menuId, $this->createPage(
            $menuId, null,
            fn ($factory) => $factory->parentId($page?->id ?: 0)
                ->when(! is_null($callback), $callback)
        ), $callback, $maxSub - 1));
    }

    /**
     * Get the dynamic page route actions.
     *
     * @param  string  $path
     * @return array
     */
    protected function getDynamicPageRouteActions(string $path): array
    {
        $request = Request::createFromBase(Request::create($path));

        $request->setRouteResolver(function () use ($request) {
            return (new Route('GET', '{any}', []))->where('any', '.*')->bind($request);
        });

        $data = [];

        $middleware = (new WebHandleDynamicRoute($this->app['config']));

        $middleware->handle($request, function ($request) use (&$data) {
            $data['controller'] = str($request->route()->getActionName())
                ->afterLast('\\')->beforeLast('@')->toString();

            $data['method'] = $request->route()->getActionMethod();

            return response('');
        });

        return $data;
    }
}

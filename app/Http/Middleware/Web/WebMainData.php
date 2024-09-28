<?php

namespace App\Http\Middleware\Web;

use App\Support\TranslationCollection;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use Models\Menu;
use Models\Page;
use Models\Translation;

class WebMainData
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->shareSettings();

        $this->sharePages();

        $this->shareTranslations();

        return $next($request);
    }

    /**
     * Share the settings.
     *
     * @return void
     */
    protected function shareSettings()
    {
        view()->share([
            'webSettings' => new Collection(
                $this->app['db']->table('web_settings')->first()
            )
        ]);
    }

    /**
     * Share the pages.
     *
     * @return void
     */
    protected function sharePages()
    {
        $menu = (new Menu)->where('main', 1)->first(['id']);

        $pages = [];

        if (! is_null($menu)) {
            $pages = (new Page)->forPublic()
                ->where('menu_id', $menu->id)
                ->positionAsc()
                ->get();
        }

        $this->app->instance('pagesTree', make_model_sub_items($pages, ''));
    }

    /**
     * Share the translations.
     *
     * @return void
     */
    protected function shareTranslations()
    {
        $trans = new Translation;

        $transCollection = new TranslationCollection;

        if ($trans->count() <= (int) $this->app['config']->get('cms.trans_limit')) {
            $transCollection->setCollection(
                $trans->joinLanguage(true)->pluck('value', 'code')
            );
        }

        $this->app->instance('trans', $transCollection);

        view()->share('trans', $transCollection);
    }
}

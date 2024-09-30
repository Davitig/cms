<?php

namespace App\Http\Middleware\Web;

use App\Support\TranslationCollection;
use Closure;
use Illuminate\Support\Collection;
use Models\Translation;

class WebMainData
{
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
                app('db')->table('web_settings')->first()
            )
        ]);
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

        if ($trans->count() <= (int) cms_config('trans_limit')) {
            $transCollection->setCollection(
                $trans->joinLanguage(true)->pluck('value', 'code')
            );
        }

        app()->instance('trans', $transCollection);

        view()->share('trans', $transCollection);
    }
}

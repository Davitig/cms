<?php

namespace App\Http\Middleware\Web;

use App\Models\Translation;
use App\Support\TranslationCollection;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class WebViewData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
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
    protected function shareSettings(): void
    {
        view()->share(['webSettings' => new Collection(
            app('db')->table('web_settings')->first()
        )]);
    }

    /**
     * Share the translations.
     *
     * @return void
     */
    protected function shareTranslations(): void
    {
        $trans = new Translation;

        $transCollection = new TranslationCollection;

        if ($trans->count() <= (int) cms_config('trans_query_limit')) {
            $transCollection->setCollection(
                $trans->joinLanguage()->pluck('value', 'code')
            );
        }

        view()->share('trans', $transCollection);
    }
}

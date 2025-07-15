<?php

namespace App\View\Composers\Web;

use App\Models\Translation;
use App\Support\TranslationProvider;
use Illuminate\Contracts\View\View;

class WebTranslationComposer
{
    /**
     * The instance of the translation provider.
     *
     * @var \App\Support\TranslationProvider
     */
    protected TranslationProvider $trans;

    /**
     * Create a new view composer instance.
     */
    public function __construct()
    {
        $this->trans = $this->getTranslations();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('trans', $this->trans);
    }

    /**
     * Get the translations.
     *
     * @return \App\Support\TranslationProvider
     */
    protected function getTranslations(): TranslationProvider
    {
        $limit = cms_config('trans_query_limit');

        return new TranslationProvider(
            ! is_null($limit) ? (new Translation)->joinLanguage()
                ->when($limit, fn ($q) => $q->limit($limit))
                ->pluck('value', 'code')
                : null,
            (new Translation)->count()
        );
    }
}

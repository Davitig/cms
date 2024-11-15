<?php

namespace App\View\Composers\Web;

use App\Models\Translation;
use App\Support\TranslationCollection;
use Illuminate\Contracts\View\View;

class WebTranslationsComposer
{
    /**
     * The Collection instance of the translations.
     *
     * @var \App\Support\TranslationCollection
     */
    protected TranslationCollection $trans;

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
     * @return \App\Support\TranslationCollection
     */
    protected function getTranslations(): TranslationCollection
    {
        $model = new Translation;

        $trans = new TranslationCollection;

        if ($model->count() <= (int) cms_config('trans_query_limit')) {
            $trans->setCollection(
                $model->joinLanguage()->pluck('value', 'code')
            );
        }

        return $trans;
    }
}

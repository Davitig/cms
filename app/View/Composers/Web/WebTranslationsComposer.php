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
     * @var \App\Support\TranslationCollection|null
     */
    protected ?TranslationCollection $translations = null;

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\Contracts\View\View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        $view->with('trans', $this->getTranslations());
    }

    /**
     * Get the translations.
     *
     * @return \App\Support\TranslationCollection
     */
    protected function getTranslations(): TranslationCollection
    {
        if (is_null($this->translations)) {
            $trans = new Translation;

            $this->translations = new TranslationCollection;

            if ($trans->count() <= (int) cms_config('trans_query_limit')) {
                $this->translations->setCollection(
                    $trans->joinLanguage()->pluck('value', 'code')
                );
            }
        }

        return $this->translations;
    }
}

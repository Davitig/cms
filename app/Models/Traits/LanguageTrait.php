<?php

namespace App\Models\Traits;

use App\Models\Base\Builder;

trait LanguageTrait
{
    /**
     * Build a query by foreign model.
     *
     * @param  int  $id
     * @param  bool|string  $currentLang
     * @return \App\Models\Base\Builder
     */
    public function byForeign(int $id, bool|string $currentLang = true): Builder
    {
        return $this->foreignId($id)->whereLanguage($currentLang);
    }

    /**
     * Add a where "language_id" clause to the query.
     *
     * @param  bool|string  $currentLang
     * @return \App\Models\Base\Builder
     */
    public function whereLanguage(bool|string $currentLang = true): Builder
    {
        return $this->where('language_id', language($currentLang, 'id'));
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (isset($attributes['language_id'])) {
            return parent::create($attributes);
        }

        $models = [];

        foreach(languages() as $value) {
            $attributes['language_id'] = $value['id'];

            $models[] = parent::create($attributes);
        }

        return $models;
    }
}

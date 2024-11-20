<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Base\Builder;

trait LanguageTrait
{
    /**
     * Build a query by foreign model.
     *
     * @param  int  $id
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Base\Builder
     */
    public function byForeignLanguage(int $id, mixed $currentLang = true): Builder
    {
        return $this->foreignId($id)->whereLanguage($currentLang);
    }

    /**
     * Add a where "language_id" clause to the query.
     *
     * @param  mixed  $currentLang
     * @return \App\Models\Alt\Base\Builder
     */
    public function whereLanguage(mixed $currentLang = true): Builder
    {
        return $this->where(
            'language_id',
            is_int($currentLang) ? $currentLang : language($currentLang, 'id')
        );
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

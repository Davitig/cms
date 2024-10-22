<?php

namespace App\Models\Traits;

use App\Models\_Language;
use App\Models\Eloquent\Builder;
use Illuminate\Support\Str;

trait LanguageTrait
{
    /**
     * The _Language instance.
     *
     * @var \App\Models\_Language
     */
    protected _Language $languageModel;

    /**
     * Create a new language instance, related to this model.
     *
     * @return $this
     */
    public function setLanguage(): static
    {
        $this->languageModel = new _Language($this);

        return $this;
    }

    /**
     * Get the language instance, related to this model.
     *
     * @return \App\Models\_Language
     */
    public function getLanguage(): _Language
    {
        return $this->languageModel;
    }

    /**
     * Get the table associated with the _Language model.
     *
     * @return string
     */
    public function getLanguageTable(): string
    {
        return $this->languageTable;
    }

    /**
     * Get the fillable attributes for the _Language model.
     *
     * @return array
     */
    public function getLanguageFillable(): array
    {
        return (array) $this->languageFillable;
    }

    /**
     * Get the not updatable attributes for the _Language model.
     *
     * @return array
     */
    public function getLanguageNotUpdatable(): array
    {
        return (array) $this->languageNotUpdatable;
    }

    /**
     * Get the updatable attributes for the _Language model.
     *
     * @param  array  $attributes
     * @param  string|null  $exclude
     * @return array
     */
    public function getLanguageUpdatable(array $attributes = [], string $exclude = null): array
    {
        if (is_null($exclude)) {
            $notUpdatable = $this->getLanguageNotUpdatable();
        } else {
            $notUpdatable = (array) $this->{'notUpdatable' . ucfirst($exclude)};
        }

        $updatable = array_flip(array_diff(
            $this->getLanguageFillable(), $notUpdatable
        ));

        return array_intersect_key($attributes, $updatable);
    }

    /**
     * Add a "*_languages" join to the query.
     *
     * @param  bool|string  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Eloquent\Builder
     */
    public function joinLanguage(bool|string $currentLang = true, array|string $columns = []):
    Builder
    {
        $table = $this->getTable();
        $languageTable = $this->getLanguageTable();
        $languageKey = Str::singular($languageTable) . '_id';

        return $this->leftJoin($languageTable,
            function ($q) use ($table, $languageTable, $currentLang) {
                return $q->on("{$table}.id", "{$languageTable}.{$this->getForeignKey()}")
                    ->when($currentLang !== false, function ($q) use ($languageTable, $currentLang) {
                        return $q->where(
                            "{$languageTable}.language_id",
                            language($currentLang)['id'] ?? $currentLang
                        );
                    });
            })->addSelect(array_merge(((array) $columns) ?: ["{$languageTable}.*"], [
            "{$languageTable}.id as {$languageKey}", "{$table}.*"
        ]))->selectSub(function ($q) use ($languageTable) {
            return $q->from('languages')
                ->whereColumn('languages.id', $languageTable . '.language_id')
                ->select('language');
        }, 'language');
    }

    /**
     * Add a where "language" clause to the query.
     *
     * @return \App\Models\Eloquent\Builder
     */
    public function currentLanguage(): Builder
    {
        return $this->where("{$this->getLanguageTable()}.language_id", language(true, 'id'));
    }

    /**
     * Update the Eloquent model with its related _Language model.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @param  string|null  $exclude
     * @return bool
     */
    public function update(array $attributes = [], array $options = [], string $exclude = null): bool
    {
        $result = parent::update($attributes, $options, $exclude);

        $this->languageModel->where($this->getForeignKey(), $this->getKey())
            ->where('language_id', language(true, 'id'))
            ->update($this->getLanguageUpdatable($attributes, $exclude));

        return $result;
    }

    /**
     * Save a new model with its related _Language model and return the instance.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function createLanguage(array $attributes = []): array
    {
        $newLanguages = [];

        $attributes[$this->getForeignKey()] = $this->getKey();

        foreach(languages() as $value) {
            $this->setLanguage();

            $attributes['language_id'] = $value['id'];

            $newLanguages[] = $this->languageModel->fill($attributes)->save();
        }

        return $newLanguages;
    }
}

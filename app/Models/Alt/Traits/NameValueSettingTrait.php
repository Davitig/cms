<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

trait NameValueSettingTrait
{
    /**
     * Indicates whether unchecked boolean values should be ignored from saving.
     *
     * @var bool
     */
    protected bool $ignoreUnchecked = false;

    /**
     * Determine if the model has languages' relation.
     *
     * @return bool
     */
    abstract public function hasLanguages(): bool;

    /**
     * Get the list of default named values.
     *
     * @return array
     */
    abstract public function defaultNamedValues(): array;

    /**
     * Set whether unchecked boolean values should be ignored from saving.
     *
     * @param  bool  $value
     * @return $this
     */
    public function ignoreUnchecked(bool $value = true): static
    {
        $this->ignoreUnchecked = $value;

        return $this;
    }

    /**
     * Filter a given attribute by its type.
     *
     * @param  mixed  $attribute
     * @return mixed
     */
    protected function filterAttribute(mixed $attribute): mixed
    {
        if (is_numeric($attribute)) {
            if ($attribute = filter_var($attribute, FILTER_VALIDATE_INT)) {
                return $attribute;
            } elseif ($attribute = filter_var($attribute, FILTER_VALIDATE_FLOAT)) {
                return $attribute;
            }
        }

        return $attribute;
    }

    /**
     * Get the result of the settings record.
     *
     * @param  mixed  $currentLang
     * @return \Illuminate\Support\Collection
     */
    public function getSettings(mixed $currentLang = true): Collection
    {
        $data = $this->when($this->hasLanguages() && $currentLang,
            function ($q) use ($currentLang) {
                return $q->where('language_id', is_numeric($currentLang)
                    ? $currentLang
                    : language()->getBy($currentLang, 'id'));
            }
        )->when($this->hasLanguages(), function ($q) {
            return $q->get()->groupBy('language_id')->map->pluck('value', 'name');
        }, function ($q) {
            return $q->pluck('value', 'name');
        })->toArray();

        foreach ($data as $key => $value) {
            if ($this->hasLanguages()) {
                foreach ($value as $langKey => $langValue) {
                    $data[$key][$langKey] = $this->filterAttribute($langValue);
                }

                $data[$key] = new Collection(array_merge($this->defaultNamedValues(), $data[$key]));
            } else {
                $data[$key] = $this->filterAttribute($value);
            }
        }

        if (! $this->hasLanguages()) {
            $data = array_merge($this->defaultNamedValues(), $data);
        } elseif (empty($data)) {
            foreach (language()->all() as $language) {
                $data[$language['id']] = $this->defaultNamedValues();
            }
        }

        return new Collection($data);
    }

    /**
     * Add a where "name" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereName(Builder $query, string $name, mixed $currentLang = true): Builder
    {
        return $query->when($this->hasLanguages() && $currentLang, function ($q) use ($currentLang) {
            return $q->where('language_id', is_numeric($currentLang)
                ? $currentLang
                : language()->getBy($currentLang, 'id'));
        })->where('name', $name);
    }

    /**
     * Find a record by its name or return default.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $name
     * @param  mixed  $currentLang
     * @return static
     */
    public function scopeFindByName(Builder $query, string $name, mixed $currentLang = true): static
    {
        return $query->whereName($name, $currentLang)->firstOrNew([], [
            'name' => $name, 'value' => $this->defaultNamedValues()[$name] ?? null
        ])->forceFill(array_filter(
            [$this->getKeyName() => null] + array_fill_keys($this->getDates(), null)
        ));
    }

    /**
     * Save the settings model to the database.
     *
     * @param  array  $input
     * @param  mixed  $currentLang
     * @return int
     */
    public function saveSettings(array $input, mixed $currentLang = true): int
    {
        $count = 0;

        if (! $this->ignoreUnchecked) {
            $uncheckedValues = array_filter(
                $this->defaultNamedValues(),
                fn ($value, $key) => ! array_key_exists($key, $input) && is_int($value),
                ARRAY_FILTER_USE_BOTH
            );

            array_walk($uncheckedValues, fn (&$value, $key) => $value = 0);

            $input = array_merge($input, $uncheckedValues);
        }

        $input = array_intersect_key($input, $this->defaultNamedValues());

        $activeLangId = is_numeric($currentLang) ? $currentLang : language()->getActive('id');
        $languageIds = language()->all()->pluck('id')->toArray();

        foreach ($input as $key => $value) {
            $model = $this->when($this->hasLanguages(), function ($q) use ($activeLangId) {
                return $q->where('language_id', $activeLangId);
            })->where('name', $key)->first();

            if (! is_null($model)) {
                $model->update(['value' => $value]);
            } else {
                if ($this->hasLanguages()) {
                    foreach ($languageIds as $languageId) {
                        if ($this->where('language_id', $languageId)
                            ->whereName($key)
                            ->exists()) {
                            continue;
                        }

                        $this->create([
                            'language_id' => $languageId,
                            'name' => $key,
                            'value' => $value
                        ]);
                    }
                } else {
                    $this->create([
                        'name' => $key,
                        'value' => $value
                    ]);
                }
            }

            $count++;
        }

        return $count;
    }
}

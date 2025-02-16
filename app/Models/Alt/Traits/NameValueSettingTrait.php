<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Eloquent\Builder;

trait NameValueSettingTrait
{
    /**
     * Get a list of the settings.
     *
     * @return array
     */
    public function getSettings(): array
    {
        $data = $this->get()->pluck('value', 'name')->toArray();

        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                if ($validatedValue = filter_var($value, FILTER_VALIDATE_INT)) {
                    $data[$key] = $validatedValue;
                } elseif ($validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    $data[$key] = $validatedValue;
                }
            }
        }

        return array_merge(static::$defaultNamedValues, $data);
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  array  $attributes
     * @param  array  $values
     * @return static
     */
    public function scopeFirstOrDefault(Builder $query, array $attributes = [], array $values = []): static
    {
        return $query->firstOrNew($attributes, $values ?: [
            'name' => key(self::$defaultNamedValues), 'value' => current(self::$defaultNamedValues)
        ]);
    }

    /**
     * Save the list of settings.
     *
     * @param  array  $input
     * @return int
     */
    public function saveSettings(array $input): int
    {
        $count = 0;

        $uncheckedBoolValues = array_filter(
            static::$defaultNamedValues,
            fn ($value, $key) => ! array_key_exists($key, $input) && $value === 1,
            ARRAY_FILTER_USE_BOTH
        );

        array_walk($uncheckedBoolValues, fn (&$value, $key) => $value = 0);

        $input = array_merge($input, $uncheckedBoolValues);

        foreach ($input as $key => $value) {
            if (! array_key_exists($key, static::$defaultNamedValues)) {
                continue;
            }

            $model = $this->where('name', $key)->first();

            if (! is_null($model)) {
                $model->update(['value' => $value]);
            } else {
                $this->create([
                    'name' => $key,
                    'value' => $value
                ]);
            }

            $count++;
        }

        return $count;
    }
}

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
     * Get a list of the settings.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSettings(): Collection
    {
        $data = $this->pluck('value', 'name')->toArray();

        foreach ($data as $key => $value) {
            if (is_numeric($value)) {
                if ($validatedValue = filter_var($value, FILTER_VALIDATE_INT)) {
                    $data[$key] = $validatedValue;
                } elseif ($validatedValue = filter_var($value, FILTER_VALIDATE_FLOAT)) {
                    $data[$key] = $validatedValue;
                }
            }
        }

        return new Collection(array_merge($this->defaultNamedValues(), $data));
    }

    /**
     * Get the first record matching the attributes or instantiate it.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $attributes
     * @param  array  $values
     * @return static
     */
    public function scopeFirstOrDefault(
        Builder $query, array $attributes = [], array $values = []
    ): static
    {
        return $query->firstOrNew($attributes, $values ?: [
            'name' => key($this->defaultNamedValues()),
            'value' => current($this->defaultNamedValues())
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

        if (! $this->ignoreUnchecked) {
            $uncheckedValues = array_filter(
                $this->defaultNamedValues(),
                fn ($value, $key) => ! array_key_exists($key, $input) && is_int($value),
                ARRAY_FILTER_USE_BOTH
            );

            array_walk($uncheckedValues, fn (&$value, $key) => $value = 0);

            $input = array_merge($input, $uncheckedValues);
        }

        foreach ($input as $key => $value) {
            if (! array_key_exists($key, $this->defaultNamedValues())) {
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

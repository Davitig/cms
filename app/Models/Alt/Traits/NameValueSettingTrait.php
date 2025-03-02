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
     * Get the result of the settings record.
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
     * Find a record by its name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @return static
     */
    public function scopeFindByName(Builder $query, string $column): static
    {
        return $query->whereName($column)->firstOrNew([], [
            'name' => $column, 'value' => $this->defaultNamedValues()[$column]
        ])->forceFill(array_filter(
            [$this->getKeyName() => null] + array_fill_keys($this->getDates(), null)
        ));
    }

    /**
     * Save the settings model to the database.
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

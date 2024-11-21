<?php

namespace App\Support;

use App\Models\Translation;
use Illuminate\Support\Collection;

class TranslationCollection
{
    /**
     * Create a new TranslationCollection instance.
     */
    public function __construct(protected ?Collection $collection = null) {}

    /**
     * Get the value from the collection.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        return ! is_null($this->collection)
            ? $this->collection->get($key, $default)
            : (new Translation)->byCode($key)->value('value') ?? $default;
    }

    /**
     * Set a new collection instance.
     *
     * @param  \Illuminate\Support\Collection $collection
     * @return $this
     */
    public function setCollection(Collection $collection): static
    {
        $this->collection = $collection;

        return $this;
    }
}

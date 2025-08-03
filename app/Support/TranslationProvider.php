<?php

namespace App\Support;

use App\Models\Translation\Translation;

class TranslationProvider
{
    /**
     * Create a new translation provider instance.
     */
    public function __construct(
        protected array $items = [], protected array $blacklistKeys = []
    ) {}

    /**
     * Get the value from the collection.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        if (! is_null($value = $this->items[$key] ?? null)) {
            return $value;
        }

        if (! in_array($key, $this->blacklistKeys)) {
            $value = (new Translation)->byCode($key)->value('value');

            if (! is_null($value)) {
                return $this->items[$key] = $value;
            } else {
                $this->blacklistKeys[] = $key;
            }
        }

        return $default;
    }

    /**
     * Get all the items.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the blacklist keys.
     *
     * @return array
     */
    public function getBlacklistKeys(): array
    {
        return $this->blacklistKeys;
    }
}

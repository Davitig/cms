<?php

namespace App\Support;

use App\Models\Translation;
use Illuminate\Support\Collection;

class TranslationProvider
{
    /**
     * Indicates the count number of items in the collection.
     *
     * @var int
     */
    protected int $count = 0;

    /**
     * The list of blacklisted keys.
     *
     * @var array
     */
    protected array $blacklistKeys = [];

    /**
     * Create a new TranslationCollection instance.
     */
    public function __construct(
        protected ?Collection $items = null, protected int $baseCount = 0
    ) {
        $this->items ??= new Collection;

        $this->count = $this->items?->count() ?: 0;
    }

    /**
     * Get the value from the collection.
     *
     * @param  string  $key
     * @param  string|null  $default
     * @return string|null
     */
    public function get(string $key, ?string $default = null): ?string
    {
        if (! is_null($value = $this->items->get($key))) {
            return $value;
        }

        if ($this->baseCount > $this->count && ! in_array($key, $this->blacklistKeys)) {
            $value = (new Translation)->byCode($key)->value('value');

            if (! is_null($value)) {
                $this->items->put($key, $value);

                $this->count++;

                return $value;
            } else {
                $this->blacklistKeys[] = $key;
            }
        }

        return $default;
    }
}

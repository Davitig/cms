<?php

namespace App\Support;

use App\Models\Language;
use Exception;
use Illuminate\Support\Collection;

class LanguageProvider
{
    /**
     * The list of languages.
     *
     * @var \Illuminate\Support\Collection
     */
    protected Collection $languages;

    /**
     * The main language.
     *
     * @var string|null
     */
    protected ?string $main;

    /**
     * The active language.
     *
     * @var string|null
     */
    protected ?string $active;

    /**
     * Indicates if the language is selected in a request path.
     *
     * @var bool
     */
    protected bool $isSelected;

    /**
     * URI's query string language.
     *
     * @var string|null
     */
    protected ?string $queryString;

    /**
     * Create a new language provider instance.
     *
     * @param  \Illuminate\Support\Collection  $languages
     * @param  string  $path
     * @param  string|null  $queryString
     */
    public function __construct(Collection $languages, string $path, ?string $queryString = null)
    {
        $this->configure($languages, $path);

        $this->queryString = $queryString;
    }

    /**
     * Create a new language service instance.
     *
     * @param  string  $path
     * @param  string|null  $queryString
     * @return static
     */
    public static function make(string $path, ?string $queryString = null): static
    {
        try {
            return new static((new Language)->positionAsc()->get(), $path, $queryString);
        } catch (Exception) {
            return new static(new Collection, $path, $queryString);
        }
    }

    /**
     * Configure the language service.
     *
     * @param  \Illuminate\Support\Collection  $languages
     * @param  string  $path
     * @return void
     */
    protected function configure(Collection $languages, string $path): void
    {
        $languages = $languages->mapWithKeys(function ($language) {
            return [$language['language'] => $language];
        });

        $this->main = (current($languages->filter(
            fn ($item) => $item['main']
        )->keys()->toArray()) ?: null);

        $segments = explode('/', $path);

        $activeLanguage = reset($segments);

        $this->isSelected = $activeLanguage && $languages->offsetExists($activeLanguage);

        if ($this->isSelected) {
            $this->active = $activeLanguage;

            array_shift($segments);
        } else {
            $this->active = $this->main ?: $languages->first()['language'] ?? null;
        }

        $this->main ??= $this->active;

        $path = trim(implode('/', $segments), '/');

        $languages->map(function ($item, $language) use ($path) {
            $item['path'] = $language . ($path ? '/' . $path : '');
        });

        $this->languages = $languages;
    }

    /**
     * Get the main language.
     *
     * @return string|null
     */
    public function main(): ?string
    {
        return $this->main;
    }

    /**
     * Get the main language item.
     *
     * @param  string|null  $attribute
     * @return string|null
     */
    public function getMain(?string $attribute = null): ?string
    {
        if (is_null($this->main())) {
            return null;
        }

        return $this->get($this->main(), $attribute);
    }

    /**
     * Determine if the main language is active.
     *
     * @return bool
     */
    public function mainIsActive(): bool
    {
        return $this->main() == $this->active();
    }

    /**
     * Determine if the main language is visible.
     *
     * @return bool
     */
    public function mainIsVisible(): bool
    {
        return $this->main() && $this->visibleExists($this->main());
    }

    /**
     * Get the active language.
     *
     * @return string|null
     */
    public function active(): ?string
    {
        return $this->active;
    }

    /**
     * Get the active language item.
     *
     * @param  string|null  $attribute
     * @return string|null
     */
    public function getActive(?string $attribute = null): mixed
    {
        if (is_null($this->active())) {
            return null;
        }

        return $this->get($this->active(), $attribute);
    }

    /**
     * Determine if the given language is active.
     *
     * @param  string  $language
     * @return bool
     */
    public function isActive(string $language): bool
    {
        return $this->active() == $language;
    }

    /**
     * Determine if the active language is visible.
     *
     * @return bool
     */
    public function activeIsVisible(): bool
    {
        return $this->active() && $this->visibleExists($this->active());
    }

    /**
     * Get the available language.
     *
     * @return string|null
     */
    public function available(): ?string
    {
        if ($this->activeIsVisible()) {
            return $this->active();
        }

        if ($this->mainIsVisible()) {
            return $this->main();
        }

        return $this->firstVisible();
    }

    /**
     * Get the available language item.
     *
     * @param  string|null  $attribute
     * @return string|null
     */
    public function getAvailable(?string $attribute = null): mixed
    {
        return $this->getActive($attribute)
            ?: $this->getMain($attribute)
                ?: $this->getFirstVisible($attribute);
    }

    /**
     * Get the query string language key.
     *
     * @return string|null
     */
    public function queryStringKey(): ?string
    {
        return config('language.query_string_key');
    }

    /**
     * Get the query string language.
     *
     * @return string|null
     */
    public function queryString(): ?string
    {
        return $this->queryString;
    }

    /**
     * Get the query string language or active.
     *
     * @return string|null
     */
    public function queryStringOrActive(): ?string
    {
        return $this->queryString ?: $this->active();
    }

    /**
     * Get the query string language item.
     *
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getByQueryString(?string $attribute = null): mixed
    {
        if (is_null($this->queryString())) {
            return null;
        }

        return $this->get($this->queryString(), $attribute);
    }

    /**
     * Get the language item by query string or active.
     *
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getByQueryStringOrActive(?string $attribute = null): mixed
    {
        return $this->getByQueryString($attribute) ?: $this->getActive($attribute);
    }

    /**
     * Determine if the language is selected in a request path.
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->isSelected;
    }

    /**
     * Get the first language.
     *
     * @return string|null
     */
    public function first(): ?string
    {
        return $this->getFirst()['language'] ?? null;
    }

    /**
     * Get the first language item.
     *
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getFirst(?string $attribute = null): mixed
    {
        if (! is_null($attribute)) {
            return $this->all()->first()[$attribute] ?? null;
        }

        return $this->all()->first();
    }

    /**
     * Get the first visible language.
     *
     * @return string|null
     */
    public function firstVisible(): ?string
    {
        return $this->getFirstVisible()['language'] ?? null;
    }

    /**
     * Get the first visible language item.
     *
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getFirstVisible(?string $attribute = null): mixed
    {
        if (! is_null($attribute)) {
            return $this->allVisible()->first()[$attribute] ?? null;
        }

        return $this->allVisible()->first();
    }

    /**
     * Determine if the given language exists.
     *
     * @param  string  $language
     * @return bool
     */
    public function exists(string $language): bool
    {
        return $this->all()->offsetExists($language);
    }

    /**
     * Determine if the given language exists and visible.
     *
     * @param  string  $language
     * @return bool
     */
    public function visibleExists(string $language): bool
    {
        return $this->allVisible()->offsetExists($language);
    }

    /**
     * Get a language item from the collection by key.
     *
     * @param  string  $language
     * @param  string|null  $attribute
     * @return mixed
     */
    public function get(string $language, ?string $attribute = null): mixed
    {
        if (! is_null($attribute)) {
            return $this->all()->get($language)[$attribute] ?? null;
        }

        return $this->all()->get($language);
    }

    /**
     * Get a visible language item from the collection by key.
     *
     * @param  string  $language
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getVisible(string $language, ?string $attribute = null): mixed
    {
        if (! is_null($attribute)) {
            return $this->allVisible()->get($language)[$attribute] ?? null;
        }

        return $this->allVisible()->get($language);
    }

    /**
     * Get a language item from the collection by key type.
     *
     * @param  mixed  $language
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getBy(mixed $language, ?string $attribute = null): mixed
    {
        return match (true) {
            $language === true => $this->getActive($attribute),
            $language === false => $this->getMain($attribute),
            is_int($language) => $this->getByKey($language, $attribute),
            $language === $this->queryStringKey()
            => ($lang = $this->queryStringOrActive()) ? $this->get(
                $lang, $attribute
            ) : null,
            default => $this->get($language, $attribute),
        };
    }

    /**
     * Get a visible language item from the collection by key type.
     *
     * @param  mixed  $language
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getVisibleBy(mixed $language, ?string $attribute = null): mixed
    {
        return match (true) {
            $language === true => $this->getActive($attribute),
            $language === false => $this->getMain($attribute),
            is_int($language) => $this->getVisibleByKey($language, $attribute),
            $language === $this->queryStringKey() => $this->getVisible(
                $this->queryStringOrActive(), $attribute
            ),
            default => $this->getVisible($language, $attribute),
        };
    }

    /**
     * Get a language item from the collection by primary key.
     *
     * @param  int  $key
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getByKey(int $key, ?string $attribute = null): mixed
    {
        $result = $this->all()->where('id', $key)->first();

        if (! is_null($attribute) && ! is_null($result)) {
            return $result[$attribute] ?? null;
        }

        return $result;
    }

    /**
     * Get a visible language item from the collection by primary key.
     *
     * @param  int  $key
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getVisibleByKey(int $key, ?string $attribute = null): mixed
    {
        $result = $this->allVisible()->where('id', $key)->first();

        if (! is_null($attribute) && ! is_null($result)) {
            return $result[$attribute] ?? null;
        }

        return $result;
    }

    /**
     * Get the list of all languages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection
    {
        return $this->languages;
    }

    /**
     * Get the list of all visible languages.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allVisible(): Collection
    {
        return $this->all()->filter(fn ($language) => $language['visible']);
    }

    /**
     * Count the number of languages.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->all()->count();
    }

    /**
     * Count the number of visible languages.
     *
     * @return int
     */
    public function countVisible(): int
    {
        return $this->allVisible()->count();
    }

    /**
     * Determine if the languages are empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->all()->isEmpty();
    }

    /**
     * Determine if the visible languages are empty or not.
     *
     * @return bool
     */
    public function visibleIsEmpty(): bool
    {
        return $this->allVisible()->isEmpty();
    }
}

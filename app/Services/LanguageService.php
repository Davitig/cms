<?php

namespace App\Services;

use App\Models\Language;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class LanguageService
{
    /**
     * The list of languages.
     *
     * @var \Illuminate\Database\Eloquent\Collection
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
     * Create a new language service instance.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $languages
     * @param  string  $path
     */
    public function __construct(Collection $languages, string $path)
    {
        $this->configure($languages, $path);
    }

    /**
     * Create a new language service instance.
     *
     * @param  string  $path
     * @return static
     */
    public static function make(string $path): static
    {
        try {
            return new static((new Language)->positionAsc()->get(), $path);
        } catch (Exception $e) {
            return new static(new Collection, $path);
        }
    }

    /**
     * Configure the language service.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $languages
     * @param  string  $path
     * @return void
     */
    protected function configure(Collection $languages, string $path): void
    {
        $languages = $languages->mapWithKeys(function ($language) {
            return [$language['language'] => $language];
        });

        $this->main = current(($languages->filter(
            fn ($item) => $item['main']
        )->keys()->toArray()) ?: $languages->keys()->toArray()) ?: null;

        $activeLanguage = current($segments = explode('/', $path));

        $this->isSelected = $activeLanguage && $languages->offsetExists($activeLanguage);

        if ($this->isSelected) {
            $this->active = $activeLanguage;

            array_shift($segments);
        } else {
            $this->active = $this->main;
        }

        foreach ($languages as $language => $model) {
            $languages[$language]['path'] = trim(
                $language . '/' . implode('/', $segments), '/'
            );
        }

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
     * Determine if the language is selected in a request path.
     *
     * @return bool
     */
    public function isSelected(): bool
    {
        return $this->isSelected;
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
     * Get a language item from the collection by key type.
     *
     * @param  bool|string  $language
     * @param  string|null  $attribute
     * @return mixed
     */
    public function getBy(bool|string $language, ?string $attribute = null): mixed
    {
        if ($language === true) {
            return $this->getActive($attribute);
        } elseif ($language === false) {
            return $this->getMain($attribute);
        }

        return $this->all()->get($language, $attribute);
    }

    /**
     * Get the list of all languages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all(): Collection
    {
        return $this->languages;
    }

    /**
     * Get the list of all visible languages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allVisible(): Collection
    {
        return $this->all()->filter(fn ($language) => $language['visible']);
    }

    /**
     * Determine if the languages contain a more than one item.
     *
     * @return bool
     */
    public function containsMany(): bool
    {
        return $this->all()->count() > 1;
    }

    /**
     * Determine if the languages contain a more than one visible item.
     *
     * @return bool
     */
    public function containsManyVisible(): bool
    {
        return $this->allVisible()->count() > 1;
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

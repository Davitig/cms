<?php

use App\Support\LanguageProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Get the language service.
 *
 * @return \App\Support\LanguageProvider
 */
function language(): LanguageProvider
{
    return app(LanguageProvider::class);
}

/**
 * Apply the languages to the given array.
 *
 * @param  array|null  $data
 * @return mixed
 */
function apply_languages(?array $data = null): array
{
    if (is_null($data)) {
        return ['language_id' => language()->getActive('id')];
    }

    $languages = [];

    foreach (language()->all() as $language) {
        $data['language_id'] = $language['id'];

        $languages[] = $data;
    }

    return $languages;
}

/**
 * Determine if the CMS routes has booted.
 *
 * @return bool
 */
function cms_booted(): bool
{
    return config('_cms.booted', false);
}

/**
 * Get the CMS path.
 *
 * @param  string|null  $path
 * @param  bool|string  $language
 * @return string
 */
function cms_path(?string $path = null, bool $language = false): string
{
    $slug = trim(cms_config('slug'), '/');

    if ($language) {
        $language = is_string($language)
            ? $language
            : (language()->isSelected() ? language()->active() : '');

        $slug = trim($language . '/' . $slug, '/');
    }

    return is_null($path) ? $slug : $slug . '/' . trim($path, '/');
}

/**
 * Get the CMS route name.
 *
 * @param  string|null  $name
 * @param  string  $separator
 * @return string
 */
function cms_route_name(?string $name = null, string $separator = '.'): string
{
    return cms_path() . $separator . $name;
}

/**
 * Get the list of named resource.
 *
 * @param  string  $name
 * @return array
 */
function resource_names(string $name): array
{
    return [
        'index'   => $name . '.index',
        'create'  => $name . '.create',
        'store'   => $name . '.store',
        'show'    => $name . '.show',
        'edit'    => $name . '.edit',
        'update'  => $name . '.update',
        'destroy' => $name . '.destroy'
    ];
}

/**
 * Generate a CMS URL to a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  mixed|null  $language
 * @param  bool  $absolute
 * @return string
 */
function cms_route(
    string $name,
    mixed  $parameters = [],
    mixed  $language = null,
    bool   $absolute = true
): string
{
    return route(...language_route_parameters(
        cms_route_name($name), $parameters, $language, $absolute,
        fn () => ! language()->isEmpty()
    ));
}

/**
 * Generate a CMS URL for the application.
 *
 * @param  mixed|null  $path
 * @param  array  $parameters
 * @param  mixed|null  $language
 * @param  bool|null  $secure
 * @return string
 */
function cms_url(
    mixed $path = null,
    array $parameters = [],
    mixed $language = null,
    ?bool $secure = null
): string
{
    if (is_array($path)) {
        $path = implode('/', array_filter($path));
    }

    if (! language()->isEmpty()) {
        $language = language()->active();
    }

    return web_url(cms_path($path), $parameters, $language, $secure);
}

/**
 * Generate a URL to a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  mixed|null  $language
 * @param  bool  $absolute
 * @return string
 */
function web_route(
    string $name,
    mixed  $parameters = [],
    mixed  $language = null,
    bool   $absolute = true
): string
{
    return route(...language_route_parameters(
        $name, $parameters, $language, $absolute, fn () => language()->countVisible() > 1
    ));
}

/**
 * Generate a URL for the application.
 *
 * @param  mixed|null  $path
 * @param  array  $parameters
 * @param  mixed|null  $language
 * @param  bool|null  $secure
 * @return string
 */
function web_url(
    mixed $path = null,
    array $parameters = [],
    mixed $language = null,
    ?bool $secure = null
): string
{
    if (is_array($path)) {
        $path = implode('/', array_filter($path));
    }

    $url = url(language_path_prefix($path, $language), [], $secure);

    $query = Arr::query($parameters);

    return trim($url, '?') . ($query ? '?' . $query : '');
}

/**
 * Add language to the route parameters.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  mixed|null  $language
 * @param  bool  $absolute
 * @param  \Closure|null  $onDefaultLanguage
 * @return array
 */
function language_route_parameters(
    string   $name,
    mixed    $parameters = [],
    mixed    $language = null,
    bool     $absolute = true,
    ?Closure $onDefaultLanguage = null
): array
{
    if ($language === false) {
        return [$name, $parameters, $absolute];
    }

    $langRouteName = config('language.route_name');

    if (! is_string($language) &&
        ! $language = ($onDefaultLanguage() ? language()->active() : null)) {
        return [$name, $parameters, $absolute];
    }

    $parameters = Arr::wrap($parameters);

    $parameters[$langRouteName] = $language;

    $langRouteName .= '.';

    return [$langRouteName . $name, $parameters, $absolute];
}

/**
 * Prefix the given path with the language.
 *
 * @param  string|null  $path
 * @param  mixed|null  $language
 * @return string
 */
function language_path_prefix(?string $path = null, mixed $language = null): string
{
    if ($language === false) {
        return $path;
    }

    $path = trim($path, '/');

    if (is_string($language)) {
        $path = $language . '/' . $path;
    } elseif (($language === true || language()->isSelected()) && language()->count() > 1) {
        $path = language()->active() . '/' . $path;
    }

    return $path;
}

/**
 * Get the CMS config.
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return mixed
 */
function cms_config(?string $key = null, mixed $default = []): mixed
{
    if (! is_null($key)) {
        return config('cms.' . $key, $default);
    }

    return config('cms', $default);
}

/**
 * Get the CMS pages config.
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return mixed
 */
function cms_pages(?string $key = null, mixed $default = []): mixed
{
    if (! is_null($key)) {
        return cms_config('pages.' . $key, $default);
    }

    return cms_config('pages', $default);
}

/**
 * Make a subitems' collection.
 *
 * @param  array|\Illuminate\Support\Collection  $items
 * @param  bool  $baseAll
 * @param  int  $parentId
 * @param  string|null  $slug
 * @return array|\Illuminate\Support\Collection
 */
function make_sub_items(
    array|Collection $items,
    bool             $baseAll = false,
    int              $parentId = 0,
    ?string          $slug = null
): array|Collection
{
    $data = $baseAll ? $items : [];

    $prevSlug = $slug;

    foreach ($items as $item) {
        if ($item->parent_id != $parentId) {
            continue;
        }

        $item->url_path = $prevSlug ? $prevSlug . '/' . $item->slug : $item->slug;

        $item->sub_items = make_sub_items($items, false, $item->id, $item->url_path);

        if (! $baseAll) {
            $data[] = $item;
        }
    }

    if (! is_array($items)) {
        $collection = get_class($items);

        return $baseAll ? $data : new $collection($data);
    }

    return $data;
}

/**
 * Determine if the item has a subitems' collection.
 *
 * @param  object  $item
 * @return bool
 */
function has_sub_items(object $item): bool
{
    return isset($item->sub_items) &&
        $item->sub_items instanceof Collection &&
        $item->sub_items->isNotEmpty();
}

/**
 * Count the items/subitems collection.
 *
 * @param  array|\Illuminate\Support\Collection  $items
 * @return int
 */
function count_sub_items(array|Collection $items): int
{
    $count = 0;

    foreach ($items as $item) {
        $count++;

        if (isset($item->sub_items) && $item->sub_items instanceof Collection) {
            $count += count_sub_items($item->sub_items);
        }
    }

    return $count;
}

/**
 * Fill an array with data.
 *
 * @param  mixed  $result
 * @param  string|null  $message
 * @param  mixed|null  $data
 * @return array
 */
function fill_data(mixed $result, ?string $message = null, mixed $data = null): array
{
    return [
        'result' => $result,
        'message' => $message,
        'data' => $data
    ];
}

/**
 * Get the path for the glide server.
 *
 * @param  string  $path
 * @param  string  $type
 * @return string
 */
function glide(string $path, string $type): string
{
    $files = (array) config('elfinder.dir');
    $files = reset($files) . '/';

    if (($pos = strpos($path, $files)) !== false) {
        $baseUrl = config('web.glide_base_url') . '/';

        $query = '?type=' . $type;

        return substr_replace($path, $baseUrl, $pos, strlen($files)) . $query;
    }

    return $path;
}

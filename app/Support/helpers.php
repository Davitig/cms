<?php

use Illuminate\Support\Collection;

/**
 * Get the application language.
 *
 * @param  bool|string|null  $key
 * @param  string|null  $value
 * @return mixed
 */
function language(mixed $key = null, ?string $value = null): mixed
{
    if (! $lang = (string) config('_app.language')) {
        return null;
    }

    if (is_null($key)) {
        return $lang;
    }

    if (is_bool($key)) {
        $key = $lang;
    }

    if (! is_null($value)) {
        return languages()[$key][$value];
    }

    return languages()[$key];
}

/**
 * Get the application languages.
 *
 * @param  bool  $visible
 * @return array
 */
function languages(bool $visible = false): array
{
    if (! $visible) {
        return (array) config('_app.languages', []);
    }

    return array_filter((array) config('_app.languages', []), function (array $language) {
        return $language['visible'];
    });
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
        return ['language_id' => language(true, 'id')];
    }

    $languages = [];

    foreach (languages() as $language) {
        $data['language_id'] = $language['id'];

        $languages[] = $data;
    }

    return $languages;
}

/**
 * Determine if the language is set in the URL.
 *
 * @return bool
 */
function language_selected(): bool
{
    return config('_app.language_selected', false);
}

/**
 * Determine if the application is multilanguage.
 *
 * @param  bool  $visible
 * @return bool
 */
function is_multilanguage(bool $visible = false): bool
{
    return count(languages($visible)) > 1;
}

/**
 * Determine if the CMS routes should be loaded.
 *
 * @return bool
 */
function cms_activated(): bool
{
    return config('_cms.activated', false);
}

/**
 * Get the CMS slug.
 *
 * @param  string|null  $path
 * @param  bool|string  $language
 * @return string
 */
function cms_slug(?string $path = null, bool $language = false): string
{
    $slug = cms_config('slug');

    if ($language) {
        $language = is_string($language)
            ? $language
            : (language_selected() ? language() : '');

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
    return cms_slug() . $separator . $name;
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
    return language_to_url(route(cms_route_name($name), $parameters, $absolute), $language);
}

/**
 * Generate a CMS URL.
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
    } elseif (! is_string($path)) {
        $path = '';
    }

    return web_url(cms_slug($path), $parameters, $language, $secure);
}

/**
 * Generate a web URL to a named route.
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
    return language_to_url(route($name, $parameters, $absolute), $language);
}

/**
 * Generate a web URL.
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
    } elseif (! is_string($path)) {
        $path = '';
    }

    $url = url(language_to_url($path, $language), [], $secure);

    return trim($url, '?') . query_string(
            $parameters, parse_url($url, PHP_URL_QUERY) ? '&' : '?'
        );
}

/**
 * Build a query string from an array of key value pairs.
 *
 * @param  array  $parameters
 * @param  string  $basePrefix
 * @return string
 */
function query_string(array $parameters, string $basePrefix = '?'): string
{
    if (count($parameters) == 0) {
        return '';
    }

    $query = http_build_query(
        $keyed = array_filter($parameters, 'is_string', ARRAY_FILTER_USE_KEY)
    );

    if (count($keyed) < count($parameters)) {
        $query .= '&'.implode(
                '&', array_filter($parameters, 'is_numeric', ARRAY_FILTER_USE_KEY)
            );
    }

    return $basePrefix.trim($query, '&');
}

/**
 * Prefix a language to the path.
 *
 * @param  string|null  $path
 * @param  mixed|null  $language
 * @return string
 */
function language_prefix(?string $path = null, mixed $language = null): string
{
    $path = trim($path, '/');

    if (is_string($language)) {
        $path = $language . '/' . $path;
    } elseif ($language !== false
        && ($language === true || language_selected())
        && count(languages()) > 1
    ) {
        $path = language() . '/' . $path;
    }

    return $path;
}

/**
 * Add language to the url.
 *
 * @param  string  $url
 * @param  mixed|null  $language
 * @return string
 */
function language_to_url(string $url, mixed $language = null): string
{
    if (! ($withLanguage = ! empty($language)) && ! language_selected()) {
        return trim($url, '/');
    }

    $segments = parse_url($url);

    $path = $segments['path'] ?? '';

    $query = isset($segments['query']) ? '?' . $segments['query'] : '';

    if (! isset($segments['host'])) {
        return language_prefix($path . $query, $language);
    }

    $baseUrl = $schemeAndHttpHost = '';

    if (isset($segments['scheme'])) {
        $schemeAndHttpHost = $segments['scheme'] . '://' . $segments['host'];
    }

    if (! empty($path) || $withLanguage) {
        if (str($path)->startsWith($baseUrl = request()->getBaseUrl())
            && $schemeAndHttpHost == request()->getSchemeAndHttpHost()
        ) {
            $path = substr($path, strlen($baseUrl));
        } else {
            $baseUrl = '';
        }

        $path = array_filter(explode('/', $path));

        if (array_key_exists((string) current($path), languages())) {
            array_shift($path);
        }

        $path = language_prefix(implode('/', $path) . $query, $language);
    }

    return trim($schemeAndHttpHost . $baseUrl . '/' . $path, '/');
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

        $item->full_slug = $prevSlug ? $prevSlug . '/' . $item->slug : $item->slug;

        $item->sub_items = make_sub_items($items, false, $item->id, $item->full_slug);

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
    return isset($item->sub_items)
        && $item->sub_items instanceof Collection
        && $item->sub_items->isNotEmpty();
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
 * @param  string  $result
 * @param  string|null  $message
 * @param  mixed|null  $input
 * @return array
 */
function fill_data(string $result, ?string $message = null, mixed $input = null): array
{
    return [
        'result' => $result,
        'message' => $message,
        'data' => $input
    ];
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
 * Get the CMS collections config.
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return mixed
 */
function cms_collections(?string $key = null, mixed $default = []): mixed
{
    if (! is_null($key)) {
        return cms_config('collections.' . $key, $default);
    }

    return cms_config('collections', $default);
}

/**
 * Get the CMS deep collections config.
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return mixed
 */
function deep_collection(?string $key = null, mixed $default = []): mixed
{
    if (! is_null($key)) {
        return cms_config('deep_collections.' . $key, $default);
    }

    return cms_config('deep_collections', $default);
}

/**
 * Get the CMS icon name.
 *
 * @param  string  $key
 * @param  mixed|null  $default
 * @return mixed
 */
function icon_type(string $key, mixed $default = null): mixed
{
    return cms_config('icons.' . $key, $default);
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
    $files = current($files) . '/';

    if (($pos = strpos($path, $files)) !== false) {
        $baseUrl = '/' . config('web.glide_base_url') . '/';

        $query = '?type=' . $type;

        return substr_replace($path, $baseUrl, $pos, strlen($files)) . $query;
    }

    return $path;
}

/**
 * Convert bytes to human-readable format.
 *
 * @param  int  $bytes
 * @param  int  $precision
 * @param  string  $separator
 * @return string
 */
function format_bytes(int $bytes, int $precision = 2, string $separator = ' '): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = (float) max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . $separator . $units[$pow];
}

/**
 * Get YouTube video id from url.
 *
 * @param  string  $url
 * @param  array  $allowQueryStrings
 * @param  bool  $strict
 * @return string
 */
function get_youtube_id(string $url, array $allowQueryStrings = [], bool $strict = false): string
{
    $parts = parse_url($url);

    if (isset($parts['query'])) {
        parse_str($parts['query'], $queryString);

        $allowQueryStrings = query_string(array_intersect_key(
            $queryString, array_flip($allowQueryStrings)
        ), '&');

        if (isset($queryString['v'])) {
            return $queryString['v'] . $allowQueryStrings;
        } elseif (isset($queryString['vi'])) {
            return $queryString['vi'] . $allowQueryStrings;
        }
    } else {
        $allowQueryStrings = '';
    }

    if ((! $strict || isset($parts['scheme'])) && isset($parts['path'])) {
        $path = explode('/', trim($parts['path'], '/'));

        return end($path) . $allowQueryStrings;
    }

    return '';
}

/**
 * Convert youtube video url to embed url.
 *
 * @param  string  $url
 * @param  array  $allowQueryStrings
 * @return string
 */
function get_youtube_embed(string $url, array $allowQueryStrings = []): string
{
    return 'https://www.youtube.com/embed/' . get_youtube_id($url, $allowQueryStrings);
}

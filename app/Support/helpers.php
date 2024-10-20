<?php

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Models\Abstracts\Model;

/**
 * Get the application language.
 *
 * @param  string|bool|null  $key
 * @param  string|null  $value
 * @return string|array
 */
function language($key = null, $value = null)
{
    $lang = (string) config('app.language');

    if (is_null($key)) {
        return $lang;
    }

    if ($key === true) {
        $key = $lang;
    }

    if (! is_null($value)) {
        $value = '.' . $value;
    }

    return config('app.languages.' . $key . $value);
}

/**
 * Get the application languages.
 *
 * @return array
 */
function languages()
{
    return (array) config('app.languages', []);
}

/**
 * Determine if the language is set in the URL.
 *
 * @return bool
 */
function language_in_url()
{
    return config('language_in_url', false);
}

/**
 * Determine if the application is multilanguage.
 *
 * @return bool
 */
function is_multilanguage()
{
    return count(languages()) > 1;
}

/**
 * Determine if the CMS routes should be loaded.
 *
 * @return bool
 */
function cms_is_booted()
{
    return config('cms_is_booted', false);
}

/**
 * Get the CMS slug
 *
 * @param  string|null  $path
 * @return string
 */
function cms_slug($path = null)
{
    if (is_null($path)) {
        return cms_config('slug');
    }

    return cms_config('slug') . '/' . $path;
}

/**
 * Get the list of named resource.
 *
 * @param  string  $name
 * @return array
 */
function resource_names($name)
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
 * Get a full CMS route name.
 *
 * @param  string  $name
 * @return string
 */
function cms_route_name($name)
{
    return cms_slug() . '.' . $name;
}

/**
 * Generate a CMS URL to a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  mixed  $language
 * @param  bool  $absolute
 * @return string
 */
function cms_route($name, $parameters = [], $language = null, $absolute = true)
{
    return language_to_url(route(cms_route_name($name), $parameters, $absolute), $language);
}

/**
 * Generate a CMS URL.
 *
 * @param  string|array  $path
 * @param  array  $parameters
 * @param  mixed  $language
 * @param  bool|null  $secure
 * @return \Illuminate\Contracts\Routing\UrlGenerator|string
 */
function cms_url($path = '', array $parameters = [], $language = null, $secure = null)
{
    return web_url(cms_slug($path), $parameters, $language, $secure);
}

/**
 * Generate a web URL to a named route.
 *
 * @param  string  $name
 * @param  mixed  $parameters
 * @param  mixed  $language
 * @param  bool  $absolute
 * @return string
 */
function web_route($name, $parameters = [], $language = null, $absolute = true)
{
    return language_to_url(route($name, $parameters, $absolute), $language);
}

/**
 * Generate a web URL.
 *
 * @param  string|array  $path
 * @param  array  $parameters
 * @param  mixed  $language
 * @param  bool|null  $secure
 * @return string
 */
function web_url($path = '', array $parameters = [], $language = null, $secure = null)
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
function query_string(array $parameters, $basePrefix = '?')
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
 * @param  string  $path
 * @param  string|null  $language
 * @return string
 */
function language_prefix($path, $language = null)
{
    $path = trim($path, '/');

    if (is_string($language)) {
        $path = $language . '/' . $path;
    } elseif ($language !== false
        && ($language === true || language_in_url())
        && count(languages()) > 1
    ) {
        $path = language() . '/' . $path;
    }

    return trim($path, '/');
}

/**
 * Add language to the url.
 *
 * @param  string  $url
 * @param  string|null  $language
 * @return string
 */
function language_to_url($url, $language = null)
{
    if (is_null($url)) {
        return null;
    }

    if (! ($withLanguage = ! empty($language)) && ! language_in_url()) {
        return trim($url, '/');
    }

    $segments = parse_url($url);

    $path = $segments['path'] ?? '';

    $query = isset($segments['query']) ? '?' . $segments['query'] : '';

    if (! isset($segments['host'])) {
        return language_prefix($path. $query, $language);
    }

    $baseUrl = $schemeAndHttpHost = '';

    if (isset($segments['scheme'])) {
        $schemeAndHttpHost = $segments['scheme'] . '://' . $segments['host'];
    }

    if (! empty($path) || $withLanguage) {
        if (Str::startsWith($path, $baseUrl = request()->getBaseUrl())
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
 * Get the Eloquent model path.
 *
 * @param  string  $name
 * @return string
 */
function model_path($name)
{
    return 'Models\\' . ucfirst(Str::singular($name));
}

/**
 * Make a nestable eloquent models tree.
 *
 * @param  \Illuminate\Support\Collection|array  $items
 * @param  string  $slug
 * @param  int  $parentId
 * @param  string  $parentKey
 * @param  string  $key
 * @return \Illuminate\Support\Collection|array
 *
 * @throws \InvalidArgumentException
 */
function make_model_sub_items($items, $slug = '', $parentId = 0, $parentKey = 'parent_id', $key = 'id')
{
    if (! $items instanceof Collection && ! is_array($items)) {
        throw new InvalidArgumentException(
            'Argument 1 must be of the type array or an instance of ' . Collection::class
        );
    }

    $data = [];

    $prevSlug = $slug;

    foreach ($items as $item) {
        if (! $item instanceof Model) {
            return $items;
        }

        if ($item->$parentKey != $parentId) {
            continue;
        }

        $item->full_slug = $prevSlug ? $prevSlug . '/' . $item->slug : $item->slug;

        $item->sub_items = make_model_sub_items($items, $item->full_slug, $item->$key, $parentKey, $key);

        $data[] = $item;
    }

    return new Collection($data);
}

/**
 * Determine if the item has a nestable eloquent model items.
 *
 * @param  mixed  $item
 * @return bool
 */
function has_model_sub_items($item)
{
    return $item instanceof Model
        && $item->sub_items instanceof Collection
        && $item->sub_items->isNotEmpty();
}

/**
 * Get the instance from the container.
 *
 * @param  string  $instance
 * @param  mixed  $default
 * @return mixed
 */
function app_instance($instance, $default = null)
{
    $app = app();

    if ($app->resolved($instance)) {
        return $app[$instance];
    }

    return $default;
}

/**
 * Fill an array with data.
 *
 * @param  string  $result
 * @param  string|null  $message
 * @param  mixed  $input
 * @return array
 */
function fill_data($result, $message = null, $input = null)
{
    return [
        'result'  => $result,
        'message' => $message,
        'input'   => $input
    ];
}

/**
 * Fill a database error message.
 *
 * @param  string  $key
 * @param  array   $parameters
 * @return array
 */
function fill_db_data($key, array $parameters = [])
{
    return fill_data('error', trans('database.error.' . $key, $parameters));
}

/**
 * Get the CMS config.
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return string|array
 */
function cms_config($key = null, $default = [])
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
 * @return string|array
 */
function cms_pages($key = null, $default = [])
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
 * @return string|array
 */
function cms_collections($key = null, $default = [])
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
 * @return array
 */
function deep_collection($key = null, $default = [])
{
    if (! is_null($key)) {
        return cms_config('deep_collections.' . $key, $default);
    }

    return cms_config('deep_collections', $default);
}

/**
 * Get the CMS user role(s).
 *
 * @param  string|null  $key
 * @param  mixed  $default
 * @return string|array
 */
function user_roles($key = null, $default = null)
{
    if (! is_null($key)) {
        return cms_config('user_roles.' . $key, $default);
    }

    return cms_config('user_roles', $default);
}

/**
 * Get the CMS icon name.
 *
 * @param  string  $key
 * @param  mixed  $default
 * @return string|array
 */
function icon_type($key, $default = null)
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
function glide($path, $type)
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
 * Convert bytes to human readable format.
 *
 * @param  int  $bytes
 * @param  int  $precision
 * @return string
 */
function format_bytes($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = (float) max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= pow(1024, $pow);

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Cut the text after the limit and breakpoint.
 *
 * @param  string  $string
 * @param  int  $limit
 * @param  string  $break
 * @param  string  $end
 * @return string
 */
function text_limit($string, $limit = 100, $break = '.', $end = '')
{
    $string = str_replace('&nbsp;', ' ', strip_tags($string));
    $string = preg_replace('/\s\s+/', ' ', $string);

    if (($stringLength = mb_strlen($string, 'UTF-8')) <= $limit) {
        return $string;
    }

    $breakpoint = ($break ? mb_strpos($string, $break, $limit, 'UTF-8') : $limit);

    if ($breakpoint < (mb_strlen($string, 'UTF-8') - 1)) {
        $string = mb_substr($string, 0, $breakpoint, 'UTF-8') . $end;
    }

    return $string;
}

/**
 * Get youtube video id from url.
 *
 * @param  string  $url
 * @param  array  $allowQueryStrings
 * @param  bool  $strict
 * @return string
 */
function get_youtube_id($url, array $allowQueryStrings = [], $strict = false)
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

        return (string) end($path) . $allowQueryStrings;
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
function get_youtube_embed($url, array $allowQueryStrings = [])
{
    return 'https://www.youtube.com/embed/' . get_youtube_id($url, $allowQueryStrings);
}

/**
 * Calculate age based on the date.
 *
 * @param  string  $dob
 * @return int
 */
function get_age($dob)
{
    $dob = new DateTime($dob);

    $today = new DateTime('today');

    $age = $dob->diff($today)->y;

    return $age;
}

/**
 * Register a database query listener and log the queries.
 *
 * @return void
 */
function log_executed_db_queries()
{
    $filename = storage_path('logs/queries.log');
    $separator = '------------------------------' . PHP_EOL;

    if (file_exists($filename)) {
        @unlink($filename);
    }

    file_put_contents($filename, $separator);

    app('events')->listen(QueryExecuted::class, function($query) use ($filename, $separator) {
        $conn     = 'Connection: ' . $query->connectionName . PHP_EOL;
        $sql      = 'SQL: ' . $query->sql . PHP_EOL;
        $bindings = 'Bindings: ' . implode(', ', (array) $query->bindings) . PHP_EOL;
        $time     = 'Time: ' . $query->time . ' ms' . PHP_EOL;
        $data     = $conn . $sql . $bindings . $time . $separator;

        $flags = FILE_APPEND | LOCK_EX;

        file_put_contents($filename, $data, $flags);
    });
}

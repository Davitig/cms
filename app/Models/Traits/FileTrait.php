<?php

namespace App\Models\Traits;

use Exception;
use Illuminate\Filesystem\Filesystem;

trait FileTrait
{
    use LanguageTrait, PositionableTrait;

    /**
     * Get the model files.
     *
     * @param  int  $forignId
     * @param  bool  $separated
     * @param  array|mixed  $columns
     * @return \Illuminate\Support\Collection
     */
    public function getFiles($forignId, $separated = true, $columns = ['*'])
    {
        $imageExt = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

        $files = $this->forPublic($forignId)->get($columns);

        if (! $separated) {
            return $files;
        }

        $images = $mixed = [];

        if (! $files->isEmpty()) {
            foreach ($files as $key => $value) {
                $item = $files->pull($key);

                if (in_array(strtolower(pathinfo($item->file, PATHINFO_EXTENSION)), $imageExt)) {
                    $images[] = $item;
                } else {
                    $mixed[] = $item;
                }
            }
        }

        $files->put('images', $images);
        $files->put('mixed', $mixed);

        return $files;
    }

    /**
     * Build a public query.
     *
     * @param  int  $forignId
     * @param  mixed  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function forAdmin($forignId, $currentLang = true)
    {
        return $this->joinLanguage($currentLang)
            ->byForeign($forignId)
            ->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  int  $forignId
     * @param  mixed  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function forPublic($forignId, $currentLang = true)
    {
        return $this->joinLanguage($currentLang)
            ->byForeign($forignId)
            ->whereVisible()
            ->positionDesc();
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \App\Models\Eloquent\Builder
     */
    public function whereVisible($value = 1)
    {
        return $this->where('visible', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            if (isset($attributes['page_id'])) {
                $attributes['position'] = $this->byForeign($attributes['page_id'])
                        ->max('position') + 1;
            } else {
                $attributes['position'] = $this->max('position') + 1;
            }
        }

        return parent::create($attributes);
    }

    /**
     * Get the file size.
     *
     * @param  string|null $file
     * @return string
     */
    public function getFileSize($file = null)
    {
        try {
            $size = (new Filesystem)->size(
                base_path(trim(parse_url($file ?: $this->file, PHP_URL_PATH), '/'))
            );
        } catch (Exception $e) {
            $size = 0;
        }

        return format_bytes($size);
    }
}

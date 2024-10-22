<?php

namespace App\Models\Traits;

use App\Models\Eloquent\Builder;
use App\Models\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Filesystem\Filesystem;

trait FileTrait
{
    use LanguageTrait, PositionableTrait;

    /**
     * Get the model files.
     *
     * @param  int  $foreignId
     * @param  bool  $separated
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     */
    public function getFiles(int $foreignId, bool $separated = true, array|string $columns = ['*']): Collection
    {
        $imageExt = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

        $files = $this->forPublic($foreignId)->get($columns);

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
     * @param  int  $foreignId
     * @param  bool|string  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function forAdmin(int $foreignId, bool|string $currentLang = true): Builder
    {
        return $this->joinLanguage($currentLang)
            ->byForeign($foreignId)
            ->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  int  $foreignId
     * @param  bool|string  $currentLang
     * @return \App\Models\Eloquent\Builder
     */
    public function forPublic(int $foreignId, bool|string $currentLang = true)
    {
        return $this->joinLanguage($currentLang)
            ->byForeign($foreignId)
            ->whereVisible()
            ->positionDesc();
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \App\Models\Eloquent\Builder
     */
    public function whereVisible(int $value = 1): Builder
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
     * @param  string|null  $file
     * @return string
     */
    public function getFileSize(string $file = null): string
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

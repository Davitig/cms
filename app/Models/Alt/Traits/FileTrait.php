<?php

namespace App\Models\Alt\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;

trait FileTrait
{
    use PositionableTrait;

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
        $files = $this->forPublic($foreignId)->get($columns);

        if ($files->isEmpty() || ! $separated) {
            return $files;
        }

        $imageExt = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

        $images = new Collection; $mixed = new Collection;

        foreach ($files as $key => $value) {
            $item = $files->pull($key);

            if (in_array(strtolower(pathinfo($item->file, PATHINFO_EXTENSION)), $imageExt)) {
                $images->add($item);
            } else {
                $mixed->add($item);
            }
        }

        $files->put('images', $images); $files->put('mixed', $mixed);

        return $files;
    }

    /**
     * Build a public query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignId
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin(Builder $query, int $foreignId, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)
            ->foreignKey($foreignId)
            ->positionDesc();
    }

    /**
     * Build a public query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignId
     * @param  mixed  $currentLang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPublic(Builder $query, int $foreignId, mixed $currentLang = true): Builder
    {
        return $query->joinLanguage($currentLang)
            ->foreignKey($foreignId)
            ->whereVisible()
            ->positionDesc();
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereVisible(Builder $query, int $value = 1): Builder
    {
        return $query->where($this->qualifyColumn('visible'), $value);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            $attributes['position'] = $this->max('position') + 1;
        }

        return parent::create($attributes);
    }

    /**
     * Get the file size.
     *
     * @param  string|null  $file
     * @return string
     */
    public function getFileSize(?string $file = null): string
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

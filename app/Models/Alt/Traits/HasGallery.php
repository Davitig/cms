<?php

namespace App\Models\Alt\Traits;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Gallery\Gallery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait HasGallery
{
    use PositionableTrait;

    /**
     * Get the gallery key name.
     *
     * @return string|null
     */
    public function getGalleryKeyName(): ?string
    {
        return $this->galleryKey ?? 'gallery_id';
    }

    /**
     * Get the data based on the admin gallery.
     *
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminGallery(Gallery $gallery, array|string $columns = ['*']):
    LengthAwarePaginator
    {
        return $this->adminGallery($gallery)
            ->paginate($gallery->admin_per_page, $columns);
    }

    /**
     * Get the data based on the public gallery.
     *
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPublicGallery(Gallery $gallery, array|string $columns = ['*']):
    LengthAwarePaginator
    {
        return $this->publicGallery($gallery)
            ->paginate($gallery->web_per_page, $columns);
    }

    /**
     * Build a query based on the admin gallery.
     *
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function adminGallery(Gallery $gallery): Builder
    {
        return $this->byGallery($gallery->id)
            ->orderBy(
                $this->getTable() . '.'
                . $gallery->admin_order_by, $gallery->admin_sort
            );
    }

    /**
     * Build a query based on the public gallery.
     *
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function publicGallery(Gallery $gallery): Builder
    {
        return $this->byGallery($gallery->id)
            ->whereVisible()
            ->orderBy(
                $this->getTable() . '.' .
                $gallery->web_order_by, $gallery->web_sort
            );
    }

    /**
     * Build a query based on the gallery.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function byGallery(int $id): Builder
    {
        return $this->joinLanguage()->galleryId($id);
    }

    /**
     * Get the same type gallery instance.
     *
     * @param  string|null  $type
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function byType(?string $type = null): Builder
    {
        return (new Gallery)->joinLanguage()->where(
            'type', is_null($type) ? static::TYPE : $type
        );
    }

    /**
     * Add a where "gallery_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function galleryId(int $id): Builder|static
    {
        return $this->where('gallery_id', $id);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function whereVisible(int $value = 1): Builder|static
    {
        return $this->where('visible', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            if (isset($attributes['gallery_id'])) {
                $attributes['position'] = $this->galleryId($attributes['gallery_id'])
                        ->max('position') + 1;
            } else {
                $attributes['position'] = $this->max('position') + 1;
            }
        }

        return parent::create($attributes);
    }
}

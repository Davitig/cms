<?php

namespace App\Models\Alt\Traits;

use App\Models\Gallery\Gallery;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetAdminGallery(
        Builder $query, Gallery $gallery, array|string $columns = ['*']
    ): LengthAwarePaginator
    {
        return $query->adminGallery($gallery)
            ->paginate($gallery->admin_per_page, $columns);
    }

    /**
     * Get the data based on the public gallery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetPublicGallery(
        Builder $query, Gallery $gallery, array|string $columns = ['*']
    ): LengthAwarePaginator
    {
        return $query->publicGallery($gallery)
            ->paginate($gallery->web_per_page, $columns);
    }

    /**
     * Build a query based on the admin gallery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminGallery(Builder $query, Gallery $gallery): Builder
    {
        return $query->byGallery($gallery->id)
            ->orderBy(
                $this->getTable() . '.' . $gallery->admin_order_by, $gallery->admin_sort
            );
    }

    /**
     * Build a query based on the public gallery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Gallery\Gallery  $gallery
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublicGallery(Builder $query, Gallery $gallery): Builder
    {
        return $query->byGallery($gallery->id)
            ->whereVisible()
            ->orderBy(
                $this->getTable() . '.' . $gallery->web_order_by, $gallery->web_sort
            );
    }

    /**
     * Build a query based on the gallery.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByGallery(Builder $query, int $id): Builder
    {
        return $query->joinLanguage()->galleryId($id);
    }

    /**
     * Add a where "gallery_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGalleryId(Builder $query, int $id): Builder
    {
        return $query->where('gallery_id', $id);
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

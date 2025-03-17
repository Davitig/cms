<?php

namespace App\Models\Alt\Traits;

use App\Models\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait HasCollection
{
    use PageableTrait, PositionableTrait;

    /**
     * Get the data based on the admin collection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetAdminCollection(
        Builder $query, Collection $collection, array|string $columns = ['*']
    ): LengthAwarePaginator
    {
        return $query->adminCollection($collection)
            ->paginate($collection->admin_per_page, $columns);
    }

    /**
     * Get the data based on the public collection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetPublicCollection(
        Builder $query, Collection $collection, array|string $columns = ['*']
    ): LengthAwarePaginator
    {
        return $query->publicCollection($collection)
            ->paginate($collection->web_per_page, $columns);
    }

    /**
     * Build a query based on the admin collection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdminCollection(Builder $query, Collection $collection): Builder
    {
        return $query->forAdmin($collection->id)
            ->orderBy(
                $this->qualifyColumn($collection->admin_order_by), $collection->admin_sort
            );
    }

    /**
     * Build a query based on the public collection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublicCollection(Builder $query, Collection $collection): Builder
    {
        return $query->forPublic($collection->id)
            ->orderBy(
                $this->qualifyColumn($collection->web_order_by), $collection->web_sort
            );
    }

    /**
     * Build an admin query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForAdmin(
        Builder      $query,
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder
    {
        return $query->when(! is_null($collectionId), function ($q) use ($collectionId) {
            return $q->collectionId($collectionId);
        })->joinLanguage($currentLang, $columns);
    }

    /**
     * Build a public query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForPublic(
        Builder      $query,
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder
    {
        return $query->when(! is_null($collectionId), function ($q) use ($collectionId) {
            return $q->collectionId($collectionId);
        })->joinLanguage($currentLang, $columns)->whereVisible();
    }

    /**
     * Build a query based on the slug.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $slug
     * @param  int|null  $collectionId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBySlug(Builder $query, string $slug, ?int $collectionId = null): Builder
    {
        return $query->where('slug', $slug)->forPublic($collectionId);
    }

    /**
     * Add a where "collection_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCollectionId(Builder $query, int $id): Builder
    {
        return $query->where('collection_id', $id);
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
            if (isset($attributes['collection_id'])) {
                $attributes['position'] = $this->collectionId($attributes['collection_id'])
                        ->max('position') + 1;
            } else {
                $attributes['position'] = $this->max('position') + 1;
            }
        }

        return parent::create($attributes);
    }
}

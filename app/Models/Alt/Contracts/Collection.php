<?php

namespace App\Models\Alt\Contracts;

use App\Models\Alt\Eloquent\Builder;
use App\Models\Collection as ModelCollection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface Collection
{
    /**
     * Get the data based on the admin collection.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetAdminCollection(
        Builder $query, ModelCollection $collection, array|string $columns = ['*']
    ): LengthAwarePaginator;

    /**
     * Get the data based on the public collection.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function scopeGetPublicCollection(
        Builder $query, ModelCollection $collection, array|string $columns = ['*']
    ): LengthAwarePaginator;

    /**
     * Build a query based on the admin collection.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeAdminCollection(Builder $query, ModelCollection $collection): Builder;

    /**
     * Build a query based on the public collection.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopePublicCollection(Builder $query, ModelCollection $collection): Builder;

    /**
     * Build an admin query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForAdmin(
        Builder      $query,
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder;

    /**
     * Build a public query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeForPublic(
        Builder      $query,
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder;

    /**
     * Build a query based on the slug.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  string  $slug
     * @param  int|null  $collectionId
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeBySlug(Builder $query, string $slug, ?int $collectionId = null): Builder;

    /**
     * Build a query based on the collection id and slug.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $collectionId
     * @param  string  $slug
     * @param  int|null  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeByCollectionSlug(
        Builder $query, int $collectionId, string $slug, ?int $id = null
    ): Builder;

    /**
     * Add a where "collection_id" clause to the query.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $query
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function scopeCollectionId(Builder $query, int $id): Builder;
}

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
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminCollection(ModelCollection $collection, array|string $columns = ['*']):
    LengthAwarePaginator;

    /**
     * Get the data based on the public collection.
     *
     * @param  \App\Models\Collection  $collection
     * @param  array|string  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPublicCollection(ModelCollection $collection, array|string $columns = ['*']):
    LengthAwarePaginator;

    /**
     * Build a query based on the admin collection.
     *
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function adminCollection(ModelCollection $collection): Builder;

    /**
     * Build a query based on the public collection.
     *
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function publicCollection(ModelCollection $collection): Builder;

    /**
     * Build an admin query.
     *
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function forAdmin(
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder;

    /**
     * Build a public query.
     *
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array|string  $columns
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function forPublic(
        ?int         $collectionId = null,
        mixed        $currentLang = true,
        array|string $columns = []): Builder;

    /**
     * Build a query based on the slug.
     *
     * @param  string  $slug
     * @param  int|null  $collectionId
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function bySlug(string $slug, ?int $collectionId = null): Builder;

    /**
     * Build a query based on the collection id and slug.
     *
     * @param  int  $collectionId
     * @param  string  $slug
     * @param  int|null  $id
     * @return \App\Models\Alt\Eloquent\Builder
     */
    public function byCollectionSlug(int $collectionId, string $slug, ?int $id = null): Builder;

    /**
     * Add a where "collection_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Alt\Eloquent\Builder|static
     */
    public function collectionId(int $id): Builder|static;
}

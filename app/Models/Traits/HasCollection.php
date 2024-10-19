<?php

namespace App\Models\Traits;

use App\Models\Collection;

trait HasCollection
{
    use PageableTrait, PositionableTrait;

    /**
     * Get the data based on the admin collection.
     *
     * @param  \App\Models\Collection  $collection
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAdminCollection(Collection $collection, $columns = ['*'])
    {
        return $this->adminCollection($collection)
            ->paginate($collection->admin_per_page, $columns);
    }

    /**
     * Get the data based on the public collection.
     *
     * @param  \App\Models\Collection  $collection
     * @param  array  $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPublicCollection(Collection $collection, $columns = ['*'])
    {
        return $this->publicCollection($collection)
            ->paginate($collection->web_per_page, $columns);
    }

    /**
     * Build a query based on the admin collection.
     *
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Builder\Builder
     */
    public function adminCollection(Collection $collection)
    {
        return $this->forAdmin($collection->id)
            ->orderBy($this->getTable() . '.' . $collection->admin_order_by, $collection->admin_sort);
    }

    /**
     * Build a query based on the public collection.
     *
     * @param  \App\Models\Collection  $collection
     * @return \App\Models\Builder\Builder
     */
    public function publicCollection(Collection $collection)
    {
        return $this->forPublic($collection->id)
            ->orderBy($this->getTable() . '.' . $collection->web_order_by, $collection->web_sort);
    }

    /**
     * Build an admin query.
     *
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array  $columns
     * @return \App\Models\Builder\Builder
     */
    public function forAdmin($collectionId = null, $currentLang = true, array $columns = [])
    {
        return $this->when(! is_null($collectionId), function ($q) use ($collectionId) {
            return $q->collectionId($collectionId);
        })->joinLanguage($currentLang, $columns);
    }

    /**
     * Build a public query.
     *
     * @param  int|null  $collectionId
     * @param  mixed  $currentLang
     * @param  array  $columns
     * @return \App\Models\Builder\Builder
     */
    public function forPublic($collectionId = null, $currentLang = true, array $columns = [])
    {
        return $this->when(! is_null($collectionId), function ($q) use ($collectionId) {
            return $q->collectionId($collectionId);
        })->joinLanguage($currentLang, $columns)->whereVisible();
    }

    /**
     * Build a query based on the slug.
     *
     * @param  string  $slug
     * @param  int|null  $collectionId
     * @return \App\Models\Builder\Builder
     */
    public function bySlug($slug, $collectionId = null)
    {
        return $this->where('slug', $slug)->forPublic($collectionId);
    }

    /**
     * Build a query based on the collection id and slug.
     *
     * @param  int $collectionId
     * @param  string  $slug
     * @param  int|null  $id
     * @return \App\Models\Builder\Builder
     */
    public function byCollectionSlug($collectionId, $slug, $id = null)
    {
        return $this->collectionId($collectionId)->bySlug($slug, $id);
    }

    /**
     * Add a where "collection_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Builder\Builder
     */
    public function collectionId($id)
    {
        return $this->where('collection_id', $id);
    }

    /**
     * Add a where "visible" clause to the query.
     *
     * @param  int  $value
     * @return \App\Models\Builder\Builder
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

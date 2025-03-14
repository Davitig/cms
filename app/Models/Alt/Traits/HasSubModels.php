<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait HasSubModels
{
    /**
     * Add a where "parent_id" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeParentId(Builder $query, int $id): Builder
    {
        return $query->where('parent_id', $id);
    }

    /**
     * Get sibling models.
     *
     * @param  array|string  $columns
     * @param  int|null  $id
     * @param  int|null  $parentId
     * @param  bool|int  $recursive
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSiblingModels(
        array|string $columns = ['*'],
        ?int         $parentId = null,
        ?int         $id = null,
        bool|int     $recursive = false
    ): Collection
    {
        if (! $parentId ??= $this->parent_id || ! $id ??= $this->getKey()) {
            return $this->newCollection();
        }

        $models = $this->forPublic()
            ->parentId($parentId)
            ->whereKeyNot($id)
            ->positionAsc()
            ->get($columns);

        if ($models->count() > 1) {
            return $recursive ? $models->each(function ($item) use ($columns, $recursive) {
                $item->subModels = $this->getSubModels($columns, $recursive, $item->id);
            }) : $models;
        }

        return $models->make();
    }

    /**
     * Get sub models.
     *
     * @param  array|string  $columns
     * @param  int|null  $parentId
     * @param  bool|int  $recursive
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSubModels(
        array|string $columns = ['*'], ?int $parentId = null, bool|int $recursive = false
    ): Collection
    {
        $columns = (array) $columns;

        $columns = current($columns) == '*' ? $columns : array_merge($columns, ['id']);

        $models = $this->forPublic()->where(
            'parent_id', $parentId ?: $this->getKey()
        )->positionAsc()->get($columns);

        if (is_int($recursive) && $recursive > 0) {
            $recursive--;
        }

        return $recursive ? $models->each(function ($item) use ($columns, $recursive) {
            $item->subModels = $this->getSubModels($columns, $recursive, $item->id);
        }) : $models;
    }

    /**
     * Determine if the model has a sibling model.
     *
     * @param  int|null  $parentId
     * @param  int|null  $id
     * @return bool
     */
    public function hasSiblingModel(?int $parentId = null, ?int $id = null): bool
    {
        if (! $parentId ??= $this->parent_id || ! $id ??= $this->getKey()) {
            return false;
        }

        return $this->parentId($parentId)->where('id', '<>', $id)->exists();
    }

    /**
     * Determine if the model has a submodel.
     *
     * @param  int|null  $parentId
     * @return bool
     */
    public function hasSubModel(?int $parentId = null): bool
    {
        if (! $parentId ??= $this->getKey()) {
            return false;
        }

        return $this->parentId($parentId)->exists();
    }

    /**
     * Get model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @return string|null
     */
    public function getFullSlug(?int $value = null, ?string $column = null): ?string
    {
        return $this->fullSlug($value, $column)->full_slug;
    }

    /**
     * Set model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @return $this
     */
    public function fullSlug(?int $value = null, ?string $column = null): static
    {
        $this->full_slug ??= $this->slug;

        if (! $value ??= $this->parent_id) {
            return $this;
        }

        if (is_null($column)) {
            $column = $this->getKeyName();
        }

        $model = (new static)->where($column, $value)->first(['slug', 'parent_id']);

        if (is_null($model)) {
            return $this;
        }

        $this->full_slug = trim($model->slug . '/' . $this->full_slug, '/');

        return $this->fullSlug($model->parent_id, $column);
    }
}

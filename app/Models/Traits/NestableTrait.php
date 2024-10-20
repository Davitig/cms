<?php

namespace App\Models\Traits;

use Closure;

trait NestableTrait
{
    /**
     * Add a where "parent_id" clause to the query.
     *
     * @param  int  $id
     * @return \App\Models\Builder\Builder
     */
    public function parentId($id)
    {
        return $this->where('parent_id', $id);
    }

    /**
     * Get the base model.
     *
     * @param  array  $columns
     * @param  int|null  $id
     * @param  \Closure|null  $callback
     * @return static
     */
    public function getBaseModel($columns = ['*'], $id = null, Closure $callback = null)
    {
        if (! ($id = ($id ?: $this->parent_id))) {
            return $this;
        }

        if (is_null($model = $this->where('id', $id)->forPublic()->first($columns))) {
            return $this;
        }

        if (! $model->parent_id || (! is_null($callback) && $callback($model))) {
            return $model;
        }

        return $this->getBaseModel($columns, $model->parent_id, $callback);
    }

    /**
     * Get sibling models.
     *
     * @param  array  $columns
     * @param  bool|int  $recursive
     * @param  bool  $self
     * @return \Illuminate\Support\Collection
     */
    public function getSiblingModels($columns = ['*'], $recursive = false, $self = true)
    {
        if (! $this->parent_id) {
            return $this->newCollection();
        }

        $models = $this->when(! $self, function ($q) {
            return $q->where('id', '<>', $this->getKey());
        })->forPublic()->parentId($this->parent_id)->positionAsc()->get($columns);

        if ($self && $models->count() > 1) {
            return $recursive ? $models->each(function ($item) use ($columns, $recursive) {
                $item->subModels = $this->getSubModels($columns, $recursive, $item->id);
            }) : $models;
        } else {
            return $models->make();
        }
    }

    /**
     * Get sub models.
     *
     * @param  array  $columns
     * @param  bool|int  $recursive
     * @param  int|null  $value
     * @param  string  $key
     * @return \Illuminate\Support\Collection
     */
    public function getSubModels($columns = ['*'], $recursive = false, $value = null, $key = 'parent_id')
    {
        if (! is_array($columns)) {
            $columns = [$columns];
        }

        $columns = current($columns) == '*' ? $columns : array_merge($columns, ['id']);

        $models = $this->forPublic()->where(
            $key, $value ?: $this->getKey()
        )->positionAsc()->get($columns);

        if (is_int($recursive) && $recursive > 0) {
            $recursive--;
        }

        return $recursive ? $models->each(function ($item) use ($columns, $recursive) {
            $item->subModels = $this->getSubModels($columns, $recursive, $item->id);
        }) : $models;
    }

    /**
     * Get model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @return string|null
     */
    public function getFullSlug($value = null, $column = null)
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
    public function fullSlug($value = null, $column = null)
    {
        $this->full_slug ??= $this->slug;

        if (! ($value = (is_null($value) ? $this->parent_id : $value))) {
            return $this;
        }

        if (is_null($column)) {
            $column = $this->getKeyName();
        }

        $model = (new static)->where($column, $value)->first(['slug', 'parent_id']);

        if (is_null($model)) {
            return $this;
        }

        $this->full_slug = trim(
            $model->slug . '/' . $this->full_slug, '/'
        );

        return $this->fullSlug($model->parent_id, $column);
    }
}

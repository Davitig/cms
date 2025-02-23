<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\RecordsNotFoundException;

trait QueriesTrait
{
    /**
     * Get the fillable attributes for the model including statically defined columns.
     *
     * @param  bool  $prefixTable
     * @return array<string>
     */
    public function getFillableWithStaticColumns(bool $prefixTable = false): array
    {
        $tablePrefix = ($prefixTable ? $this->getTable() . '.' : '');

        $fillable = array_merge([$this->getKeyName()], $this->getFillable(), $this->getDates());

        if (empty($tablePrefix)) {
            return $fillable;
        }

        return array_map(fn ($value) => $tablePrefix . $value, $fillable);
    }

    /**
     * Execute the query as a "select" statement or throw an exception.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     *
     */
    public function scopeGetOrFail(Builder $query, array|string $columns = ['*']): Collection
    {
        $collection = $query->get($columns);

        $collection->isEmpty() and throw new RecordsNotFoundException;

        return $collection;
    }

    /**
     * Add an "order by" primary key asc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderAsc(Builder $query): Builder
    {
        return $query->orderBy($this->qualifyColumn($this->getKeyName()));
    }

    /**
     * Add an "order by" primary key desc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderDesc(Builder $query): Builder
    {
        return $query->orderByDesc($this->qualifyColumn($this->getKeyName()));
    }

    /**
     * Add an "order by" created at asc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedAsc(Builder $query): Builder
    {
        return $query->orderBy($this->qualifyColumn($this->getCreatedAtColumn()));
    }

    /**
     * Add an "order by" created at desc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedDesc(Builder $query): Builder
    {
        return $query->orderByDesc($this->qualifyColumn($this->getCreatedAtColumn()));
    }
}

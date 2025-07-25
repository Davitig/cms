<?php

namespace App\Concerns\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Arr;

trait ExtendsQueries
{
    /**
     * Add a new qualified select column to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAddQualifiedSelect(Builder $query, ...$columns): Builder
    {
        return $query->addSelect($this->qualifyColumns(Arr::flatten($columns)));
    }

    /**
     * Add a select exists statement to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $from
     * @param  string  $foreignKey
     * @param  string  $as
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectExists(
        Builder $query, string $from, string $foreignKey, string $as
    ): Builder
    {
        $queryString = $query->getQuery()->newQuery()
            ->from($from)
            ->whereColumn($this->qualifyColumn($this->getKeyName()), $foreignKey)
            ->toSql();

        return $query->selectRaw('(select exists(' . $queryString . ')) as ' . $as);
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

        if ($collection->isEmpty()) {
            throw new RecordsNotFoundException;
        }

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

    /**
     * Add an "order by" updated at asc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedAsc(Builder $query): Builder
    {
        return $query->orderBy($this->qualifyColumn($this->getUpdatedAtColumn()));
    }

    /**
     * Add an "order by" updated at desc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedDesc(Builder $query): Builder
    {
        return $query->orderByDesc($this->qualifyColumn($this->getUpdatedAtColumn()));
    }
}

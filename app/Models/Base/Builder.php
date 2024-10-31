<?php

namespace App\Models\Base;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\JoinClause;
use InvalidArgumentException;

class Builder extends EloquentBuilder
{
    /**
     * The property subjects where prefixes will be applied.
     *
     * @var array
     */
    protected array $propertyPrefixes = ['columns', 'from', 'joins', 'wheres'];

    /**
     * {@inheritDoc}
     */
    public function addSelect($column): Builder|QueryBuilder|static
    {
        parent::addSelect($column);

        if (! empty($this->getQuery()->columns)) {
            $this->getQuery()->columns = array_unique($this->getQuery()->columns, SORT_REGULAR);
        }

        return $this;
    }

    /**
     * Add a select exists statement to the query.
     *
     * @param  \Closure|string|\Illuminate\Database\Query\Builder  $query
     * @param  string  $as
     * @param  bool  $not
     * @return \Illuminate\Database\Query\Builder|static
     *
     * @throws \InvalidArgumentException
     */
    public function selectExists(mixed $query, string $as, bool $not = false): QueryBuilder|static
    {
        if ($query instanceof Closure) {
            $callback = $query;

            $callback($query = $this->query->newQuery());
        }

        if ($query instanceof QueryBuilder) {
            $bindings = $query->getBindings();

            $query = $query->toSql();
        } elseif (is_string($query)) {
            $bindings = [];
        } else {
            throw new InvalidArgumentException;
        }

        return $this->selectRaw(
            '(select '.($not ? 'not ' : '').'exists('.$query.')) as '
            . $this->query->getGrammar()->wrap($as),
            $bindings
        );
    }

    /**
     * Add a select not exists statement to the query.
     *
     * @param  \Closure|string|\Illuminate\Database\Query\Builder  $query
     * @param  string  $as
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function selectNotExists(mixed $query, string $as): QueryBuilder|static
    {
        return $this->selectExists($query, $as, true);
    }

    /**
     * Determine if any rows exist for the current query or fail.
     *
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function existsOrFail(): bool
    {
        return $this->exists() or throw new ModelNotFoundException;
    }

    /**
     * Get the first record or instantiate it.
     *
     * @param  array|string  $columns
     * @return \App\Models\Base\Model
     */
    public function firstNew(array|string $columns = ['*']): Model
    {
        if (! is_null($model = $this->first($columns))) {
            return $model;
        }

        return $this->newModelInstance();
    }

    /**
     * Execute the query and get the first result attribute.
     *
     * @param  string  $attribute
     * @param  int|string|null  $value
     * @param  string|null  $column
     * @return string|null
     */
    public function firstAttr(
        string          $attribute,
        int|string|null $value = null,
        ?string         $column = null): ?string
    {
        return $this->when(! is_null($value), function ($q) use ($value, $column) {
            return $q->where($column ?: $this->getModel()->getKeyName(), $value);
        })->value($attribute);
    }

    /**
     * Execute the query and get the first result attribute or throw an exception.
     *
     * @param  string  $attribute
     * @param  int|string|null  $value
     * @param  string|null  $column
     * @return string|null
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function firstAttrOrFail(
        string          $attribute,
        int|string|null $value = null,
        ?string         $column = null): ?string
    {
        if (is_null($attribute = $this->firstAttr($attribute, $value, $column))) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        return $attribute;
    }

    /**
     * {@inheritDoc}
     */
    public function get($columns = ['*']): Collection
    {
        $this->prefixColumnsOnJoin();

        return parent::get($columns);
    }

    /**
     * Execute the query as a "select" statement or throw an exception.
     *
     * @param  array|string  $columns
     * @return \Illuminate\Database\Eloquent\Collection<int, Model>
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getOrFail(array|string $columns = ['*']): Collection
    {
        $collection = $this->get($columns);

        $collection->isEmpty() and throw new ModelNotFoundException;

        return $collection;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $values
     * @param  string|null  $exclude
     * @return int
     */
    public function update(array $values = [], ?string $exclude = null): int
    {
        $this->model->setFillableByUpdatable($exclude);

        return parent::update($values);
    }

    /**
     * Prefix columns with the model table name if join clause is set.
     *
     * @return void
     */
    protected function prefixColumnsOnJoin(): void
    {
        if (! isset($this->query->joins)) {
            return;
        }

        $properties = $this->propertyPrefixes;

        foreach ($properties as $i => $binding) {
            if (! is_array($binding = $this->query->$binding)) {
                continue;
            }

            foreach ($binding as $bind => $value) {
                if ($value instanceof JoinClause) {
                    $wheres = $value->wheres;

                    foreach ($wheres as $key => $clause) {
                        if (! empty($clause['nested'])) {
                            continue;
                        }

                        if (empty($clause['operator'])) {
                            $value->wheres[$key]['operator'] = "=";
                        }

                        if (isset($value->wheres[$key]['first'])
                            && ! str_contains($first = $value->wheres[$key]['first'], '.')
                        ) {
                            $value->wheres[$key]['first'] = "{$this->query->from}.{$first}";
                        }

                        if (($secondExists = ! empty($value->wheres[$key]['second']))
                            && is_string($second = $value->wheres[$key]['second'])
                            && ! str_contains($second, '.')
                        ) {
                            $value->wheres[$key]['second'] = "{$value->table}.{$second}";
                        } elseif (! $secondExists) {
                            $value->wheres[$key]['second'] = "{$value->table}.id";
                        }
                    }
                } elseif (is_string($value) && $value == 'id') {
                    $this->query->{$properties[$i]}[$bind] = $this->query->from . '.' . $value;
                } elseif (is_array($value)
                    && isset($value['column'])
                    && ! str_contains($value['column'], '.')
                ) {
                    $columns = array_merge(
                        array_values($this->model->getFillable()),
                        array_values($this->model->getDates())
                    );

                    if ($value['column'] == 'id' || in_array($value['column'], $columns)) {
                        $table = $this->query->from . '.';

                        $this->query->{$properties[$i]}[$bind]['column'] = $table . $value['column'];
                    }
                }
            }
        }
    }

    /**
     * Add an "order by" primary key asc clause to the query.
     *
     * @param  bool|string  $table
     * @return $this
     */
    public function orderAsc(bool|string $table = false): Builder
    {
        return $this->orderBy(
            $this->getTableNameWithDot($table) . $this->getModel()->getKeyName()
        );
    }

    /**
     * Add an "order by" primary key desc clause to the query.
     *
     * @param  bool|string  $table
     * @return $this
     */
    public function orderDesc(bool|string $table = false): Builder
    {
        return $this->orderByDesc(
            $this->getTableNameWithDot($table) . $this->getModel()->getKeyName()
        );
    }

    /**
     * Add an "order by" created at asc clause to the query.
     *
     * @param  bool|string  $table
     * @return $this
     */
    public function createdAsc(bool|string $table = false): Builder
    {
        return $this->orderBy($this->getTableNameWithDot($table) . 'created_at');
    }

    /**
     * Add an "order by" created at desc clause to the query.
     *
     * @param  bool|string  $table
     * @return $this
     */
    public function createdDesc(bool|string $table = false): Builder
    {
        return $this->orderByDesc($this->getTableNameWithDot($table) . 'created_at');
    }

    /**
     * Get the name of the table with the added dot.
     *
     * @param  bool|string  $table
     * @return string
     */
    protected function getTableNameWithDot(bool|string $table): string
    {
        if ($table = (($table === true) ? $this->model->getTable() : $table)) {
            return $table . '.';
        }

        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->model, $method)) {
            $this->model->setEloquentBuilder($this);

            return call_user_func_array([$this->model, $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }
}

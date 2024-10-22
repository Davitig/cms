<?php

namespace App\Models\Eloquent;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
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
    protected $propertyPrefixes = ['columns', 'from', 'joins', 'wheres'];

    /**
     * {@inheritDoc}
     */
    public function addSelect($column)
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
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @param  string  $as
     * @param  bool  $not
     * @return \Illuminate\Database\Query\Builder|static
     *
     * @throws \InvalidArgumentException
     */
    public function selectExists($query, $as, $not = false)
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
     * @param  \Closure|\Illuminate\Database\Query\Builder|string $query
     * @param  string  $as
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function selectNotExists($query, $as)
    {
        return $this->selectExists($query, $as, true);
    }

    /**
     * Determine if any rows exist for the current query or fail.
     *
     * @return bool
     */
    public function existsOrFail()
    {
        return $this->exists() or abort(404);
    }

    /**
     * Get model full slug.
     *
     * @param  int|null  $value
     * @param  string|null  $column
     * @param  mixed  $currentLang
     * @return string|null
     */
    public function getFullSlug($value = null, $column = null, $currentLang = true)
    {
        if ($result = $this->fullSlug($value, $column, $currentLang)) {
            return $result->full_slug;
        }

        return null;
    }

    /**
     * Find a model by its query or instantiate it.
     *
     * @param  array  $columns
     * @return \App\Models\Eloquent\Model
     */
    public function firstNew($columns = ['*'])
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
     * @param  int|null  $value
     * @param  string|null  $column
     * @return string|null
     */
    public function firstAttr($attribute, $value = null, $column = null)
    {
        return $this->when(! is_null($value), function ($q) use ($value, $column) {
            return $q->where($column ?: $this->getModel()->getKeyName(), $value);
        })->value($attribute);
    }

    /**
     * Execute the query and get the first result attribute or throw an exception.
     *
     * @param  string  $attribute
     * @param  int|null  $value
     * @param  string|null  $column
     * @return string|null
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function firstAttrOrFail($attribute, $value = null, $column = null)
    {
        if (is_null($attribute = $this->firstAttr($attribute, $value, $column))) {
            throw (new ModelNotFoundException)->setModel(get_class($this));
        }

        return $attribute;
    }

    /**
     * {@inheritDoc}
     */
    public function get($columns = ['*'])
    {
        $this->prefixColumnsOnJoin($columns);

        return parent::get($columns);
    }

    /**
     * Execute the query as a "select" statement or throw an exception.
     *
     * @param  array  $columns
     * @return \Illuminate\Support\Collection
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function getOrFail($columns = ['*'])
    {
        $collection = $this->get($columns);

        $collection->isEmpty() and abort(404);

        return $collection;
    }

    /**
     * Update the model in the database.
     *
     * @param  array   $attributes
     * @param  string  $exclude
     * @return int
     */
    public function update(array $attributes = [], $exclude = null)
    {
        $this->model->setFillableByUpdatable($exclude);

        return parent::update($attributes);
    }

    /**
     * Prefix columns with the model table name if join clause is set.
     *
     * @return void
     */
    protected function prefixColumnsOnJoin()
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
     * @param  mixed  $table
     * @return \App\Models\Eloquent\Builder
     */
    public function orderAsc($table = null)
    {
        return $this->orderBy(
            $this->getTableNameWithDot($table) . $this->getModel()->getKeyName()
        );
    }

    /**
     * Add an "order by" primary key desc clause to the query.
     *
     * @param  string|null  $table
     * @return \App\Models\Eloquent\Builder
     */
    public function orderDesc($table = null)
    {
        return $this->orderByDesc(
            $this->getTableNameWithDot($table) . $this->getModel()->getKeyName()
        );
    }

    /**
     * Add an "order by" created at asc clause to the query.
     *
     * @param  string|null  $table
     * @return \App\Models\Eloquent\Builder
     */
    public function createdAsc($table = null)
    {
        return $this->orderBy($this->getTableNameWithDot($table) . 'created_at');
    }

    /**
     * Add an "order by" created at desc clause to the query.
     *
     * @param  string|null  $table
     * @return \App\Models\Eloquent\Builder
     */
    public function createdDesc($table = null)
    {
        return $this->orderByDesc($this->getTableNameWithDot($table) . 'created_at');
    }

    /**
     * Get the name of the table with the added dot.
     *
     * @param  string  $table
     * @return string
     */
    protected function getTableNameWithDot($table)
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

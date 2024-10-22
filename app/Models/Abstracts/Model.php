<?php

namespace App\Models\Abstracts;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Builder\Builder;
use App\Models\Traits\LanguageTrait;

abstract class Model extends BaseModel
{
    /**
     * The Eloquent query builder class to use for the model.
     *
     * @var class-string<\App\Models\Builder\Builder<*>>
     */
    protected static string $builder = Builder::class;

    /**
     * Indicates if the model has a languages.
     *
     * @var bool
     */
    protected $hasLanguage = false;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set language model if it's used into the called model.
        if (in_array(LanguageTrait::class, trait_uses_recursive($this))) {
            $this->setLanguage();

            $this->hasLanguage = true;
        }
    }

    /**
     * Determine if a model has a languages.
     *
     * @return bool
     */
    public function hasLanguage()
    {
        return $this->hasLanguage;
    }

    /**
     * Set the updatable attributes for the model.
     *
     * @param  string|null  $exclude
     * @return void
     */
    public function setFillableByUpdatable($exclude = null)
    {
        if (! ($hasUpdatable = ! empty($this->updatable)) && empty($this->notUpdatable)) {
            return;
        }

        $property = is_null($exclude) ? 'updatable' : 'updatable' . ucfirst($exclude);

        if ($hasUpdatable) {
            $fillable = array_intersect($this->fillable, (array) $this->$property);
        } else {
            $fillable = array_diff(
                $this->fillable,
                (array) $this->{'not' . ucfirst($property)}
            );
        }

        $this->fillable($fillable);
    }

    /**
     * Get all the current fillable attributes on the model.
     *
     * @param  array  $fillable
     * @return array
     */
    public function getRefillAttributes(array $fillable = [])
    {
        $fillable = array_merge($this->fillable, $fillable);

        return array_intersect_key($this->getAttributes(), array_flip($fillable));
    }

    /**
     * {@inheritDoc}
     */
    public function newEloquentBuilder($builder)
    {
        if ($this->builder instanceof Builder) {
            $builder = $this->builder;

            $this->builder = null;

            return $builder;
        }

        return new Builder($builder, $this);
    }

    /**
     * Set the Eloquent query builder instance.
     *
     * @param  \App\Models\Builder\Builder  $builder
     * @return $this
     */
    public function setEloquentBuilder(Builder $builder)
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Find a model by its query or return new static.
     *
     * @param  array  $columns
     * @return static
     */
    public function firstNew($columns = ['*'])
    {
        if (! is_null($model = $this->first($columns))) {
            return $model;
        }

        return new static;
    }

    /**
     * Execute the query and get the first result attribute.
     *
     * @param  string  $attribute
     * @param  int|null  $value
     * @param  string|null  $column
     * @return mixed
     */
    public function firstAttr($attribute, $value = null, $column = null)
    {
        $model = $this->when(! is_null($value), function ($q) use ($value, $column) {
            return $q->where($column ?: $this->getKeyName(), $value);
        })->first([$attribute]);

        return ! is_null($model) ? $model->$attribute : null;
    }

    /**
     * Execute the query and get the first result attribute or throw an exception.
     *
     * @param  string  $attribute
     * @param  int|null  $value
     * @param  string|null  $column
     * @return mixed
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
    public function create(array $attributes = [])
    {
        $model = parent::create($attributes);

        // Create a language model if it exists in this model.
        if (method_exists(get_called_class(), 'createLanguage')) {
            $model->createLanguage($attributes);
        }

        return $model;
    }

    /**
     * Update the model in the database.
     *
     * @param  array   $attributes
     * @param  array   $options
     * @param  string  $exclude
     * @return bool|int
     */
    public function update(array $attributes = [], array $options = [], $exclude = null)
    {
        $this->setFillableByUpdatable($exclude);

        return parent::update($attributes, $options);
    }

    /**
     * Delete the model from the database.
     *
     * @param  int|null  $id
     * @return bool|null
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function delete($id = null)
    {
        if (is_null($id)) {
            return parent::delete();
        }

        if (! is_null($model = $this->find($id))) {
            return $model->delete();
        }
    }
}

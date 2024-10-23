<?php

namespace App\Models\Eloquent;

use App\Models\Traits\LanguageTrait;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * The Eloquent query builder class to use for the model.
     *
     * @var class-string<\App\Models\Eloquent\Builder<*>>
     */
    protected static string $builder = Builder::class;

    /**
     * Indicates if the model has a languages.
     *
     * @var bool
     */
    protected bool $hasLanguage = false;

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
    public function hasLanguage(): bool
    {
        return $this->hasLanguage;
    }

    /**
     * Set the updatable attributes for the model.
     *
     * @param  string|null  $exclude
     * @return void
     */
    public function setFillableByUpdatable(string $exclude = null): void
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
     * Get fillable attributes on the model.
     *
     * @param  array  $fillable
     * @return array
     */
    public function getFillableAttributes(array $fillable = []): array
    {
        $fillable = array_merge($this->fillable, $fillable);

        return array_intersect_key($this->getAttributes(), array_flip($fillable));
    }

    /**
     * {@inheritDoc}
     */
    public function newEloquentBuilder($query)
    {
        if ($this->builder instanceof Builder) {
            $builder = $this->builder;

            $this->builder = null;

            return $builder;
        }

        return new static::$builder($query);
    }

    /**
     * Set the Eloquent query builder instance.
     *
     * @param  \App\Models\Eloquent\Builder  $builder
     * @return $this
     */
    public function setEloquentBuilder(Builder $builder): static
    {
        $this->builder = $builder;

        return $this;
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
     * @param  array  $attributes
     * @param  array  $options
     * @param  string|null  $exclude
     * @return bool
     */
    public function update(array $attributes = [], array $options = [], string $exclude = null): bool
    {
        $this->setFillableByUpdatable($exclude);

        return parent::update($attributes, $options);
    }
}

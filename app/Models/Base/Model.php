<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * The Eloquent query builder class to use for the model.
     *
     * @var class-string<\App\Models\Base\Builder<*>>
     */
    protected static string $builder = Builder::class;

    /**
     * Set the updatable attributes for the model.
     *
     * @param  string|null  $exclude
     * @return void
     */
    public function setFillableByUpdatable(?string $exclude = null): void
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
     * @param  \App\Models\Base\Builder  $builder
     * @return $this
     */
    public function setEloquentBuilder(Builder $builder): static
    {
        $this->builder = $builder;

        return $this;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @param  array  $options
     * @param  string|null  $exclude
     * @return bool
     */
    public function update(array $attributes = [], array $options = [], ?string $exclude = null): bool
    {
        $this->setFillableByUpdatable($exclude);

        return parent::update($attributes, $options);
    }
}

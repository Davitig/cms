<?php

namespace App\Models\Alt\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * The Eloquent query builder class used to ease scopes.
     * The instance is set when calling method from the Builder.
     * Null is set after the method call (see Builder's __call).
     *
     * @var \App\Models\Alt\Eloquent\Builder|null
     */
    protected ?Builder $tmpBuilder = null;

    /**
     * The Eloquent query builder class to use for the model.
     *
     * @var class-string<\App\Models\Alt\Eloquent\Builder<*>>
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
        if ($this->tmpBuilder instanceof Builder) {
            $builder = $this->tmpBuilder;

            $this->tmpBuilder = null;

            return $builder;
        }

        return new static::$builder($query);
    }

    /**
     * Set the Eloquent query builder instance.
     *
     * @param  \App\Models\Alt\Eloquent\Builder  $builder
     * @return $this
     */
    public function setEloquentBuilder(Builder $builder): static
    {
        $this->tmpBuilder = $builder;

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

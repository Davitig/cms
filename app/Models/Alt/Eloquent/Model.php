<?php

namespace App\Models\Alt\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * The Eloquent query builder class to use for the model.
     *
     * @var class-string<\App\Models\Alt\Eloquent\Builder<*>>
     */
    protected static string $builder = Builder::class;

    /**
     * Get the full fillable attributes for the model.
     *
     * @param  bool  $table
     * @return array<string>
     */
    public function getFullFillable(bool $table = false): array
    {
        $tablePrefix = ($table ? $this->getTable() . '.' : '');

        $fillable = array_merge([$this->getKeyName()], $this->getFillable(), $this->getDates());

        if (empty($tablePrefix)) {
            return $fillable;
        }

        return array_map(fn ($value) => $tablePrefix . $value, $fillable);
    }

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

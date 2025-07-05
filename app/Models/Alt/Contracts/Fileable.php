<?php

namespace App\Models\Alt\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Fileable
{
    /**
     * Add a where foreign key clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $foreignKey
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForeignKey(Builder $query, int $foreignKey): Builder;
}

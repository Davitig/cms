<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PositionableTrait
{
    /**
     * Find a duplicated model position.
     *
     * @param  int|null  $startPosition
     * @param  int|null  $parentId
     * @param  string|null  $foreignKey
     * @param  int|null  $foreignValue
     * @return int|null
     */
    public function findDuplicatedPosition(
        ?int    $startPosition = null,
        ?int    $parentId = null,
        ?string $foreignKey = null,
        ?int    $foreignValue = null
    ): ?int
    {
        $hasParentId = in_array('parent_id', $this->getFillable());

        $table = $this->getTable();
        $altTable = str($table)->substr(0, 1);

        return $this->when(
            $foreignKey && ! is_null($foreignValue),
            fn ($q) => $q->where($foreignKey, $foreignValue)
        )->when($hasParentId && ! is_null($parentId), fn ($q) => $q->parentId($parentId))
        ->when(! is_null($startPosition), fn ($q) => $q->wherePosition('>', $startPosition))
            ->whereNotExists(function ($q) use (
                $table, $altTable, $foreignKey, $foreignValue, $hasParentId, $parentId) {
                return $q->from($table, $altTable)
                    ->when(
                        $foreignKey && ! is_null($foreignValue),
                        fn ($q) => $q->where($foreignKey, $foreignValue)
                    )->when(
                        $hasParentId && ! is_null($parentId),
                        fn ($q) => $q->where('parent_id', $parentId)
                    )->whereRaw($altTable . '.position = ' . $table . '.position + 1');
            })->selectRaw('position + 1 as position')
            ->value('position');
    }

    /**
     * Update the position of the Eloquent models.
     *
     * @param  int  $startId
     * @param  int|null  $endId
     * @param  int|null  $parentId
     * @param  string|null  $foreignKey
     * @return int
     */
    public function positions(
        int $startId, ?int $endId = null, ?int $parentId = null, ?string $foreignKey = null
    ): int
    {
        if ((! $endId && is_null($parentId)) ||
            ($parentId && ! $this->whereKey($parentId)->exists())) {
            return 0;
        }

        $hasParentId = in_array('parent_id', $this->getFillable());

        $startItem = $this->findOrFail($startId, array_filter([
            'id', 'position', $foreignKey, $hasParentId ? 'parent_id' : null
        ]));

        if ($foreignKey && is_null($startItem->$foreignKey)) {
            return 0;
        }

        if (is_null($parentId)) {
            $startItemPosition = $startItem->position;
        } else {
            $startItemPosition = $this->when($hasParentId, fn ($q) => $q->parentId($parentId))
                    ->max('position') + 1;

            $items = $this->when($foreignKey, function ($q, $value) use ($startItem) {
                return $q->where($value, $startItem->$value);
            })->wherePosition('>', $startItem->position)
                ->when($hasParentId, fn ($q) => $q->parentId($startItem->parent_id))
                ->get(['id', 'position']);

            foreach ($items as $item) {
                $item->update(['position' => $item->position - 1]);
            }
        }

        if (! $endId && ! is_null($parentId)) {
            return (int) $startItem->update([
                'position' => $startItemPosition, 'parent_id' => $parentId
            ]);
        }

        $endItem = $this->findOrFail($endId, array_filter([
            'id', 'position', $hasParentId ? 'parent_id' : null
        ]));

        if (is_null($parentId) && $startItem->position == $endItem->position) {
            return 0;
        }

        $count = 0;

        $ascending = $startItemPosition > $endItem->position;

        if (is_null($parentId)) {
            $positions = [$startItemPosition, $endItem->position];
            sort($positions);

            $items = $this->when($foreignKey, function ($q, $value) use ($startItem) {
                return $q->where($value, $startItem->$value);
            })->wherePosition('>', $positions[0])
                ->wherePosition('<', $positions[1])
                ->when(
                    $hasParentId && ! is_null($startItem->parent_id),
                    fn ($q) => $q->parentId($startItem->parent_id)
                )->get(['id', 'position']);

            foreach ($items as $item) {
                $count += (int) $item->update([
                    'position' => $ascending ? $item->position + 1 : $item->position - 1
                ]);
            }
        } else {
            $items = $this->when($foreignKey, function ($q, $value) use ($startItem) {
                return $q->where($value, $startItem->$value);
            })->wherePosition('>', $endItem->position)
                ->when($hasParentId, fn ($q) => $q->parentId($endItem->parent_id))
                ->get(['id', 'position']);

            foreach ($items as $item) {
                $item->update(['position' => $item->position + 1]);
            }
        }

        $parentData = ! is_null($parentId) ? ['parent_id' => $parentId] : [];

        $count += (int) $startItem->update(['position' => $endItem->position] + $parentData);

        $count += (int) $endItem->update([
            'position' => $ascending ? $endItem->position + 1 : $endItem->position - 1
        ]);

        return $count;
    }

    /**
     * Add a where "position" clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed|null  $operator
     * @param  int|null  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWherePosition(Builder $query, mixed $operator = null, ?int $value = null): Builder
    {
        return $query->where($this->qualifyColumn('position'), $operator, $value);
    }

    /**
     * Add an "order by" position asc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $qualify
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositionAsc(Builder $query, bool $qualify = false): Builder
    {
        return $query->orderBy(
            $qualify ? $this->qualifyColumn('position') : 'position',
        );
    }

    /**
     * Add an "order by" position desc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  bool  $qualify
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositionDesc(Builder $query, bool $qualify = false): Builder
    {
        return $query->orderByDesc(
            $qualify ? $this->qualifyColumn('position') : 'position',
        );
    }

    /**
     * Save a new model with increment position and return the instance.
     *
     * @param  array  $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            $attributes['position'] = $this->max('position') + 1;
        }

        return parent::create($attributes);
    }
}

<?php

namespace App\Models\Alt\Traits;

use Illuminate\Database\Eloquent\Builder;

trait PositionableTrait
{
    /**
     * Update the position of the Eloquent models.
     *
     * @param  array  $data
     * @param  string  $orderBy
     * @param  int|null  $parentId
     * @param  string|null  $move
     * @return int
     */
    public function positions(
        array $data, string $orderBy = 'asc', ?int $parentId = null, ?string $move = null
    ): int
    {
        $isMoveAction = false;

        if ($move == 'prev' || $move == 'next') {
            $data = array_values($this->moveTargetPosition($data, $orderBy, $move));

            $isMoveAction = true;
        }

        $attributes = $positions = [];

        $count = $position = 0;

        foreach($data as $item) {
            if (isset($item['pos'])) {
                $positions[] = $item['pos'];
            } else {
                $positions[] = $position++;
            }
        }

        if (! $isMoveAction) {
            if ($orderBy === 'desc') {
                rsort($positions);
            } else {
                sort($positions);
            }
        }

        foreach($positions as $key => $position) {
            if (! is_null($parentId)) {
                $attributes['parent_id'] = $parentId;
            }

            $attributes['position'] = $position;

            $id = $data[$key]['id'];

            $count += (int) $this->whereKey($id)->update($attributes);

            if (isset($data[$key]['children'])) {
                $count += $this->positions($data[$key]['children'], $orderBy, $id, $move);
            }
        }

        return $count;
    }

    /**
     * Move the position of the Eloquent model by specified order and direction.
     *
     * @param  array  $data
     * @param  string  $orderBy
     * @param  string  $move
     * @return array
     */
    private function moveTargetPosition(array $data, string $orderBy, string $move): array
    {
        if (empty($data = array_filter(
            $data, fn ($value) => ! empty($value['id']) && ! empty($value['pos'])))) {
            return $data;
        }

        $target = array_shift($data);

        if (! empty($data)) {
            $startPos = last($data)['pos'];

            if ($move == 'next') {
                if ($orderBy == 'desc') {
                    $target['pos'] = $startPos - 1; $queryOperator = '<';
                    $queryOrderBy = 'desc'; $posFunc = fn (&$value) => $value['pos']++;
                } else {
                    $target['pos'] = $startPos + 1; $queryOperator = '>';
                    $queryOrderBy = 'asc'; $posFunc = fn (&$value) => $value['pos']--;
                }
            } else {
                if ($orderBy == 'desc') {
                    $target['pos'] = $startPos + 1; $queryOperator = '>';
                    $queryOrderBy = 'asc'; $posFunc = fn (&$value) => $value['pos']--;
                } else {
                    $target['pos'] = $startPos - 1; $queryOperator = '<';
                    $queryOrderBy = 'desc'; $posFunc = fn (&$value) => $value['pos']++;
                }
            }

            array_walk($data, $posFunc);
        } else {
            $startPos = $target['pos'];

            if ($move == 'next') {
                if ($orderBy == 'desc') {
                    $target['pos'] = $startPos - 1; $queryOperator = '<'; $queryOrderBy = 'desc';
                } else {
                    $target['pos'] = $startPos + 1; $queryOperator = '>'; $queryOrderBy = 'asc';
                }
            } else {
                if ($orderBy == 'desc') {
                    $target['pos'] = $startPos + 1; $queryOperator = '>'; $queryOrderBy = 'asc';
                } else {
                    $target['pos'] = $startPos - 1; $queryOperator = '<'; $queryOrderBy = 'desc';
                }
            }
        }

        if (is_null($newData = $this->where('position', $queryOperator, $startPos)
            ->orderBy('position', $queryOrderBy)
            ->first(['id']))) {
            return $data;
        }

        $dataCount = count($data);

        $data[$dataCount]['id'] = $newData['id'];
        $data[$dataCount]['pos'] = $startPos;
        $data['target']['id'] = $target['id'];
        $data['target']['pos'] = $target['pos'];

        return $data;
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

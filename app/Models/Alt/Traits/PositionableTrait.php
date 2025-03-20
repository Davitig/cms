<?php

namespace App\Models\Alt\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait PositionableTrait
{
    /**
     * Update the position of the Eloquent models.
     *
     * @param  array  $data
     * @param  int  $parentId
     * @param  array  $params
     * @param  bool  $hasSubItems
     * @return bool
     */
    public function updatePosition(
        array $data, int $parentId = 0, array $params = [], bool $hasSubItems = false
    ): bool
    {
        if (empty($data)
            || ! $hasSubItems && ! is_array($data = $this->movePosition($data, $params))) {
            return false;
        }

        $attributes = [];

        $position = 0;

        foreach($data as $item) {
            if (! isset($item['id'])) {
                continue;
            }

            $position++;

            if ($hasSubItems) {
                $attributes['parent_id'] = $parentId;
            }

            if (isset($item['pos'])) {
                $position = $item['pos'];
            }

            $attributes['position'] = $position;

            $this->whereKey($item['id'])->update($attributes);

            if (isset($item['children'])) {
                $this->updatePosition($item['children'], $item['id'], $params, $hasSubItems);
            }
        }

        return true;
    }

    /**
     * Update the position of the Eloquent models by specified order and direction.
     *
     * @param  array  $data
     * @param  array  $params
     * @return array|bool
     */
    private function movePosition(array $data, array $params = []): bool|array
    {
        if (! isset($params['move']) || ! isset($params['orderBy'])) {
            return $data;
        }

        if (empty($data = array_filter(
            $data, fn ($value) => ! empty($value['pos']) && ! empty($value['id'])
        ))) {
            return false;
        }

        $target = array_shift($data);

        if (! empty($data)) {
            $startPos = last($data)['pos'];

            if ($params['move'] == 'next') {
                if ($params['orderBy'] == 'desc') {
                    $target['pos'] = $startPos - 1; $queryOperator = '<';
                    $queryOrderBy = 'desc'; $posFunc = fn (&$value) => $value['pos']++;
                } else {
                    $target['pos'] = $startPos + 1; $queryOperator = '>';
                    $queryOrderBy = 'asc'; $posFunc = fn (&$value) => $value['pos']--;
                }
            } else {
                if ($params['orderBy'] == 'desc') {
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

            if ($params['move'] == 'next') {
                if ($params['orderBy'] == 'desc') {
                    $target['pos'] = $startPos - 1; $queryOperator = '<'; $queryOrderBy = 'desc';
                } else {
                    $target['pos'] = $startPos + 1; $queryOperator = '>'; $queryOrderBy = 'asc';
                }
            } else {
                if ($params['orderBy'] == 'desc') {
                    $target['pos'] = $startPos + 1; $queryOperator = '>'; $queryOrderBy = 'asc';
                } else {
                    $target['pos'] = $startPos - 1; $queryOperator = '<'; $queryOrderBy = 'desc';
                }
            }
        }

        if (is_null($newData = $this->where('position', $queryOperator, $startPos)
            ->orderBy('position', $queryOrderBy)
            ->first(['id']))) {
            return false;
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
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositionAsc(Builder $query): Builder
    {
        return $query->orderBy('position');
    }

    /**
     * Add an "order by" position desc clause to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePositionDesc(Builder $query): Builder
    {
        return $query->orderByDesc('position');
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

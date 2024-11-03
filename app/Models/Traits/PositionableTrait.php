<?php

namespace App\Models\Traits;

use App\Models\Base\Builder;
use Exception;

trait PositionableTrait
{
    /**
     * Update the position of the Eloquent models.
     *
     * @param  array  $data
     * @param  int  $parentId
     * @param  array  $params
     * @param  bool  $nestable
     * @return bool
     */
    public function updatePosition(
        array $data,
        int   $parentId = 0,
        array $params = [],
        bool  $nestable = false
    ): bool
    {
        if (! $nestable && ! is_array($data = $this->movePosition($data, $params))) {
            return false;
        }

        $attributes = [];

        $position = 0;

        foreach($data as $item) {
            if (! isset($item['id'])) {
                return false;
            }

            $position++;

            if ($nestable) {
                $attributes['parent_id'] = $parentId;
            } elseif (isset($item['pos'])) {
                $position = $item['pos'];
            }

            $attributes['position'] = $position;

            $this->whereKey($item['id'])->update($attributes);

            if (isset($item['children'])) {
                $this->updatePosition($item['children'], $item['id'], $params, $nestable);
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

        if ($params['move'] == 'next') {
            if ($params['orderBy'] == 'desc') {
                $posFunc = function (&$value) {
                    return $value['pos']++;
                };
            } else {
                $posFunc = function (&$value) {
                    return $value['pos']--;
                };
            }

            $newPos = end($data)['pos'] - 1;
        } else {
            if ($params['orderBy'] == 'asc') {
                $posFunc = function (&$value) {
                    return $value['pos']++;
                };
            } else {
                $posFunc = function (&$value) {
                    return $value['pos']--;
                };
            }

            $newPos = end($data)['pos'] + 1;
        }

        try {
            array_walk($data, $posFunc);
        } catch (Exception) {
            return false;
        }

        $newData = $this->where(['position' => $newPos])->first(['id']);

        if (! $newData) {
            return false;
        }

        $dataCount = count($data);

        $data[0]['pos'] = $newPos;
        $data[$dataCount]['pos'] = $params['move'] == 'next' ? $newPos + 1 : $newPos - 1;
        $data[$dataCount]['id'] = $newData['id'];

        return $data;
    }

    /**
     * Add an "order by" position asc clause to the query.
     *
     * @return \App\Models\Base\Builder
     */
    public function positionAsc(): Builder
    {
        return $this->orderBy('position');
    }

    /**
     * Add an "order by" position desc clause to the query.
     *
     * @return \App\Models\Base\Builder
     */
    public function positionDesc(): Builder
    {
        return $this->orderByDesc('position');
    }

    /**
     * {@inheritDoc}
     */
    public function create(array $attributes = [])
    {
        if (empty($attributes['position'])) {
            $attributes['position'] = $this->max('position') + 1;
        }

        return parent::create($attributes);
    }
}

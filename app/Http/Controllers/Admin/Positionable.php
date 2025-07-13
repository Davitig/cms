<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alt\Traits\PositionableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;

trait Positionable
{
    /**
     * Update a model positions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function positions(Request $request)
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new ModelNotFoundException;
        } elseif (! in_array(PositionableTrait::class, trait_uses_recursive($this->model))) {
            throw new RuntimeException('Position trait not found');
        }

        $startId = (int) $request->get('start_id');

        $foreignKey = $request->get('foreign_key');

        if ($request->boolean('resolve_duplicated')) {
            return $this->positionResponse(
                $request, $this->resolveDuplicatedPosition($startId, $foreignKey)
            );
        }

        if (! is_null($endId = $request->get('end_id'))) {
            $endId = (int) $endId;
        }

        if (! is_null($parentId = $request->get('parent_id'))) {
            $parentId = (int) $parentId;
        }

        $move = $request->get('move');
        $direction = $request->get('order_by');

        if ($endId && $move && $direction) {
            $endId = $this->getNextPositionItemId($endId, $move, $direction, $foreignKey);
        }

        $result = $this->model->positions($startId, $endId, $parentId, $foreignKey);

        return $this->positionResponse($request, $result);
    }

    /**
     * Get the position response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $result
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function positionResponse(Request $request, int $result)
    {
        $data = fill_data(
            (bool) $result,
            trans('database.' . ($result ? 'updated' : 'no_changes')), $result
        );

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return back()->with('alert', $data);
    }

    /**
     * Get a next position model ID.
     *
     * @param  int  $id
     * @param  string  $move
     * @param  string  $direction
     * @param  string|null  $foreignKey
     * @return int|null
     */
    protected function getNextPositionItemId(
        int $id, string $move, string $direction, ?string $foreignKey = null
    ): ?int
    {
        if ($foreignKey && ! in_array($foreignKey, $this->model->getFillable())) {
            throw new InvalidArgumentException('Invalid foreign key provided.');
        }

        $item = $this->model->find($id, array_filter(['id', 'position', $foreignKey]));

        if (is_null($item) || $foreignKey && is_null($item->$foreignKey)) {
            return null;
        }

        if ($move == 'next') {
            if ($direction == 'desc') {
                $operator = '<';
            } else {
                $operator = '>';
                $direction = 'asc';
            }
        } else {
            if ($direction == 'asc') {
                $operator = '<';
                $direction = 'desc';
            } else {
                $operator = '>';
                $direction = 'asc';
            }
        }

        return $this->model->when(
            $foreignKey && ! is_null($item->$foreignKey),
            fn ($q) => $q->where($foreignKey, $item->$foreignKey)
        )->where('position', $operator, $item->position)
            ->orderBy('position', $direction)
            ->value('id');
    }

    /**
     * Resolve the duplicated model position.
     *
     * @param  int  $id
     * @param  string|null  $foreignKey
     * @return int
     */
    protected function resolveDuplicatedPosition(int $id, ?string $foreignKey = null): int
    {
        $hasParentId = in_array('parent_id', $this->model->getFillable());

        $item = $this->model->findOrFail($id, array_filter([
            'id', 'position', $foreignKey, $hasParentId ? 'parent_id' : null
        ]));

        if ($foreignKey && is_null($item->$foreignKey) ||
            ! $this->model->whereKeyNot($id)->wherePosition($item->position)->exists()) {
            return 0;
        }

        $position = $this->model->findDuplicatedPosition(
            $item->position - 1,
            $hasParentId ? $item->parent_id : null,
            $foreignKey,
            $foreignKey ? $item->$foreignKey : null
        );

        if (is_null($position)) {
            return 0;
        }

        return (int) $item->update(['position' => $position]);
    }

    /**
     * Delete the model and update submodels.
     *
     * @param  int|string  $id
     * @return bool|null
     */
    protected function deleteAndUpdateSubItems(int|string $id): ?bool
    {
        $model = $this->model->findOrFail($id);

        $deleted = $model->delete();

        if (is_null($model->parent_id)) {
            return $deleted;
        }

        $parentId = $model->parent_id ? (int) $this->model->find($model->parent_id)?->id : 0;

        $maxParentPosition = $model->parentId($parentId)->max('position') + 1;

        $this->model->parentId($id)->update([
            'position' => $maxParentPosition, 'parent_id' => $parentId
        ]);

        return $deleted;
    }
}

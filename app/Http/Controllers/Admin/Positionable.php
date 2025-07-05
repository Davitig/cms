<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait Positionable
{
    /**
     * Update model positions.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function positions()
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new ModelNotFoundException;
        }

        $data = (array) request('data');

        $orderBy = (string) request('order_by');
        $move = (string) request('move');

        $hasSubItems = in_array('parent_id', $this->model->getFillable());

        $result = $this->model->positions($data, $orderBy, $hasSubItems ? 0 : null, $move);

        $data = fill_data(
            (bool) $result, trans('database.' . ($result ? 'updated' : 'no_changes')), $result
        );

        if (request()->expectsJson()) {
            return response()->json($data);
        }

        return back()->with('alert', $data);
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

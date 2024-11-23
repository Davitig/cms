<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alt\Base\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait Positionable
{
    /**
     * Update model position.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updatePosition()
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new ModelNotFoundException;
        }

        $data = request('data');

        $params = request()->except('data');

        $nestable = in_array('parent_id', $this->model->getFillable());

        $result = $this->model->updatePosition($data, 0, $params, $nestable);

        if (request()->expectsJson()) {
            return response()->json($result);
        }

        return back();
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

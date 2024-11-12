<?php

namespace App\Http\Controllers\Admin;

use App\Models\Base\Model;
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
}

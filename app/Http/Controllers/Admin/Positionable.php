<?php

namespace App\Http\Controllers\Admin;

use App\Models\Base\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

trait Positionable
{
    /**
     * Update model position.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function updatePosition()
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new ModelNotFoundException;
        }

        if (isset($this->request) && $this->request instanceof Request) {
            $request = $this->request;
        } else {
            $request = app(Request::class);
        }

        $data = $request->get('data');

        $params = $request->except('data');

        $nestable = in_array('parent_id', $this->model->getFillable());

        $result = $this->model->updatePosition($data, 0, $params, $nestable);

        if ($request->expectsJson()) {
            return response()->json($result);
        }

        return back();
    }
}

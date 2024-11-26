<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alt\Eloquent\Model;
use ErrorException;
use Illuminate\Http\Request;

trait VisibilityTrait
{
    /**
     * Update visibility of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \ErrorException
     */
    public function visibility(Request $request, string $id)
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new ErrorException('Model not found');
        }

        $model = $this->model->findOrFail($id);

        $model->update(['visible' => $visible = (int) ! $model->visible]);

        if ($request->expectsJson()) {
            return response()->json($visible);
        }

        return back();
    }
}

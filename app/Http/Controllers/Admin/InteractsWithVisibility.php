<?php

namespace App\Http\Controllers\Admin;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

trait InteractsWithVisibility
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

        $updated = $model->update(['visible' => $visible = (int) ! $model->visible]);

        $data = fill_data(
            $updated, trans('database.' . ($updated ? 'updated' : 'no_changes')), $visible
        );

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        return back()->with('alert', $data);
    }
}

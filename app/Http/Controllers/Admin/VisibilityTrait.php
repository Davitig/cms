<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alt\Base\Model;
use Illuminate\Http\Request;
use RuntimeException;

trait VisibilityTrait
{
    /**
     * Update visibility of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \RuntimeException
     */
    public function visibility(Request $request, string $id)
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new RuntimeException('Model not found');
        }

        $model = $this->model->findOrFail($id);

        $model->update(['visible' => $visible = (int) ! $model->visible]);

        if ($request->expectsJson()) {
            return response()->json($visible);
        }

        return back();
    }
}

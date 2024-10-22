<?php

namespace App\Http\Controllers\Admin;

use App\Models\Eloquent\Model;
use Illuminate\Http\Request;
use RuntimeException;

trait VisibilityTrait
{
    /**
     * Update visibility of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \RuntimeException
     */
    public function visibility(Request $request, $id)
    {
        if (! isset($this->model) || ! $this->model instanceof Model) {
            throw new RuntimeException('Model not found');
        }

        $model = $this->model->when($this->model->hasLanguage(), function ($q) {
            return $q->joinLanguage();
        })->findOrFail($id);

        $model->update(['visible' => $visible = (int) ! $model->visible]);

        if ($request->expectsJson()) {
            return response()->json($visible);
        }

        return redirect()->back();
    }
}

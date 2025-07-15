<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

trait Transferable
{
    /**
     * Transfer model by changing the specified foreign key value.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function transfer(Request $request, string $id)
    {
        $input = $request->all(['id', 'column', 'column_value', 'recursive']);

        if (! $this->model instanceof Model) {
            return $this->getTransferResponse($request, false, 'Model not found', 404);
        }

        if ($id == $input['column_value']) {
            return $this->getTransferResponse($request, false, trans('database.no_changes'));
        }

        $model = $this->model->findOrFail($input['id'], ['id']);

        $position = $this->model->where($input['column'], $input['column_value'])->max('position');

        $attributes = [$input['column'] => $input['column_value'], 'position' => $position + 1];

        if ($recursive = ! empty($input['recursive'])) {
            $attributes['parent_id'] = 0;
        }

        try {
            $model->update($attributes);
        } catch (QueryException $e) {
            $errorCode = (string) ($e->errorInfo[1] ?? null);

            return $this->getTransferResponse(
                $request,
                (int) ! $errorCode,
                trans('database.' . ($errorCode ? 'error.' . $errorCode : 'deleted')),
                422
            );
        }

        if ($recursive) {
            $this->transferRecursively($model, $input['column'], $input['column_value']);
        }

        return $this->getTransferResponse($request, true, trans('database.updated'));
    }

    /**
     * Transfer model recursively with its child item(s).
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $column
     * @param  string  $columnValue
     * @return void
     */
    protected function transferRecursively(Model $model, string $column, string $columnValue): void
    {
        $items = $this->model->where('parent_id', $model->id)->get(['id']);

        if (! $items->isEmpty()) {
            foreach ($items as $item) {
                $item->update([$column => $columnValue]);

                $this->transferRecursively($item, $column, $columnValue);
            }
        }
    }

    /**
     * Get the transfer response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $result
     * @param  string|null  $message
     * @param  int  $status
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function getTransferResponse(
        Request $request, bool $result, ?string $message = null, int $status = 200
    ): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(fill_data($result, $message), $status);
        }

        return back()->with('alert', fill_data($result, $message));
    }
}

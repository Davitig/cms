<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\Positionable;
use App\Http\Controllers\Admin\VisibilityTrait;
use App\Models\Alt\Contracts\Fileable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdminFileController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * Foreign key name.
     *
     * @var string
     */
    protected string $foreignKey;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Fileable $model, protected Request $request)
    {
        $this->foreignKey = str($this->model->getTable())->beforeLast('_')->singular()
            . '_' . $this->model->getKeyName();
    }

    /**
     * Get a listing of the resource.
     *
     * @param  string  $foreignId
     * @param  \Illuminate\Database\Eloquent\Model  $foreignModel
     * @return array
     */
    public function indexData(string $foreignId, Model $foreignModel)
    {
        $data['foreignModels'] = $foreignModel->whereKey($foreignId)
            ->joinLanguage(false)
            ->getOrFail();

        $data['foreignModel'] = $data['foreignModels']->first();

        $data['items'] = $this->model->forAdmin($foreignId)->paginate(24);

        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $foreignId
     * @param  string  $viewPath
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function createData(string $foreignId, string $viewPath, string $redirectUrl)
    {
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;
            $data['current']->{$this->foreignKey} = $foreignId;

            return response()->json([
                'result' => true,
                'view' => str(view($viewPath, $data)->render())->squish()
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Foundation\Http\FormRequest  $request
     * @param  string  $foreignId
     * @param  string  $viewPath
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function storeData(FormRequest $request, string $foreignId, string $viewPath, string $redirectUrl)
    {
        $input = $request->all();
        $input[$this->foreignKey] = $foreignId;

        $model = $this->model->create($input);

        $model->languages()->createMany(apply_languages($input));

        if ($request->expectsJson()) {
            return response()->json(
                fill_data(true, trans('general.created'))
                + ['view' => str(
                    view($viewPath, [
                        'item' => $model, 'itemInput' => $input,
                        'currentPage' => $request->get('current_page'),
                        'lastPage' => $request->get('last_page')
                    ])->render()
                )->squish()]
            );
        }

        return redirect($redirectUrl);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $foreignId
     * @param  string  $id
     * @param  string  $viewPath
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function editData(string $foreignId, string $id, string $viewPath, string $redirectUrl)
    {
        if ($this->request->expectsJson()) {
            $data['items'] = $this->model->joinLanguage(false)
                ->whereKey($id)
                ->get();

            return response()->json([
                'result' => true,
                'view' => str(view($viewPath, $data)->render())->squish()
            ]);
        }

        return redirect($redirectUrl);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Foundation\Http\FormRequest  $request
     * @param  string  $foreignId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateData(FormRequest $request, string $foreignId, string $id)
    {
        tap($this->model->findOrFail($id))
            ->update($input = $request->all())
            ->languages()
            ->updateOrCreate(apply_languages(), $input);

        if ($request->expectsJson()) {
            return response()->json(fill_data(true, trans('general.updated'), $input));
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $foreignId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroyData(string $foreignId, string $id)
    {
        $result = $this->model->findOrFail($id)->delete();

        if (request()->expectsJson()) {
            return response()->json(fill_data($result, trans('database.deleted')));
        }

        return back()->with('alert', fill_data($result, trans('database.deleted')));
    }

    /**
     * Remove the specified resources from storage.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroyMany(string $foreignId)
    {
        $deleted = $this->model->destroy($this->request->get('ids'));

        $data = fill_data(
            (bool) $deleted,
            trans('database.' . ($deleted ? 'deleted' : 'no_changes')),
            $deleted
        );

        if (request()->expectsJson()) {
            return response()->json($data);
        }

        return back()->with('alert', $data);
    }
}

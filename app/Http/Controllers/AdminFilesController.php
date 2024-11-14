<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\LanguageRelationsTrait;
use App\Http\Controllers\Admin\Positionable;
use App\Http\Controllers\Admin\VisibilityTrait;
use App\Models\Alt\Base\Model;
use App\Models\Alt\Contracts\Fileable;
use App\Models\Alt\Traits\HasGallery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AdminFilesController extends Controller
{
    use Positionable, VisibilityTrait, LanguageRelationsTrait;

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
        $this->foreignKey = in_array(HasGallery::class, class_uses($model))
            ? (string) $model->getGalleryKeyName()
            : str($this->model->getTable())->before('_')->singular()
            . '_' . $this->model->getKeyName();
    }

    /**
     * Get a listing of the resource.
     *
     * @param  string  $foreignId
     * @param  \App\Models\Alt\Base\Model  $foreignModel
     * @return array
     */
    public function indexData(string $foreignId, Model $foreignModel)
    {
        $data['foreignModels'] = $foreignModel->where('id', $foreignId)
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
                'view' => view($viewPath, $data)->render()
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

        $this->createLanguageRelations('languages', $input, $model->id, true);

        if ($request->expectsJson()) {
            $view = view($viewPath, ['item' => $model, 'itemInput' => $input])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
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
                ->where('id', $id)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view($viewPath, $data)->render()
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
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function updateData(FormRequest $request, string $foreignId, string $id, string $redirectUrl)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect($redirectUrl);
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
        $this->model->destroy($this->request->get('ids', $id));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data('success', trans('database.deleted')));
    }
}

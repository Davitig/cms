<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhotoRequest;
use App\Models\Gallery\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;

class AdminPhotosController extends Controller
{
    use Positionable, VisibilityTrait, LanguageRelationsTrait;

    /**
     * Create a new controller instance.
     */
    public function __construct(protected Photo $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  string  $galleryId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $galleryId)
    {
        $data['parent'] = (new Gallery)->byType($this->model::TYPE)
            ->joinLanguage()
            ->findOrFail($galleryId);

        $data['items'] = $this->model->getAdminGallery($data['parent']);

        $data['parentSimilar'] = (new Gallery)->byType($this->model::TYPE)
            ->joinLanguage()
            ->get();

        return view('admin.galleries.photos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $galleryId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(string $galleryId)
    {
        if ($this->request->expectsJson()) {
            $data['current'] = $this->model;
            $data['current']['gallery_id'] = $galleryId;

            return response()->json([
                'result' => true,
                'view' => view('admin.galleries.photos.create', $data)->render()
            ]);
        }

        return redirect(cms_route('photos.index', [$galleryId]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PhotoRequest  $request
     * @param  string  $galleryId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(PhotoRequest $request, string $galleryId)
    {
        $input = $request->all();
        $input['gallery_id'] = $galleryId;

        $model = $this->model->create($input);

        $this->createLanguageRelations('languages', $input, $model->id, true);

        if ($request->expectsJson()) {
            $view = view('admin.galleries.photos.item', [
                'item' => $model,
                'itemInput' => $input
            ])->render();

            return response()->json(
                fill_data('success', trans('general.created'))
                + ['view' => preg_replace('/\s+/', ' ', trim($view))]
            );
        }

        return redirect(cms_route('photos.index', [$galleryId]));
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $galleryId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $galleryId, string $id)
    {
        if ($this->request->expectsJson()) {
            $model = $this->model;

            $data['items'] = $model->joinLanguage(false)->where('id', $id)
                ->getOrFail();

            return response()->json([
                'result' => true,
                'view' => view('admin.galleries.photos.edit', $data)->render()
            ]);
        }

        return redirect(cms_route('photos.index', [$galleryId]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\PhotoRequest  $request
     * @param  string  $galleryId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(PhotoRequest $request, string $galleryId, string $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

        $this->updateOrCreateLanguageRelations('languages', $input, $id);

        if ($request->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('general.updated'), $input
            ));
        }

        return redirect(cms_route('photos.index', [$galleryId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $galleryId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $galleryId, string $id)
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PhotoRequest;
use App\Models\Collection;
use App\Models\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;

class AdminPhotosController extends Controller
{
    use Positionable, VisibilityTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Photo $model, protected Request $request) {}

    /**
     * Display a listing of the resource.
     *
     * @param  int  $galleryId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(int $galleryId)
    {
        $data['parent'] = (new Gallery)->where('type', Photo::TYPE)
            ->joinLanguage()
            ->findOrFail($galleryId);

        $data['items'] = $this->model->getAdminGallery($data['parent']);

        $data['parentSimilar'] = (new Collection)->byType($this->model::TYPE)->get();

        return view('admin.galleries.photos.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $galleryId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function create(int $galleryId)
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
     * @param  int  $galleryId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(PhotoRequest $request, int $galleryId)
    {
        $input = $request->all();
        $input['gallery_id'] = $galleryId;

        $model = $this->model->create($input);

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
     * @param  int  $galleryId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function edit(int $galleryId, int $id)
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
     * @param  int  $galleryId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(PhotoRequest $request, int $galleryId, int $id)
    {
        $this->model->findOrFail($id)->update($input = $request->all());

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
     * @param  int  $galleryId
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(int $galleryId, int $id)
    {
        $this->model->destroy($this->request->get('ids'));

        if (request()->expectsJson()) {
            return response()->json(fill_data(
                'success', trans('database.deleted')
            ));
        }

        return back()->with('alert', fill_data(
            'success', trans('database.deleted')
        ));
    }
}

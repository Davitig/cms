<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFilesController as Controller;
use App\Http\Requests\Admin\PhotoRequest;
use App\Models\Gallery\Gallery;
use App\Models\Photo;
use Illuminate\Http\Request;

class AdminPhotosController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(Photo $model, Request $request)
    {
        parent::__construct($model, $request);
    }

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
        return $this->createData(
            $galleryId,
            'admin.galleries.photos.create',
            cms_route('photos.index', [$galleryId])
        );
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
        return $this->storeData(
            $request,
            $galleryId,
            'admin.galleries.photos.item',
            cms_route('photos.index', [$galleryId])
        );
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
        return $this->editData(
            $galleryId,
            $id,
            'admin.galleries.photos.edit',
            cms_route('photos.index', [$galleryId])
        );
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
        return $this->updateData(
            $request, $galleryId, $id, cms_route('photos.index', [$galleryId])
        );
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
        return $this->destroyData($galleryId, $id);
    }
}

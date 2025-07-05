<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFileController as Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Page\Page;
use App\Models\Page\PageFile;
use Illuminate\Http\Request;

class AdminPageFileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(PageFile $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $pageId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $pageId)
    {
        $data = $this->indexData($pageId, new Page);

        // make a menu list active from this (files) resource by passing the
        // current page menu_id
        view()->composer('admin.-partials.menu', function ($view) use ($data) {
            return $view->with('activeMenuId', $data['foreignModel']->menu_id);
        });

        return view('admin.pages.files.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $pageId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(string $pageId)
    {
        return $this->createData(
            $pageId,
            'admin.pages.files.create',
            cms_route('pages.files.index', [$pageId])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $pageId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, string $pageId)
    {
        return $this->storeData(
            $request,
            $pageId,
            'admin.pages.files.item',
            cms_route('pages.files.index', [$pageId])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $pageId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $pageId, string $id)
    {
        return $this->editData(
            $pageId,
            $id,
            'admin.pages.files.edit',
            cms_route('pages.files.index', [$pageId])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $pageId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, string $pageId, string $id)
    {
        return $this->updateData($request, $pageId, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $pageId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $pageId, string $id)
    {
        return $this->destroyData($pageId, $id);
    }
}

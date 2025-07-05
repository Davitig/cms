<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminFileController as Controller;
use App\Http\Requests\Admin\FileRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductFile;
use Illuminate\Http\Request;

class AdminProductFileController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(ProductFile $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  string  $productId
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $productId)
    {
        return view('admin.products.files.index', $this->indexData(
            $productId, new Product
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  string  $productId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function create(string $productId)
    {
        return $this->createData(
            $productId,
            'admin.products.files.create',
            cms_route('products.files.index', [$productId])
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $productId
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function store(FileRequest $request, string $productId)
    {
        return $this->storeData(
            $request,
            $productId,
            'admin.products.files.item',
            cms_route('products.files.index', [$productId])
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $productId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     * @throws \Throwable
     */
    public function edit(string $productId, string $id)
    {
        return $this->editData(
            $productId,
            $id,
            'admin.products.files.edit',
            cms_route('products.files.index', [$productId])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Admin\FileRequest  $request
     * @param  string  $productId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(FileRequest $request, string $productId, string $id)
    {
        return $this->updateData($request, $productId, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $productId
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(string $productId, string $id)
    {
        return $this->destroyData($productId, $id);
    }
}

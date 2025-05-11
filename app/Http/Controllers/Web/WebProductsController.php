<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductFile;

class WebProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Product\Product  $model
     */
    public function __construct(protected Product $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages)
    {
        $data['current'] = end($pages);

        $data['items'] = $this->model->forPublic()->paginate(10);

        return view('web.products', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @param  string  $slug
     * @return \Illuminate\Contracts\View\View
     */
    public function show(array $pages, string $slug)
    {
        $data['parent'] = array($pages);

        $data['current'] = $this->model->bySlug($slug)->firstOrFail();

        $data['files'] = (new ProductFile)->getFiles($data['current']->id);

        return view('web.product', $data);
    }
}

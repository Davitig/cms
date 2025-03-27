<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Faq;

class WebFaqController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \App\Models\Faq  $model
     */
    public function __construct(protected Faq $model) {}

    /**
     * Display a listing of the resource.
     *
     * @param  array<\App\Models\Page\Page>  $pages
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Contracts\View\View
     */
    public function index(array $pages, Collection $collection)
    {
        $data['current'] = end($pages);

        $data['items'] = $this->model->getPublicCollection($collection);

        return view('web.faq', $data);
    }

    /**
     * The specified resource is not available.
     *
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show()
    {
        abort(404);
    }
}

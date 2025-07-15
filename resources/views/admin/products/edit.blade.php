@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center row-gap-4 gap-4 mb-6">
        <div class="fs-4 flex-grow-1 text-black">Edit Product</div>
        <a href="{{ cms_route('products.create') }}">
            <i class="icon-base fa fa-plus icon-xs"></i>
            <span>Add New Record</span>
        </a>
        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            Go to
        </button>
        <ul class="dropdown-menu">
            <li>
                <a href="{{ cms_route('products.files.index', [$current->id]) }}" class="dropdown-item">Files</a>
            </li>
        </ul>
    </div>
    @include('admin.-partials.lang.tabs')
    <div class="tab-content p-0">
        @php
            $activeLang = language()->queryStringOrActive();
            $hasManyLanguage = language()->count() > 1;
        @endphp
        @foreach($items as $current)
            <div id="item-{{ $current->language }}" class="tab-pane{{ $current->language == $activeLang || ! $activeLang ? ' show active' : '' }}">
                {{ html()->modelForm($current, 'put', cms_route('products.update', [$current->id], $hasManyLanguage ? $current->language : null))
                ->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                @include('admin.products.form')
                {{ html()->form()->close() }}
            </div>
        @endforeach
    </div>
@endsection

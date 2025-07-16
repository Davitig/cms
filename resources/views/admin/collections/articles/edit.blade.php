@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('collections.index') }}">Collections</a>
            </li>
            <li class="breadcrumb-item active">Articles</li>
        </ol>
    </nav>
    @include('admin.-partials.lang.tabs')
    <div class="card">
        <div class="card-header header-elements flex-column flex-md-row align-items-start row-gap-4">
            <div class="fs-5">Articles</div>
            <div class="card-header-elements ms-md-auto flex-row-reverse flex-md-row gap-4">
                <a href="{{ cms_route('articles.create', [$current->collection_id]) }}">
                    <i class="icon-base fa fa-plus icon-xs"></i>
                    <span>Add New Record</span>
                </a>
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Go to
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ cms_route('articles.files.index', [$current->id]) }}" class="dropdown-item">Files</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                @php($activeLang = language()->queryStringOrActive())
                @foreach($items as $current)
                    <div id="item-{{ $current->language }}" class="tab-pane{{ $current->language == $activeLang || ! $activeLang ? ' show active' : '' }}">
                        {{ html()->modelForm($current, 'put', cms_route('articles.update', [$current->collection_id, $current->id], $current->language))
                        ->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                        @include('admin.collections.articles.form')
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@include('admin.-scripts.datepicker')

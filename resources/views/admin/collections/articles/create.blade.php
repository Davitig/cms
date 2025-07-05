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
    <div class="card">
        <div class="card-header fs-5">Articles</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'post', cms_route('articles.store', [$current->collection_id]))->open() }}
            @include('admin.collections.articles.form')
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
@include('admin.-scripts.datepicker')

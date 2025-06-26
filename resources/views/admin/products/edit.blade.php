@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Products</li>
        </ol>
    </nav>
    {{ html()->modelForm($current, 'put', cms_route('products.update', [$current->id], language()->queryStringOrActive()))->open() }}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center row-gap-4 mb-6">
        <div class="fs-4 text-black">Edit Product</div>
        <div class="d-flex align-items-center flex-wrap flex-row-reverse flex-md-row gap-4">
            <a href="{{ cms_route('products.create') }}">
                <i class="icon-base fa fa-plus icon-xs"></i>
                <span>Add New Record</span>
            </a>
            <a href="{{ cms_route('products.index') }}" class="btn btn-label-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    <div class="nav-align-top">
        @include('admin._partials.lang.tabs_linked', ['routeName' => 'products.edit', 'routeParams' => [$current->id]])
    </div>
    <div class="row">
        @include('admin.products.form')
    </div>
    {{ html()->form()->close() }}
@endsection

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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center row-gap-4 mb-6">
        <div class="fs-4 text-black">Add a new Product</div>
        <div class="d-flex flex-wrap gap-4">
            <a href="{{ cms_route('products.index') }}" class="btn btn-label-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
    {{ html()->modelForm($current, 'post', cms_route('products.store'))->open() }}
    <div class="row">
        @include('admin.products.form')
    </div>
    {{ html()->form()->close() }}
@endsection

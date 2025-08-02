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
    <div class="mb-6">
        <div class="fs-4 text-dark">Add a new Product</div>
    </div>
    @includeWhen(! language()->queryStringOrActive(), 'admin.-alerts.resource-requires-lang')
    {{ html()->modelForm($current, 'post', cms_route('products.store'))->attribute('novalidate')->open() }}
    @include('admin.products.form')
    {{ html()->form()->close() }}
@endsection

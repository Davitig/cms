@push('head.title')
    405 Method Not Allowed
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">405</h1>
        <h4 class="mb-2">Method Not Allowed</h4>
        <p class="mb-6">The resource was found, but the request method is not allowed</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

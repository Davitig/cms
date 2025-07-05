@push('head.title')
    404 Not Found
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">404</h1>
        <h4 class="mb-2">Page Not Found️</h4>
        <p class="mb-6">We couldn't find that page</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

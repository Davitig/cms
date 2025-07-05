@push('head.title')
    500 Internal Server Error
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">500</h1>
        <h4 class="mb-2">Internal Server Error</h4>
        <p class="mb-6">The server encountered an internal error and was unable to complete your request</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

@push('head.title')
    503 Service Unavailable
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">503</h1>
        <h4 class="mb-2">Service Unavailable</h4>
        <p class="mb-6">The server is currently unable to handle the request due to a temporary overloading or maintenance of the server</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

@push('head.title')
    401 Unauthorized
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">401</h1>
        <h4 class="mb-2">Unauthorized</h4>
        <p class="mb-6">The request lacks valid authentication credentials for the target resource</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

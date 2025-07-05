@push('head.title')
    403 Forbidden
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="d-flex flex-column align-items-center pt-10">
        <h1 class="mb-2">403</h1>
        <h4 class="mb-2">Forbidden</h4>
        <p class="mb-6">You don't have permission to perform this action</p>
        <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
    </div>
@endsection

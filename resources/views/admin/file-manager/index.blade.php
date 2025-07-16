@extends('admin.app')
@section('content')
    @if ($userRouteAccess('file_manager.index'))
        <iframe src="{{ cms_route('file_manager.index') }}" width="100%" height="650" tabindex="-1"></iframe>
    @else
        <div class="d-flex flex-column align-items-center pt-10">
            <div class="alert alert-danger" role="alert">
                You don't have permission to view this content
            </div>
            <a href="{{ cms_route('dashboard.index') }}" class="btn btn-primary mb-10">Go to home</a>
        </div>
    @endif
@endsection

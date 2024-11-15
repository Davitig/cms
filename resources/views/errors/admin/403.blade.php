@extends('errors.admin.layout')
@section('content')
    @push('head.title')
        <title>403 Forbidden</title>
    @endpush
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-exclamation"></i>
                403 Forbidden
            </h1>
            <p>You don't have permission to perform this action.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

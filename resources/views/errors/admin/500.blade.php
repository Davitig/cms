@extends('errors.admin.layout')
@section('content')
    @push('head.title')
        <title>500 Internal Server Error</title>
    @endpush
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-exclamation"></i>
                500 Internal Server Error
            </h1>
            <p>The server encountered an internal error and was unable to complete your request.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

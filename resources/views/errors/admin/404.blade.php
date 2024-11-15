@extends('errors.admin.layout')
@section('content')
    @push('head.title')
        <title>404 Not Found</title>
    @endpush
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-times-circle"></i>
                404 Not Found
            </h1>
            <p>We did not find the page you were looking for.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

@extends('errors.admin.layout')
@section('content')
    @push('head.title')
        <title>503 Service Unavailable</title>
    @endpush
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-times-circle"></i>
                503 Service Unavailable
            </h1>
            <p>The server is currently unable to handle the request due to a temporary overloading or maintenance of the server.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

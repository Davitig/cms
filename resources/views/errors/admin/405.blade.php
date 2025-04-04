@push('head.title')
    <title>405 Method Not Allowed</title>
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-exclamation"></i>
                405 Method Not Allowed
            </h1>
            <p>The resource was found, but the request method is not allowed.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

@push('head.title')
    <title>401 Unauthorized</title>
@endpush
@extends('errors.admin.layout')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="fa fa-exclamation"></i>
                401 Unauthorized
            </h1>
            <p>The request has not been applied because it lacks valid authentication credentials for the target resource.</p>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-body">
        </div>
    </div>
@endsection

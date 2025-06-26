@extends('admin.app')
@section('content')
    <iframe src="{{ cms_route('fileManager.index') }}" width="100%" height="650" tabindex="-1"></iframe>
@endsection

@extends('admin.app')
@section('content')
<div class="page-title">
    <div class="title-env">
        <h1 class="title">
            <i class="fa fa-gear"></i>
            Web Settings
        </h1>
        <p class="description">Management of the web settings</p>
    </div>
    <div class="breadcrumb-env">
        <ol class="breadcrumb bc-1">
            <li>
                <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
            </li>
            <li class="active">
                <i class="fa fa-gear"></i>
                <strong>Web Settings</strong>
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Web settings form</h2>
    </div>
@if (! empty($webSettings))
    {{ html()->form('put', cms_route('webSettings.update'))->id('form-update')->class('form-horizontal')->open() }}
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label">Email:</label>
                <div class="col-sm-5">
                    {{ html()->text('email', $webSettings->email)->class('form-control') }}
                    <div class="desc">Messages from users will be sent to this email address.</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Phone:</label>
                <div class="col-sm-5">
                    {{ html()->text('phone', $webSettings->phone)->class('form-control') }}
                    <div class="desc">Phone number that will be displayed on the website.</div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Address:</label>
                <div class="col-sm-5">
                    {{ html()->text('address', $webSettings->address)->class('form-control') }}
                    <div class="desc">Address that will be displayed on the website.</div>
                </div>
            </div>
        </div>

        <div class="form-group-separator"></div>

        <div class="form-group">
            <div class="col-sm-10 btn-action pull-right">
                <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ trans('general.update') }}">
                    <i class="fa fa-save"></i>
                    <span>{{ trans('general.save') }}</span>
                </button>
            </div>
        </div>
    {{ html()->form()->close() }}
@endif
</div>
@endsection

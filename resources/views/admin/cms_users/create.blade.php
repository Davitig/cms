@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('cmsUsers')}}"></i>
                CMS Users
            </h1>
            <p class="description">Management of the CMS users</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>CMS Users</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-headerless">
        <div class="panel-body">
            {{ html()->modelForm($current, 'post', cms_route('cmsUsers.store'))
            ->acceptsFiles()->class('form-horizontal')->open() }}
            <div class="member-form-add-header">
                <div class="row">
                    <div class="col-md-10 col-sm-8">
                        <div class="user-img">
                            <div id="photo-upload-btn" class="droppable-area dz-clickable mrg0 border-0">
                                <span class="photo-upload-text">Upload Photo</span>
                                <img src="#" width="150" height="150" id="user-photo" class="img-circle vat hidden" alt="Photo">
                            </div>
                            {{ html()->file('photo')->id('photo-input')->class('hidden') }}
                        </div>
                        <div class="photo-msg text-danger">{{$errors->first('photo')}}</div>
                    </div>
                </div>
            </div>
            @include('admin.cms_users.form', [
                'submit' => trans('general.create'),
                'submitAndBack' => trans('general.create_n_close'),
                'icon' => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection
@include('admin.cms_users.scripts')

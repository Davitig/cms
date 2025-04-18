@extends('admin.app')
@section('content')
<div class="page-title">
    <div class="title-env">
        <h1 class="title">
            <i class="{{$icon = icon_type('cmsUsers')}}"></i>
            Profile
        </h1>
        <p class="description">Profile of the CMS user</p>
    </div>
    <div class="breadcrumb-env">
        <ol class="breadcrumb bc-1">
            <li>
                <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
            </li>
            <li>
                <a href="{{ cms_route('cmsUsers.index') }}"><i class="{{$icon}}"></i>CMS Users</a>
            </li>
            <li class="active">
                <strong>{{$current->first_name}} {{$current->last_name}}</strong>
            </li>
        </ol>
    </div>
</div>
<section class="profile-env">
    <div class="row">
        <div class="col-sm-3">
            <div class="user-info-sidebar">
                <div class="user-img">
                    <img src="{{cms_route('cmsUsers.photo', [$current->id])}}" alt="User photo" class="img-cirlce img-responsive img-thumbnail">
                </div>
                <div class="user-name">
                    {{$current->first_name}} {{$current->last_name}}
                    <span class="user-status is-online"></span>
                </div>
                <span class="btn-block text-center">
                    <strong>{{ucfirst($current->role)}}</strong>
                </span>
            @if (auth('cms')->user()->hasFullAccess() || auth('cms')->id() == $current->id)
                <a href="{{cms_route('cmsUsers.edit', [$current->id])}}" class="btn-block text-center">{{trans('general.edit')}}</a>
            @endif
                <hr>
                <ul class="list-unstyled user-info-list">
                    <li>
                        <i class="fa fa-envelope"></i>
                        {{$current->email}}
                    </li>
                    <li>
                        <i class="fa fa-phone-square"></i>
                        {{$current->phone}}
                    </li>
                    <li>
                        <i class="fa fa-building"></i>
                        {{$current->address}}
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-sm-9">
            <section class="user-timeline-stories">
                <article class="timeline-story">...</article>
            </section>
        </div>
    </div>
</section>
@endsection

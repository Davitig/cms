@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('roles')}}"></i>
                CMS User Roles
            </h1>
            <p class="description">Management of the CMS user roles</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>CMS User Roles</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Edit CMS user role</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
            <a href="{{cms_route('cmsUserRoles.create')}}" class="pull-right padr">Add more</a>
        </div>
        <div class="panel-body">
            {{ html()->modelForm($current,
                'put', cms_route('cmsUserRoles.update', [$current->id])
            )->class('form-horizontal ' . $cmsSettings->get('ajax_form'))->open() }}
            @include('admin.cms_user_roles.form', [
                'submit'        => trans('general.update'),
                'submitAndBack' => trans('general.update_n_back'),
                'icon'          => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
    @push('body.bottom')
        <script type="text/javascript">
            $('form.ajax-form').on('ajaxFormSuccess', function (e, res) {
                if (res?.data?.full_access === '1') {
                    $('#permissions-btn').addClass('hidden');
                } else {
                    $('#permissions-btn').removeClass('hidden');
                }
            });
        </script>
    @endpush
@endsection

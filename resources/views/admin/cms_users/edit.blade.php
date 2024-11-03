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
            {{ html()->modelForm($current, 'put', cms_route('cmsUsers.update', [$current->id]))
            ->class('form-horizontal ' . $cmsSettings->get('ajax_form'))->open() }}
            <div class="member-form-add-header">
                <div class="row">
                    <div class="col-md-2 col-sm-4 pull-right-sm">
                        <div class="action-buttons">
                            <div class="profile">
                                <a href="{{$routeShow = cms_route('cmsUsers.show', [$current->id])}}" class="btn btn-block btn-turquoise">Profile</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10 col-sm-8">
                        @if ($current->photo)
                            <div class="user-img">
                                <img src="{{$current->photo}}" width="128" class="img-circle" alt="Photo">
                            </div>
                        @endif
                        <div class="user-name">
                            <a href="{{$routeShow}}">{{$current->first_name}} {{$current->last_name}}</a>
                            <span>{{ucfirst($current->role)}}</span>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.cms_users.form', [
                'submit'        => trans('general.update'),
                'submitAndBack' => trans('general.update_n_back'),
                'icon'          => 'save'
            ])
            {{ html()->form()->close() }}
        </div>
    </div>
    @push('body.bottom')
        <script type="text/javascript">
            $(function() {
                $('form.ajax-form').on('ajaxFormSuccess', function() {
                    let first_name = $('#first_name', this).val();
                    let last_name = $('#last_name', this).val();
                    let photo = $('#photo', this).val();

                    $('.user-name a', this).text(first_name + ' ' + last_name);
                    $('.user-name span', this).text($('[name="cms_user_role_id"] option:selected', this).text());
                    $('.user-img img', this).attr('src', photo);

                    $('#password, #password_confirmation', this).val('');
                });
            });
        </script>
    @endpush
@endsection

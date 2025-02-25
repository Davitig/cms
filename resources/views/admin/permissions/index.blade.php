@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('permissions')}}"></i>
                Permissions
            </h1>
            <p class="description">Set permissions for the selected CMS user role</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{ cms_route('cmsUserRoles.index') }}"><i class="{{icon_type('roles')}}"></i>CMS User roles</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Permissions</strong>
                </li>
            </ol>
        </div>
    </div>
    <form action="{{cms_route('permissions.store')}}" method="post" id="permissions-form">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="role_id" value="{{ $activeRoleId }}">
        <div class="panel panel-headerless">
            <div class="panel-body">
                <div class="member-form-add-header">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 pull-right-sm">
                            <div class="permissions">
                                <a href="{{cms_route('cmsUserRoles.index')}}" class="btn btn-block btn-turquoise">{{ trans('general.back') }}</a>
                            </div>
                            <div class="action-buttons">
                                <button type="submit" class="btn btn-block btn-secondary">{{ trans('general.update') }}</button>
                            </div>
                        </div>
                        <div class="col-md-10 col-sm-8">
                            <h2 class="text-primary inline padr">Roles:</h2>
                            <div id="roles-btn" class="inline">
                                @foreach($roles as $id => $role)
                                    <a href="{{ cms_route('permissions.index', ['role' => $id]) }}" class="btn btn-{{ $id == $activeRoleId ? 'info' : 'gray' }}">{{ ucfirst($role) }}</a>
                                @endforeach
                            </div>
                        </div>
                        <div id="multi-check-all" class="multi-check dib padl fs-14">
                            <a href="#" class="check-action" data-group="*" data-action="check">Check all</a> /
                            <a href="#" class="check-action" data-group="*" data-action="uncheck">Uncheck all</a> /
                            <a href="#" class="check-action" data-group="*" data-action="toggle">Toggle</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="permissions-input" class="">
            @foreach ($routeGroups as $groupName => $routes)
                <div class="panel panel-default clearfix">
                    <div class="panel-heading">
                        <label>{{ucfirst($groupName ?: config('app.name'))}}</label>
                        -
                        <div class="multi-check dib fs-14">
                            <a href="#" class="check-action" data-group="{{$groupName}}" data-action="Check">Check all</a> /
                            <a href="#" class="check-action" data-group="{{$groupName}}" data-action="Uncheck">Uncheck all</a> /
                            <a href="#" class="check-action" data-group="{{$groupName}}" data-action="Toggle">Toggle</a>
                        </div>
                    </div>
                    @foreach ($routes as $subGroupName => $name)
                        @if (is_array($name))
                            <div class="clearfix padb">
                                <div class="row">
                                    <div class="title col-xs-12 padt">
                                        <div class="fs-16{{ $loop->first ? '' : ' padt bot' }}">
                                            {{ucfirst($subGroupName ?: config('app.name'))}}
                                            -
                                            <div class="multi-check dib fs-14">
                                                <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="Check">Check all</a> /
                                                <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="Uncheck">Uncheck all</a> /
                                                <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="Toggle">Toggle</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($name as $subName)
                                    <div class="panel-body col-xs-6 col-sm-4 col-md-3">
                                        <label><strong>{{str(implode(' ', explode('.', $subName)))->headline()}}</strong></label>
                                        <input type="checkbox" name="permissions[{{$groupName}}][]" value="{{$subName}}"{{in_array($subName, $currentRoutes) ? ' checked' : ''}} class="{{$groupName}} {{$subGroupName}} icheck" id="{{$subName}}">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="panel-body col-xs-6 col-sm-4 col-md-3">
                                <label><strong>{{str(implode(' ', explode('.', $name)))->headline()}}</strong></label>
                                <input type="checkbox" name="permissions[{{$groupName}}][]" value="{{$name}}"{{in_array($name, $currentRoutes) ? ' checked' : ''}} class="{{$groupName}} icheck" id="{{$name}}">
                            </div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            <div class="panel panel-default">
                <div class="btn-action text-center">
                    <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ trans('general.update') }}">
                        <i class="fa fa-save"></i>
                        <span>{{ trans('general.update') }}</span>
                    </button>
                    <a href="{{ cms_route('cmsUserRoles.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
                        <i class="fa fa-arrow-left"></i>
                        <span>{{ trans('general.back') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </form>
    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/libs/js/icheck/skins/all.css') }}">
    @endpush
    @push('body.bottom')
        <script src="{{ asset('assets/libs/js/icheck/icheck.min.js') }}"></script>
        <script type="text/javascript">
            $(function() {
                // Style Checkbox
                $('input.icheck').iCheck({
                    checkboxClass: 'icheckbox_square-red'
                });
                // check all / uncheck all / toggle all
                let permissionsForm = $('#permissions-form');
                $('.check-action').on('click', permissionsForm, function(e) {
                    e.preventDefault();
                    let action = $(this).data('action');
                    let group = $(this).data('group');
                    let selector = group === '*'
                        ? permissionsForm
                        : permissionsForm.find('.' + group);
                    selector.each(function() {
                        $(this).iCheck(action);
                    })
                });
            })
        </script>
    @endpush
@endsection

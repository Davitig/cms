@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Permissions</li>
        </ol>
    </nav>
    {{ html()->form('post', cms_route('permissions.store'))->id('permissions-form')->open() }}
    <input type="hidden" name="role_id" value="{{ $activeRoleId }}">
    <nav class="navbar navbar-expand-lg bg-gradient-light rounded mb-6">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="javascript:void(0)">
                <span>Role:</span>
                {{ $activeRole }}
                <span class="total-allowed badge rounded-pill p-1 bg-{{ ($activeRoutesCount = count($activeRoutes)) ? 'success' : 'danger' }} ms-2">
                    {{ number_format($activeRoutesCount) }}
                </span>
            </a>
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbar-content"
                aria-controls="navbar-content"
                aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar-content">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <a href="javascript:void(0)" class="nav-link dropdown-toggle"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Roles
                        </a>
                        <ul class="dropdown-menu">
                            @foreach($roles as $id => $role)
                                <li>
                                    <a href="{{ cms_route('permissions.index', ['role' => $id]) }}" class="dropdown-item">{{ $role }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <div class="mx-lg-4 my-4 my-lg-0">
                    <a href="#" class="check-action" data-group="*" data-action="check">Check all</a> /
                    <a href="#" class="check-action" data-group="*" data-action="uncheck">Uncheck all</a> /
                    <a href="#" class="check-action" data-group="*" data-action="toggle">Toggle</a>
                </div>
                <button type="submit" class="btn btn-primary me-4">Submit</button>
                <a href="{{ cms_route('cms_user_roles.index') }}" class="btn btn-label-secondary">Cancel</a>
            </div>
        </div>
    </nav>
    <div class="row mt-6">
        @foreach ($routeGroups as $groupName => $routes)
            @php
                $allowedRouteGroupCount = count(array_filter($activeRoutes, fn ($item) => str_starts_with($item, $groupName . '.')));
                $fullAccessOnGroup = $allowedRouteGroupCount == count(\Illuminate\Support\Arr::flatten($routes));
            @endphp
            <div class="col-md-6 col-xxl-4 mb-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center border-bottom mb-4">
                        <div class="d-flex align-items-center">
                            <div class="title-{{ $groupName }} badge bg-{{ $fullAccessOnGroup ? 'success' : ($allowedRouteGroupCount ? 'warning' : 'danger') }} rounded me-4 p-1_5 pe-3">
                                <i class="icon-base fa fa-user-{{ $allowedRouteGroupCount ? 'check' : 'lock' }} icon-sm"></i>
                            </div>
                            <div class="fs-5">
                                {{ucfirst($groupName ?: config('app.name'))}}
                            </div>
                        </div>
                        <div>
                            <div class="form-text">
                                <a href="#" class="check-action" data-group="{{$groupName}}" data-action="check">Check all</a> /
                                <a href="#" class="check-action" data-group="{{$groupName}}" data-action="uncheck">Uncheck all</a> /
                                <a href="#" class="check-action" data-group="{{$groupName}}" data-action="toggle">Toggle</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-{{ $groupName }} list-group p-0 m-0 list-unstyled" data-list-group="{{ $groupName }}">
                            @php($hasMainList = false)
                            @foreach ($routes as $subGroupName => $name)
                                @if (is_array($name))
                                    <li class="mb-4">
                                        <div class="p{{ $hasMainList ? 'y' : 'b' }}-4 d-flex justify-content-between border{{ $hasMainList ? ' border-start-0 border-end-0' : '-bottom' }}">
                                            <div class="fs-5">
                                                {{ucfirst($subGroupName ?: config('app.name'))}}
                                            </div>
                                            <div>
                                                <div class="form-text">
                                                    <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="check">Check all</a> /
                                                    <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="uncheck">Uncheck all</a> /
                                                    <a href="#" class="check-action" data-group="{{$subGroupName}}" data-action="toggle">Toggle</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @foreach($name as $subName)
                                        @php($hasAccess = in_array($subName, $activeRoutes))
                                        <li class="mb-4 d-flex justify-content-between align-items-center">
                                            <div class="badge bg-label-{{ $hasAccess ? 'success' : 'danger' }} rounded p-1_5 pe-3">
                                                <i class="icon-base fa fa-user-{{ $hasAccess ? 'check' : 'lock' }} icon-sm"></i>
                                            </div>
                                            <div class="d-flex justify-content-between w-100 flex-wrap">
                                                <h6 class="mb-0 ms-4">{{str(implode(' ', explode('.', $subName)))->headline()}}</h6>
                                                <div class="d-flex form-check-success">
                                                    <input type="checkbox" name="permissions[{{$groupName}}][]" value="{{$subName}}"{{$hasAccess ? ' checked' : ''}} class="inp-{{$groupName}} inp-{{$subGroupName}} form-check-input" data-inp-group="{{ $subGroupName }}">
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                @else
                                    @php($hasAccess = in_array($name, $activeRoutes))
                                    @php($hasMainList = true)
                                    <li class="mb-4 d-flex justify-content-between align-items-center">
                                        <div class="badge bg-label-{{ $hasAccess ? 'success' : 'danger' }} rounded p-1_5 pe-3">
                                            <i class="icon-base fa fa-user-{{ $hasAccess ? 'check' : 'lock' }} icon-sm"></i>
                                        </div>
                                        <div class="d-flex justify-content-between w-100 flex-wrap">
                                            <h6 class="mb-0 ms-4">{{str(implode(' ', explode('.', $name)))->headline()}}</h6>
                                            <div class="d-flex form-check-success">
                                                <input type="checkbox" name="permissions[{{$groupName}}][]" value="{{$name}}"{{$hasAccess ? ' checked' : ''}} class="inp-{{$groupName}} form-check-input" data-inp-group="{{ $groupName }}">
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-grid gap-2 col-lg-6 mx-auto">
                <button type="submit" class="btn btn-primary btn-lg">Submit</button>
                <a href="{{ cms_route('cms_user_roles.index') }}" class="btn btn-label-secondary btn-lg">Cancel</a>
            </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection
@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            let total = {{ $activeRoutesCount }};
            let permissionsForm = $('#permissions-form');
            // check / uncheck
            $('input[type="checkbox"]').on('click', permissionsForm, function () {
                itemBadgeToggler(this);

                if (this.checked) {
                    total++;
                } else {
                    total--;
                }

                let hasChecked = false;
                let hasUnchecked = false;

                $(this).closest('ul').find('input[type="checkbox"]').each(function () {
                    if (this.checked) {
                        hasChecked = true;
                    } else {
                        hasUnchecked = true;
                    }
                });

                mainBadgeToggler($(this).data('inp-group'), hasChecked, hasUnchecked);
                totalBadgeCounter(total);
            });
            // check all / uncheck all / toggle all
            $('.check-action').on('click', permissionsForm, function (e) {
                e.preventDefault();

                let action = $(this).data('action');
                let group = $(this).data('group');

                if (group === '*') {
                    permissionsForm.find('.list-group').each(function () {
                        iterator($(this).find('input[type="checkbox"]'), action, $(this).data('list-group'));
                    });
                } else {
                    iterator(permissionsForm.find('.inp-' + group), action, group);
                }

                totalBadgeCounter(total);
            });
            let iterator = function (selector, action, group) {
                let hasChecked = false;
                let hasUnchecked = false;

                selector.each(function () {
                    if (action === 'toggle') {
                        if (! this.checked) {
                            total++;
                        } else if (this.checked) {
                            total--;
                        }

                        this.checked = ! this.checked;
                    } else {
                        if (! this.checked && action === 'check') {
                            total++;
                        } else if (this.checked && action !== 'check') {
                            total--;
                        }

                        this.checked = action === 'check';
                    }

                    itemBadgeToggler(this);

                    if (this.checked) {
                        hasChecked = true;
                    } else {
                        hasUnchecked = true;
                    }

                });

                mainBadgeToggler(group, hasChecked, hasUnchecked);
            }
            let itemBadgeToggler = function (target) {
                let badgeSelector = $(target).closest('li').find('.badge');
                if (target.checked) {
                    badgeSelector.removeClass('bg-label-danger').addClass('bg-label-success')
                        .find('i').removeClass('fa-user-lock').addClass('fa-user-check');
                } else {
                    badgeSelector.removeClass('bg-label-success').addClass('bg-label-danger')
                        .find('i').removeClass('fa-user-check').addClass('fa-user-lock')
                }
            }
            let mainBadgeToggler = function (group, hasChecked, hasUnchecked) {
                if (hasChecked && hasUnchecked) {
                    $('.title-' + group).removeClass('bg-danger bg-success').addClass('bg-warning')
                        .find('i').removeClass('fa-user-lock').addClass('fa-user-check');
                } else if (hasChecked && ! hasUnchecked) {
                    $('.title-' + group).removeClass('bg-danger bg-warning').addClass('bg-success')
                        .find('i').removeClass('fa-user-lock').addClass('fa-user-check');
                } else {
                    $('.title-' + group).removeClass('bg-warning bg-success').addClass('bg-danger')
                        .find('i').removeClass('fa-user-check').addClass('fa-user-lock');
                }
            }
            let totalBadgeCounter = function (total) {
                let totalAllowedSelector = $('.total-allowed', permissionsForm);
                if (total) {
                    totalAllowedSelector.removeClass('bg-danger').addClass('bg-success');
                } else {
                    totalAllowedSelector.removeClass('bg-success').addClass('bg-danger');
                }
                totalAllowedSelector.text(total);
            }
        })
    </script>
@endpush

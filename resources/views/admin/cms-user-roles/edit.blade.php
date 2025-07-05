@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">CMS User Roles</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements">
            <div class="fs-5">CMS User Roles</div>
            <div class="card-header-elements ms-auto">
                <a href="{{ cms_route('cmsUserRoles.create') }}">
                    <i class="icon-base fa fa-plus icon-xs"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="panel-body">
                {{ html()->modelForm($current, 'put', cms_route('cmsUserRoles.update', [$current->id]))
                ->data('ajax-form', $preferences->get('ajax_form'))->attribute('novalidate')->open() }}
                @include('admin.cms-user-roles.form')
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
@endsection
@push('body.bottom')
    <script type="text/javascript">
        $('form[data-ajax-form="1"]').on('ajaxFormSuccess', function (e, res) {
            if (parseInt(res?.data?.full_access)) {
                $('#permissions-btn').addClass('d-none');
            } else {
                $('#permissions-btn').removeClass('d-none');
            }
        });
    </script>
@endpush

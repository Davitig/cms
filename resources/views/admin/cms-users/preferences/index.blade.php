@extends('admin.app')
@section('content')
    @include('admin.cms-users.-partials.header', ['current' => $cmsUser])
    @include('admin.cms-users.-partials.navbar', ['current' => $cmsUser])
    <div class="card">
        <div class="card-header fs-5">Preferences</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'put', cms_route('cms_users.preferences.save', [$cmsUser->id]))
            ->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <label class="switch switch-primary">
                    {{ html()->checkbox('horizontal_menu')
                    ->id('horizontal_menu_inp')->class('switch-input') }}
                    <span class="switch-toggle-slider"></span>
                    <span class="switch-label">Horizontal Menu</span>
                </label>
                <label class="switch switch-primary">
                    {{ html()->checkbox('ajax_form')
                    ->id('ajax_form_inp')->class('switch-input') }}
                    <span class="switch-toggle-slider"></span>
                    <span class="switch-label">No reload on form update</span>
                </label>
                @if ($userRouteAccess('cms_user_roles.index'))
                    <div>
                        <label for="roles_list_view_inp" class="form-label">Roles List View</label>
                        {{ html()->select('roles_list_view', [
                            'table' => 'Table', 'card' => 'Card'
                        ])->id('roles_list_view_inp')->class('form-select') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

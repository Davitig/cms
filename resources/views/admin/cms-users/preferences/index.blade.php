@extends('admin.app')
@section('content')
    <!-- Navbar pills -->
    <div class="row">
        <div class="col-md-12">
            @include('admin.cms-users.navbar')
        </div>
    </div>
    <div class="card">
        <div class="card-header fs-5">Preferences</div>
        <div class="card-body">
            {{ html()->form('put', cms_route('cmsUsers.preferences.update', [$current->id]))->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <label class="switch switch-primary">
                    {{ html()->checkbox('horizontal_menu', $preferences->get('horizontal_menu'))
                    ->id('horizontal_menu_inp')->class('switch-input') }}
                    <span class="switch-toggle-slider"></span>
                    <span class="switch-label">Horizontal Menu</span>
                </label>
                <label class="switch switch-primary">
                    {{ html()->checkbox('ajax_form', $preferences->get('ajax_form'))
                    ->id('ajax_form_inp')->class('switch-input') }}
                    <span class="switch-toggle-slider"></span>
                    <span class="switch-label">No reload on form submit</span>
                </label>
                @if ($userRouteAccess('cmsUserRoles.index'))
                    <div>
                        <label for="roles_list_view_inp" class="form-label">Roles List View</label>
                        {{ html()->select('roles_list_view', [
                            'table' => 'Table', 'card' => 'Card'
                        ], $preferences->get('roles_list_view'))->id('roles_list_view_inp')->class('form-select') }}
                    </div>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

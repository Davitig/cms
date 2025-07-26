@extends('admin.app')
@section('content')
    @include('admin.cms-users.-partials.header')
    @include('admin.cms-users.-partials.navbar')
    <div class="card">
        <div class="card-header fs-5">Change Password</div>
        <div class="card-body">
            {{ html()->modelForm($current, 'put', cms_route('cms_users.password', [$current->id]))
            ->data('ajax-form', $preferences->get('ajax_form'))->attribute('novalidate')->open() }}
            <div class="row g-6 mb-6">
                <div>
                    <label class="form-label required" for="current_password">Current Password</label>
                    <input type="password" name="current_password" id="current_password_inp" class="form-control"
                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                </div>
                @error('current_password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
                <div class="col-lg-6">
                    <label class="form-label required" for="password">New Password</label>
                    <input type="password" name="password" id="password_inp" class="form-control"
                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                    @error('password')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <label class="form-label required" for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                </div>
                <div>
                    <h6 class="text-body">Password Requirements:</h6>
                    <ul class="ps-4 mb-0">
                        <li class="mb-4">Minimum 8 characters long</li>
                        <li>At least one number</li>
                    </ul>
                </div>
            </div>
            <button type="submit" class="btn btn-primary me-3">Save changes</button>
            <a href="{{ cms_route('cms_users.show', [$current->id]) }}" class="btn btn-label-secondary">Cancel</a>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

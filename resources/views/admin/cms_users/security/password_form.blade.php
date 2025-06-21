<div class="card mb-6">
    <h5 class="card-header">Change Password</h5>
    {{ html()->modelForm($current, 'put', cms_route('cmsUsers.password', [$current->id]))->open() }}
    <div class="card-body pt-1">
        <div class="row mb-sm-6 mb-2">
            <div class="col-md-6">
                <label class="form-label" for="current_password">Current Password</label>
                <input type="password" name="current_password" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            </div>
            @error('current_password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="row gy-sm-6 gy-2 mb-sm-0 mb-2">
            <div class="mb-6 col-md-6">
                <label class="form-label" for="password">New Password</label>
                <input type="password" name="password" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                @error('password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-6 col-md-6">
                <label class="form-label" for="password_confirmation">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
            </div>
        </div>
        <h6 class="text-body">Password Requirements:</h6>
        <ul class="ps-4 mb-0">
            <li class="mb-4">Minimum 8 characters long</li>
            <li>At least one number</li>
        </ul>
        <div class="mt-6">
            <button type="submit" class="btn btn-primary me-3">Save changes</button>
            <a href="{{ cms_route('cmsUsers.show', [$current->id]) }}" class="btn btn-label-secondary">Cancel</a>
        </div>
    </div>
    {{ html()->form()->close() }}
</div>

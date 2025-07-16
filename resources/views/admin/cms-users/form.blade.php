<div class="row g-6 mb-6">
    <div class="col-md-6">
        <label for="email_inp" class="form-label required">E-mail</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <i class="icon-base fa-regular fa-envelope"></i>
            </span>
            {{ html()->text('email')->id('email_inp')->class('form-control') }}
        </div>
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    @if (auth('cms')->user()->hasFullAccess() && auth('cms')->id() != $current->id)
        <div class="col-md-6">
            <label for="cms_user_role_id_inp" class="form-label required">Role</label>
            <div class="input-group input-group-merge">
                <span class="input-group-text">
                    <i class="icon-base fa fa-user-pen"></i>
                </span>
                {{ html()->select('cms_user_role_id', $roles)->id('cms_user_role_id_inp')->class('form-select') }}
            </div>
        </div>
    @endif
    @error('cms_user_role_id')
    <div class="text-danger">{{ $message }}</div>
    @enderror
    <div class="col-md-6">
        <label for="first_name_inp" class="form-label required">First Name</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <i class="icon-base fa fa-user-tie"></i>
            </span>
            {{ html()->text('first_name')->id('first_name_inp')->class('form-control') }}
        </div>
        @error('first_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="last_name_inp" class="form-label required">Last Name</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <i class="icon-base fa fa-user-tie"></i>
            </span>
            {{ html()->text('last_name')->id('last_name_inp')->class('form-control') }}
        </div>
        @error('last_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="phone_inp" class="form-label">Phone Number</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <i class="icon-base fa fa-phone"></i>
            </span>
            {{ html()->text('phone')->id('phone_inp')->class('form-control') }}
        </div>
    </div>
    <div class="col-md-6">
        <label for="address_inp" class="form-label">Address</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <i class="icon-base fa fa-address-book"></i>
            </span>
            {{ html()->text('address')->id('address_inp')->class('form-control') }}
        </div>
    </div>
    <div>
        <label class="switch switch-warning">
            {{ html()->checkbox('suspended')->id('suspended_inp')->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Suspend</span>
        </label>
    </div>
    @if (! $current->id)
        <div class="col-md-6">
            <label for="password_inp" class="form-label required">Password</label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label for="password_inp" class="form-label required">Repeat Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <h6 class="text-body mt-4">Password Requirements:</h6>
        <ul class="ps-7 m-0">
            <li class="mb-4">Minimum 8 characters long</li>
            <li>At least one number</li>
        </ul>
    @endif
</div>
<button type="submit" class="btn btn-primary me-3">Save changes</button>
<a href="{{ cms_route('cms_users.' . ($current->id ? 'show' : 'index'), [$current->id]) }}" class="btn btn-label-secondary">Cancel</a>


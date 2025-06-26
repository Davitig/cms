<div class="row g-6 mb-6">
    <div>
        <label for="role_inp" class="form-label">Role Name</label>
        {{ html()->text('role')->id('role_inp')->class('form-control') }}
        @error('role')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="full_access_inp" class="form-label">Access</label>
        {{ html()->select('full_access', [0 => 'Custom', 1 => 'Full Access'])
        ->id('full_access_inp' . $current->language)->class('form-select select') }}
        @error('full_access')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
<button type="submit" class="btn btn-primary me-4">Submit</button>
<a href="{{ cms_route('permissions.index', ['role' => $current->id]) }}" id="permissions-btn"
   class="btn btn-label-{{ $current->permissions_count ? 'warning' : 'danger' }} me-4 {{ ! $current->id || $current->full_access ? ' d-none' : '' }}" title="Permissions">
    <i class="icon-base fa fa-user-lock icon-sm me-4"></i>
    <span>Permissions</span>
</a>
<a href="{{ cms_route('cmsUserRoles.index') }}" class="btn btn-label-secondary">Cancel</a>

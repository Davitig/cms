<div class="card-body d-flex align-items-start align-items-sm-center gap-6">
    <img src="{{ $current->id ? cms_route('cmsUsers.photo', [$current->id]) : asset('assets/default/img/avatar.png') }}"
         alt="Photo" class="d-block w-px-100 h-px-100 rounded bg-white user-photo">
    <div class="button-wrapper">
        <label for="photo_inp" class="btn btn-primary me-3 mb-4" tabindex="0">
            <span class="d-none d-sm-block">Upload new photo</span>
            <i class="icon-base fa fa-upload d-block d-sm-none"></i>
            <input type="file" name="photo" id="photo_inp" class="account-file-input" hidden>
        </label>
        <label for="remove_photo_inp" class="btn btn-label-secondary account-image-reset mb-4">
            <i class="icon-base fa fa-trash-arrow-up d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Reset</span>
            <input type="checkbox" name="remove_photo" id="remove_photo_inp" hidden>
        </label>
        <div>Max size of 2MB</div>
    </div>
</div>
<div class="text-danger photo-msg">{{ $errors->first('photo') }}</div>


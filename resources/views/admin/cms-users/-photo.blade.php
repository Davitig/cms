@php($avatar = asset('assets/default/img/avatar.png'))
<div class="card-body d-flex align-items-start align-items-sm-center gap-6">
    <img src="{{ $current->id ? cms_route('cms_users.photo', [$current->id]) : $avatar }}"
         alt="Photo" class="d-block w-px-100 h-px-100 rounded bg-white user-photo"
         data-default="{{ $avatar }}">
    <div class="button-wrapper" data-error="append">
        <div class="d-flex">
            {{ html()->modelForm($current, 'post', cms_route('cms_users.image.store', [$current->id]))
            ->id('upload-photo')->data('ajax-form', 1)->acceptsFiles()->open() }}
            {{ html()->hidden('image_type', 'photo') }}
            <label for="photo_inp" class="btn btn-primary me-3 mb-4" tabindex="0">
                <div class="loading-photo spinner-border spinner-border-sm text-white me-1 d-none"></div>
                <span class="d-none d-sm-block">Upload new photo</span>
                <i class="icon-base fa fa-upload d-block d-sm-none"></i>
                <input type="file" name="photo" id="photo_inp" class="account-file-input" hidden>
            </label>
            {{ html()->form()->close() }}
            {{ html()->modelForm($current, 'delete', cms_route('cms_users.image.destroy', [$current->id]))
            ->class('delete-image')->data('type', 'photo')->open() }}
            {{ html()->hidden('image_type', 'photo') }}
            <button type="submit" class="btn btn-label-secondary account-image-reset">
                <i class="icon-base fa fa-trash-arrow-up d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Remove</span>
            </button>
            {{ html()->form()->close() }}
        </div>
        <div>Max size of 1MB</div>
        @error('photo')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

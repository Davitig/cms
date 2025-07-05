<div class="row g-6 mb-6">
    <div>
        <label for="full_name_inp" class="form-label required">Full Name</label>
        {{ html()->text('full_name')->id('full_name_inp')->class('form-control')->placeholder('E.g. English')  }}
        @error('full_name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="short_name_inp" class="form-label required">Short Name</label>
        {{ html()->text('short_name')->id('short_name_inp')->class('form-control')->placeholder('E.g. en')  }}
        @error('short_name')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="language_inp" class="form-label required">Language Code</label>
        <div class="input-group input-group-merge">
            <span class="input-group-text">
                <img id="lang-img" src="{{ asset('assets/default/img/flags/' . ($current->language ?: 'en') . '.png') }}" width="28" height="18" alt="flag">
            </span>
            {{ html()->text('language')->id('language_inp')->class('form-control')->placeholder('E.g. en')  }}
        </div>
        @error('language')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <div class="form-text">
            Code will be used in URLs. E.g.
            <span class="text-primary">{{ request()->host() }}/<strong class="sample-lang">{{ $current->language ?: 'en' }}</strong>/home</span>
        </div>
    </div>
    <div>
        <label class="switch switch-primary">
            {{ html()->checkbox('visible')->id('visible_inp')->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Visible</span>
        </label>
        <label class="switch switch-primary">
            {{ html()->checkbox('main')->id('main_inp')->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Main</span>
        </label>
    </div>
</div>
<div class="d-flex gap-4">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ cms_route('languages.index') }}" class="btn btn-label-secondary">Cancel</a>
</div>

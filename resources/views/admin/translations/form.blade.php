<div class="row g-4 mb-4">
    <div>
        <label for="title_inp" class="form-label">Name</label>
        {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control')->data('lang', 1) }}
        @error('title')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-text">The title for the "value." Visible only in Admin</div>
    </div>
    <div>
        <label for="value_inp" class="form-label">Value</label>
        {{ html()->text('value')->id('value_inp' . $current->language)->class('form-control')->data('lang', 1) }}
        @error('value')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-text">The value contains the translated text that will be displayed to public</div>
    </div>
    <div>
        <label for="code_inp" class="form-label">Code</label>
        {{ html()->text('code')->id('code_inp' . $current->language)->class('form-control')->data('lang', 1)
        ->ifNotNull($current->code, function ($html) {
            return $html->disabled();
        }) }}
        @if ($current->code)
            <input type="hidden" name="code" value="{{ $current->code }}">
        @endif
        @error('code')
        <span class="text-danger">{{$message}}</span>
        @enderror
        <div class="form-text">The code is identifier for the "value"</div>
    </div>
    <div>
        <label for="type_inp" class="form-label">Type</label>
        {{ html()->select('type', ['' => 'Global'] + $transTypes)
        ->id('type_inp' . $current->language)->class('form-control')->data('lang', 1) }}
    </div>
</div>
<button type="submit" class="btn btn-primary me-4">Submit</button>
<a href="{{ cms_route('translations.index') }}" class="btn btn-label-secondary">Cancel</a>

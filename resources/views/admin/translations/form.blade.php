<div class="row g-6 mb-6">
    <div>
        <label for="value_inp" class="form-label">Value</label>
        {{ html()->text('value')->id('value_inp' . $current->language)->class('form-control') }}
        @error('value')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <div class="form-text">The value field will be displayed to public</div>
    </div>
    <div>
        <label for="code_inp" class="form-label">Code</label>
        {{ html()->text('code')->id('code_inp' . $current->language)->class('form-control')
        ->ifNotNull($current->code, function ($html) {
            return $html->disabled();
        }) }}
        @error('code')
        <span class="text-danger">{{ $message }}</span>
        @enderror
        <div class="form-text">The code field will be used to display the <mark>value</mark> field</div>
    </div>
    <div>
        <label for="type_inp" class="form-label">Type</label>
        {{ html()->select('type', ['' => 'Global'] + $transTypes)
        ->id('type_inp' . $current->language)->class('form-select') }}
    </div>
</div>
<button type="submit" class="btn btn-primary me-4">Submit</button>
<a href="{{ cms_route('translations.index') }}" class="btn btn-label-secondary">Cancel</a>

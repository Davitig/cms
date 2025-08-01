<div class="row g-6 mb-6">
    <div>
        <label for="title_inp" class="form-label required">Title</label>
        {{ html()->text('title')->id('title_inp')->class('form-control') }}
        @error('title')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="description_inp" class="form-label">Description</label>
        {{ html()->textarea('description')->class('form-control')->rows(2) }}
        @error('description')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label class="switch switch-primary">
            {{ html()->checkbox('main')->id('main_inp')->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Main</span>
        </label>
    </div>
</div>
<div class="d-flex gap-4">
    <button type="submit" class="btn btn-primary">Submit</button>
    @if ($current->id)
        <a href="{{ cms_route('pages.index', [$current->id]) }}" class="btn btn-outline-dark" title="Go to Pages">Pages</a>
    @endif
    <a href="{{ cms_route('menus.index') }}" class="btn btn-label-secondary">Cancel</a>
</div>

<div class="row g-6 mb-6">
    <div>
        <label for="title_inp" class="form-label required">Title</label>
        {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control') }}
        @error('title')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    @ifMainLanguage($current->language)
    <div>
        <label for="slug_inp" class="form-label required">Slug</label>
        {{ html()->text('slug')->id('slug_inp' . $current->language)->class('form-control') }}
        @error('slug')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="image_inp" class="form-label">Image</label>
        <div class="input-group">
            {{ html()->text('image')->id('image_inp' . $current->language)->class('form-control') }}
            <button type="button" class="file-manager-popup btn btn-outline-primary" data-browse="image_inp{{$current->language}}">Browse</button>
        </div>
        @error('image')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    @endifMainLanguage
    <div>
        <label for="description_inp" class="form-label">Description</label>
        {{ html()->textarea('description')->id('description_inp' . $current->language)
        ->class('form-control text-editor')->rows(8) }}
        @error('description')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="content_inp" class="form-label">Content</label>
        {{ html()->textarea('content')->id('content_inp' . $current->language)
        ->class('form-control text-editor')->rows(12) }}
        @error('content')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="meta_title_inp" class="form-label">Meta Title</label>
        {{ html()->text('meta_title')->id('meta_title_inp' . $current->language)->class('form-control') }}
        @error('meta_title')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="meta_desc_inp" class="form-label">Meta Description</label>
        {{ html()->text('meta_desc')->id('meta_desc_inp' . $current->language)->class('form-control') }}
        @error('meta_desc')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    @ifMainLanguage($current->language)
    <div>
        <label class="switch switch-primary">
            {{ html()->checkbox('visible')->id('visible_inp' . $current->language)->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Visible</span>
        </label>
    </div>
    @endifMainLanguage
</div>
<div class="d-flex gap-4">
    <button type="submit" class="btn btn-primary">Submit</button>
    <a href="{{ cms_route('events.index', [$current->collection_id]) }}" class="btn btn-label-secondary">Cancel</a>
</div>

<div class="row g-6 mb-6">
    <div class="col-md-6">
        <label for="title_inp" class="form-label required">Title</label>
        {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control') }}
        @error('title')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="short_title_inp" class="form-label required">Short Title</label>
        {{ html()->text('short_title')->id('short_title_inp' . $current->language)->class('form-control') }}
        @error('short_title')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    @ifMainLanguage($current->language)
    <div>
        <div class="row">
            <div class="col-md-6">
                <label for="slug_inp" class="form-label required">Slug</label>
                {{ html()->text('slug')->id('slug_inp' . $current->language)->class('form-control') }}
                @error('slug')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <label for="type_inp" class="form-label required">Type</label>
        {{ html()->select('type', $types)->id('type_inp' . $current->language)->class('form-select') }}
        @error('type')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div @class(['col-md-6 type-id', 'd-none' => ! old('type_id', $current->type_id) && ! $errors->first('type_id')])>
        <label for="type_id_inp" class="form-label required">{{$current->type_id ? ucfirst($current->type) : ucfirst(old('type', 'Type ID'))}}</label>
        {{ html()->select('type_id', $listableTypes)->id('type_id_inp' . $current->language)->class('form-select') }}
        @error('type_id')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <div class="row">
            <div class="col-md-6">
                <label for="image_inp" class="form-label">Image</label>
                <div class="input-group">
                    {{ html()->text('image')->id('image_inp' . $current->language)->class('form-control') }}
                    <button type="button" class="file-manager-popup btn btn-outline-primary" data-browse="image_inp{{$current->language}}">Browse</button>
                </div>
            </div>
        </div>
    </div>
    @endifMainLanguage
    <div>
        <label for="description_inp" class="form-label">Description</label>
        {{ html()->textarea('description')->id('description_inp' . $current->language)->class('form-control text-editor')->rows(8) }}
    </div>
    <div>
        <label for="content_inp" class="form-label">Content</label>
        {{ html()->textarea('content')->id('content_inp' . $current->language)->class('form-control text-editor')->rows(12) }}
    </div>
    <div>
        <label for="meta_title_inp" class="form-label">Meta Title</label>
        {{ html()->text('meta_title')->id('meta_title_inp' . $current->language)->class('form-control') }}
    </div>
    <div>
        <label for="meta_desc_inp" class="form-label">Meta Description</label>
        {{ html()->text('meta_desc')->id('meta_desc_inp' . $current->language)->class('form-control') }}
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
    <a href="{{ cms_route('pages.index', [$current->menu_id]) }}" class="btn btn-label-secondary">Cancel</a>
</div>

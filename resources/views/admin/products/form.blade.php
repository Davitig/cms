<div class="row">
    <div class="col-lg-8 mb-6">
        <div class="card mb-6">
            <div class="card-header fs-5">Product Information</div>
            <div class="card-body row g-6">
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
                <div>
                    <label for="image_inp" class="form-label">Image</label>
                    <div class="input-group">
                        {{ html()->text('image')->id('image_inp' . $current->language)->class('form-control') }}
                        <button type="button" class="file-manager-popup btn btn-outline-primary" data-browse="image_inp{{$current->language}}">Browse</button>
                    </div>
                </div>
                <div>
                    <label class="switch switch-primary">
                        {{ html()->checkbox('visible')->id('visible_inp' . $current->language)->class('switch-input') }}
                        <span class="switch-toggle-slider"></span>
                        <span class="switch-label">Visible</span>
                    </label>
                </div>
                @endifMainLanguage
            </div>
        </div>
        <div class="card">
            <div class="card-header fs-5">Text</div>
            <div class="card-body row g-6">
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
            </div>
        </div>
    </div>
    @ifMainLanguage($current->language)
    <div class="col-lg-4 mb-6">
        <div class="card">
            <div class="card-header fs-5">Pricing</div>
            <div class="card-body row g-6">
                <div>
                    <label for="price_inp" class="form-label required">Price</label>
                    {{ html()->number('price')->id('price_inp' . $current->language)->class('form-control') }}
                    @error('price')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="quantity_inp" class="form-label required">Quantity</label>
                    {{ html()->number('quantity')->id('quantity_inp' . $current->language)->class('form-control') }}
                    @error('quantity')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <div class="border-top pt-4">
                        <label class="switch switch-primary">
                            {{ html()->checkbox('in_stock')->id('in_stock_inp' . $current->language)->class('switch-input') }}
                            <div class="switch-toggle-slider"></div>
                            <div class="switch-label">In Stock</div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endifMainLanguage
    <div class="d-flex gap-4">
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ cms_route('products.index') }}" class="btn btn-label-secondary">Cancel</a>
    </div>
</div>

<div class="row g-6 mb-6">
    <div>
        <label for="title_inp" class="form-label required">Title</label>
        {{ html()->text('title')->id('title_inp')->class('form-control') }}
        @error('title')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <label for="type_inp" class="form-label required">Type</label>
        {{ html()->select('type', cms_config('listable.collections.types'))
        ->id('type_inp')->class('form-select')
        ->ifNotNull($current->id, function ($html) {
            return $html->attribute('disabled');
        }) }}
        @error('type')
        <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="admin_order_by_inp" class="form-label required">Admin Order By</label>
        {{ html()->select('admin_order_by', $orderBy = cms_config('listable.collections.order_by'))
        ->id('admin_order_by_inp')->class('form-select') }}
        @error('admin_order_by')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="web_order_by_inp" class="form-label required">Web Order By</label>
        {{ html()->select('web_order_by', $orderBy)
        ->id('web_order_by_inp')->class('form-select') }}
        @error('web_order_by')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="admin_sort_inp" class="form-label required">Admin Sort</label>
        {{ html()->select('admin_sort', $sort = cms_config('listable.collections.sort'))
        ->id('admin_sort_inp')->class('form-select') }}
        @error('admin_sort')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="web_sort_inp" class="form-label required">Web Sort</label>
        {{ html()->select('web_sort', $sort)
        ->id('web_sort_inp')->class('form-select') }}
        @error('web_sort')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="admin_per_page_inp" class="form-label required">Admin Per Page</label>
        {{ html()->number('admin_per_page')->id('admin_per_page_inp')->class('form-control') }}
        @error('admin_per_page')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="web_per_page_inp" class="form-label required">Web Per Page</label>
        {{ html()->number('web_per_page')->id('web_per_page_inp')->class('form-control') }}
        @error('web_per_page')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="admin_max_similar_type_inp" class="form-label required">Admin Max Similar Type</label>
        {{ html()->number('admin_max_similar_type')->id('admin_max_similar_type_inp')->class('form-control') }}
        @error('admin_max_similar_type')
        <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label for="description_inp" class="form-label">Description</label>
        {{ html()->textarea('description')->id('description_inp')->class('form-control')->rows(3) }}
    </div>
</div>
<div class="d-flex gap-4">
    <button type="submit" class="btn btn-primary">Submit</button>
    @if ($current->id)
        <a href="{{ cms_route($current->type . '.index', [$current->id]) }}" class="btn btn-outline-dark" title="Go to {{ ucfirst($current->type) }}">
            {{ucfirst($current->type)}}
        </a>
    @endif
    <a href="{{ cms_route('collections.index') }}" class="btn btn-label-secondary">Cancel</a>
</div>

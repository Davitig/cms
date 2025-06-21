<div class="row g-4 mb-4">
    <div>
        <label for="title_inp" class="form-label">Name</label>
        {{ html()->text('title')->id('title_inp')->class('form-control') }}
        @if ($error = $errors->first('title'))
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
    <div>
        <label for="description_inp" class="form-label">Description</label>
        {{ html()->textarea('description')->class('form-control')->rows(3) }}
    </div>
    <div>
        <label class="switch switch-primary">
            {{ html()->checkbox('main')->id('main_inp')->class('switch-input') }}
            <span class="switch-toggle-slider">
            <span class="switch-on"></span>
            <span class="switch-off"></span>
        </span>
            <span class="switch-label">Main</span>
        </label>
    </div>
</div>
<button type="submit" class="btn btn-primary me-4">Submit</button>
<a href="{{ cms_route('menus.index') }}" class="btn btn-label-secondary">Cancel</a>

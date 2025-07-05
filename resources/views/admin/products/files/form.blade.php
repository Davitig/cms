<div class="row">
    <div class="mb-6">
        <label for="title_inp" class="form-label required">Title</label>
        {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control')->autofocus() }}
    </div>
    <div class="mb-6">
        <label for="file_inp" class="form-label required">File</label>
        <div class="input-group input-group-merge">
            {{ html()->text('file')->id('file_inp' . $current->language)->class('form-control') }}
            <button type="button" class="file-manager-popup btn btn-outline-primary" data-browse="file_inp{{$current->language}}">Browse</button>
        </div>
    </div>
    @ifMainLanguage($current->language)
    <div class="mb-6">
        <label class="switch switch-primary">
            {{ html()->checkbox('visible')->id('visible_inp')->class('switch-input') }}
            <span class="switch-toggle-slider"></span>
            <span class="switch-label">Visible</span>
        </label>
    </div>
    @endifMainLanguage
</div>
<div class="d-flex gap-4">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

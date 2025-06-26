<div class="modal-body">
    <div class="row gy-4">
        <div>
            <label for="title_inp" class="form-label">Title</label>
            {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control')->autofocus() }}
        </div>
        <div>
            <label for="file_inp" class="form-label">File</label>
            <div class="input-group input-group-merge">
                {{ html()->text('file')->id('file_inp' . $current->language)->class('form-control') }}
                <button type="button" class="popup-file-manager btn btn-outline-primary" data-browse="file_inp{{$current->language}}">Browse</button>
            </div>
        </div>
        <div>
            <label class="switch switch-primary">
                {{ html()->checkbox('visible')->id('visible_inp')->class('switch-input') }}
                <span class="switch-toggle-slider"></span>
                <span class="switch-label">Visible</span>
            </label>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Submit</button>
</div>

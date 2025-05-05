<div class="col-md-12">
    <div class="form-group">
        <label class="control-label required">Title:</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control')->autofocus() }}
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label required">Image:</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-image"></i></span>
            {{ html()->text('file')->id('file_inp' . $current->language)->class('form-control')->data('lang', 1) }}
            <div class="input-group-btn popup" data-browse="file{{$current->language}}">
                <span class="btn btn-info">Browse</span>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label">Link:</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link"></i></span>
            {{ html()->text('link')->id('link_inp' . $current->language)->class('form-control') }}
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label">Short description:</label>
        {{ html()->textarea('description')->id('description_inp' . $current->language)->class('form-control')->rows(2) }}
    </div>
</div>
<div class="col-md-12">
    <div class="form-group">
        <label class="control-label">Visible:</label>
        {{ html()->checkbox('visible')->id('visible_inp' . $current->language)
        ->class('iswitch iswitch-secondary')->data('lang', 1) }}
    </div>
</div>
<button type="button" class="btn btn-md btn-white" data-dismiss="modal">{{trans('general.close')}}</button>
<button type="submit" class="btn btn-md btn-secondary">{{trans('general.save')}}</button>

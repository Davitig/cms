<div class="modal-body">
    <div class="form-group">
        <label class="col-sm-2 control-label required">Title:</label>
        <div class="col-sm-10">
            {{ html()->text('title')->id('title' . $current->language)->class('form-control')->data('lang', 1) }}
            @if ($error = $errors->first('title'))
            <div class="text-danger">{{$error}}</div>
            @endif
            <div class="desc">The title for the "value". It's visible only for CMS Users</div>
        </div>
    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
        <label class="col-sm-2 control-label required">Code:</label>
        <div class="col-sm-10">
            {{ html()->text('code')->id('code' . $current->language)
            ->class('form-control')
            ->ifNotNull($current->code, function ($html) {
                return $html->readonly();
            }) }}
            @if ($error = $errors->first('code'))
                <div class="text-danger">{{$error}}</div>
            @endif
            <div class="desc">The code is the identifier for the "value" (it's not changeable after creation!)</div>
        </div>
    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
        <label class="col-sm-2 control-label required">Value:</label>
        <div class="col-sm-10">
            {{ html()->text('value')->id('value' . $current->language)->class('form-control') }}
            @if ($error = $errors->first('value'))
            <div class="text-danger">{{$error}}</div>
            @endif
            <div class="desc">Value contains the translated text that will be displayed on the website</div>
        </div>
    </div>

    <div class="form-group-separator"></div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Type:</label>
        <div class="col-sm-10">
            {{ html()->select('type', ['' => 'Global'] + $transTypes)
            ->id('type' . $current->language)->class('form-control')->data('lang', 1) }}
            <div class="desc">The type that will separate translations.</div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save</button>
</div>

<input type="hidden" name="code" value="{{$current->code}}">
<div class="trans-body clearfix">
    <div class="trans-form-group">
        <label class="trans-control-label required">Title:</label>
        <div>
            {{ html()->text('title')->id('title' . $current->language)->class('trans-form-control')->data('lang', 1) }}
            @if ($error = $errors->first('title'))
                <div class="text-danger">{{$error}}</div>
            @endif
            <div class="desc">The title for the "value." It's visible only for CMS Users</div>
        </div>
    </div>
    <div class="trans-form-group">
        <label class="trans-control-label required">Value:</label>
        <div class="">
            {{ html()->text('value')->id('value' . $current->language)->class('trans-form-control') }}
            @if ($error = $errors->first('value'))
                <div class="text-danger">{{$error}}</div>
            @endif
            <div class="desc">Value contains the translated text that will be displayed on the website</div>
        </div>
    </div>
    <div class="trans-form-group">
        <label class="trans-control-label">Type:</label>
        <div class="">
            {{ html()->select('type', ['' => 'Global'] + $transTypes)
            ->id('type' . $current->language)->class('trans-form-control')->data('lang', 1) }}
        </div>
    </div>
</div>
<div class="trans-modal-footer">
    <button type="button" class="trans-btn trans-btn-default" data-dismiss="trans-modal">Close</button>
    <button type="submit" class="trans-btn trans-btn-primary">Save</button>
</div>

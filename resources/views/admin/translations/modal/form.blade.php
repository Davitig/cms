<input type="hidden" name="code" value="{{ $current->code }}" id="code{{ $current->language }}">
<div class="trans-body clearfix">
    <div class="trans-form-group">
        <label class="trans-control-label required">Value:</label>
        <div>
            {{ html()->text('value')->id('value_inp' . $current->language)->class('trans-form-control') }}
            @if ($error = $errors->first('value'))
                <div class="text-danger">{{$error}}</div>
            @endif
            <div class="form-text">The value field will be displayed to public</div>
        </div>
    </div>
    <div class="trans-form-group">
        <label class="trans-control-label">Type:</label>
        <div class="">
            {{ html()->select('type', ['' => 'Global'] + $transTypes)
            ->id('type_inp' . $current->language)->class('trans-form-control')->data('lang', 1) }}
        </div>
    </div>
</div>
<div class="trans-modal-footer">
    <button type="button" class="trans-btn trans-btn-default" data-dismiss="trans-modal">Close</button>
    <button type="submit" class="trans-btn trans-btn-primary">Save</button>
</div>

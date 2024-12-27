<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-6">
        {{ html()->text('title')->id('title' . $current->language)->class('form-control')->data('lang', 1) }}
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
        <div class="desc">The title for the "value." It's visible only for CMS Users</div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('value')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Value:</label>
    <div class="col-sm-6">
        {{ html()->text('value')->id('value' . $current->language)->class('form-control') }}
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
        <div class="desc">The value contains the translated text that will be displayed on the website</div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('code')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Code:</label>
    <div class="col-sm-6">
        {{ html()->text('code')->id('code' . $current->language)
        ->class('form-control')
        ->ifNotNull($current->code, function ($html) {
            return $html->isReadonly();
        }) }}
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
        <div class="desc">The code is the identifier for the "value" (Not changeable after creation)</div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Type:</label>
    <div class="col-sm-6">
        {{ html()->select('type', ['' => 'Global'] + $transTypes)
        ->id('type' . $current->language)->class('form-control')->data('lang', 1) }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <div class="col-sm-10 btn-action pull-right">
        <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ $submit }}">
            <i class="fa fa-{{ $icon }}"></i>
            <span>{{ trans('general.save') }}</span>
        </button>
        <a href="{{ cms_route('translations.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

<div class="form-group{{($error = $errors->first('full_name')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Full name:</label>
    <div class="col-sm-10">
        {{ html()->text('full_name')->id('full_name')->class('form-control')->placeholder('E.g. English') }}
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('short_name')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Short name:</label>
    <div class="col-sm-10">
        {{ html()->text('short_name')->id('short_name')->class('form-control')->placeholder('E.g. en') }}
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('language')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Language Code:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon">
                <img id="lang-img" src="{{ asset('assets/libs/images/flags/'.$current->language.'.png') }}" width="28" height="18" alt="flag">
                {{-- <i class="{{icon_type('languages')}}"></i> --}}
            </span>
            {{ html()->text('language')->id('language')->class('form-control')->data('mask', 'aa')->placeholder('E.g. en') }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <div class="col-sm-10 btn-action pull-right">
        <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ $submit }}">
            <i class="fa fa-{{ $icon }}"></i>
            <span>{{ trans('general.save') }}</span>
        </button>
        <a href="{{ cms_route('languages.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

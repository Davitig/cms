<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('title')->id('title_inp')->class('form-control') }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description:</label>
    <div class="col-lg-6 col-sm-10">
        {{ html()->textarea('description')->class('form-control')->rows(3) }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label class="col-sm-6 control-label">Main:</label>
            <div class="col-sm-6">
                {{ html()->checkbox('main')->id('main_inp')
                ->class('iswitch iswitch-secondary')->data('lang', 1) }}
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <div class="col-sm-10 btn-action pull-right">
        <button type="submit" class="btn btn-secondary btn-icon-standalone" title="{{ $submit }}">
            <i class="fa fa-{{ $icon }}"></i>
            <span>{{ $submit }}</span>
        </button>
        @if ($current->id)
            <a href="{{ cms_route('pages.index', [$current->id]) }}" class="btn btn-info btn-icon-standalone" title="{{ trans('general.pages') }}">
                <i class="{{icon_type('pages')}}"></i>
                <span>{{ trans('general.pages') }}</span>
            </a>
        @endif
        <a href="{{ cms_route('menus.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

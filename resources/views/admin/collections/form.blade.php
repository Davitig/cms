<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('title')->id('title')->class('form-control') }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('type')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Type:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-ellipsis-h"></i></span>
            {{ html()->select('type', cms_config('collections.types'))->id('type')->class('form-control select')
            ->ifNotNull($current->id, function ($html) {
                return $html->attribute('disabled');
            }) }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('admin_order_by')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Admin order by:</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-list-ol"></i></span>
                    {{ html()->select('admin_order_by', $orderBy = cms_config('collections.order_by'))
                    ->id('admin_order_by')->class('form-control select') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('web_order_by')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Web order by:</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-list-ol"></i></span>
                    {{ html()->select('web_order_by', $orderBy)->id('web_order_by')->class('form-control select') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('admin_sort')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Admin sort:</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sort"></i></span>
                    {{ html()->select('admin_sort', cms_config('collections.sort'))->id('admin_sort')->class('form-control select') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('web_sort')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Web sort:</label>
            <div class="col-sm-8">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sort"></i></span>
                    {{ html()->select('web_sort', cms_config('collections.sort'))->class('form-control select') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('admin_per_page')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Admin per page:</label>
            <div class="col-sm-8">
                <div id="admin_per_page" class="input-group spinner" data-step="1" data-min="1" data-max="50">
                    <div class="input-group-btn">
                        <span class="btn btn-info" data-type="decrement">-</span>
                    </div>
                    {{ html()->text('admin_per_page')->class('form-control text-center')->isReadonly() }}
                    <div class="input-group-btn">
                        <span class="btn btn-info" data-type="increment">+</span>
                    </div>
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group{{($error = $errors->first('web_per_page')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-4 control-label required">Web per page:</label>
            <div class="col-sm-8">
                <div id="web_per_page" class="input-group spinner" data-step="1" data-min="1" data-max="50">
                    <div class="input-group-btn">
                        <span class="btn btn-info" data-type="decrement">-</span>
                    </div>
                    {{ html()->text('web_per_page')->class('form-control text-center')->isReadonly() }}
                    <div class="input-group-btn">
                        <span class="btn btn-info" data-type="increment">+</span>
                    </div>
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description:</label>
    <div class="col-sm-10">
        {{ html()->textarea('description')->class('form-control')->rows(3)->placeholder('Short description') }}
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
            <a href="{{ cms_route($current->type . '.index', [$current->id]) }}" class="btn btn-info btn-icon-standalone" title="{{ trans('general.'.$current->type) }}">
                <i class="{{icon_type($current->type)}}"></i>
                <span>{{ucfirst($current->type)}}</span>
            </a>
        @endif
        <a href="{{ cms_route('collections.index') }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>
@push('body.bottom')
    <script type="text/javascript">
        $(function() {
            $('select.select').select2({
                placeholder: 'Select type...',
                allowClear: true
            }).on('select2-open', function() {
                // Adding Custom Scrollbar
                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
            });
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/js/select2/select2-bootstrap.css') }}">
    <script src="{{ asset('assets/libs/js/select2/select2.min.js') }}"></script>
@endpush

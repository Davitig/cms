<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('title')->id('title' . $current->language)->class('form-control') }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group{{($error = $errors->first('slug')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Slug:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-link"></i></span>
            {{ html()->text('slug')->id('slug' . $current->language)->class('form-control')->data('lang', 1) }}
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
            {{ html()->select('type', deep_collection('galleries.types'))
            ->id('type' . $current->language)->class('form-control select')
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
                    {{ html()->select('admin_order_by', deep_collection('galleries.order_by'))
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
                    {{ html()->select('web_order_by', deep_collection('galleries.order_by'))
                    ->id('web_order_by')->class('form-control select') }}
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
                    {{ html()->select('admin_sort', deep_collection('galleries.sort'))
                    ->id('admin_sort' . $current->language)->class('form-control select')->data('lang', 1) }}
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
                    {{ html()->select('web_sort', deep_collection('galleries.sort'))
                    ->id('web_sort' . $current->language)->class('form-control select')->data('lang', 1) }}
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
                <div id="admin_per_page{{$current->language}}" class="input-group spinner" data-type="general" data-step="1" data-min="1" data-max="50">
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
                <div id="web_per_page{{$current->language}}" class="input-group spinner" data-type="general" data-step="1" data-min="1" data-max="50">
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
    <label class="col-sm-2 control-label">Image:</label>
    <div class="col-sm-6">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-image"></i></span>
            {{ html()->text('image')->id('image' . $current->language)->class('form-control')->data('lang', 1) }}
            <div class="input-group-btn popup" data-browse="image{{$current->language}}">
                <span class="btn btn-info">Browse</span>
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description:</label>
    <div class="col-sm-10">
        {{ html()->textarea('description')->id('description' . $current->language)->class('form-control')->rows(5) }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Title:</label>
    <div class="col-sm-10">
        {{ html()->text('meta_title')->id('meta_title' . $current->language)->class('form-control') }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Description:</label>
    <div class="col-sm-10">
        {{ html()->text('meta_desc')->id('meta_desc' . $current->language)->class('form-control') }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label class="col-sm-6 control-label">Visible:</label>
            <div class="col-sm-6">
                {{ html()->checkbox('visible')->id('visible' . $current->language)
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
            <span>{{ trans('general.save') }}</span>
        </button>
        @if ($current->id)
            <a href="{{ cms_route($current->type . '.index', [$current->id]) }}" class="btn btn-info btn-icon-standalone" title="{{ trans('general.'.$current->type) }}">
                <i class="{{icon_type($current->type)}}"></i>
                <span>{{ucfirst($current->type)}}</span>
            </a>
        @endif
        <a href="{{ cms_route('galleries.index', [$current->collection_id]) }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

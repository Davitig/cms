<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {{ html()->text('title')->id('title_inp' . $current->language)->class('form-control') }}
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
            {{ html()->text('slug')->id('slug_inp' . $current->language)->class('form-control')->data('lang', 1) }}
        </div>
        @if ($error)
            <span class="text-danger">{{$error}}</span>
        @endif
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group{{($error = $errors->first('price')) ? ' validate-has-error' : '' }}">
            <label class="col-lg-4 col-sm-2 control-label required">Price:</label>
            <div class="col-lg-8 col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-money-bill"></i></span>
                    {{ html()->text('price')->id('price_inp' . $current->language)->class('form-control') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group{{($error = $errors->first('quantity')) ? ' validate-has-error' : '' }}">
            <label class="col-lg-4 col-sm-2 control-label required">Quantity:</label>
            <div class="col-lg-8 col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-sort-numeric-desc"></i></span>
                    {{ html()->text('quantity')->id('quantity_inp' . $current->language)->class('form-control')->data('lang', 1) }}
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
    <div class="col-lg-8">
        <div class="form-group">
            <label class="col-lg-3 col-sm-2 control-label">Image:</label>
            <div class="col-lg-9 col-sm-10">
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                        {{ html()->text('image')->id('image_inp' . $current->language)->class('form-control')->data('lang', 1) }}
                    </div>
                    <div class="input-group-btn popup" data-browse="image_inp{{$current->language}}">
                        <span class="btn btn-info">Browse</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description:</label>
    <div class="col-sm-10">
        {{ html()->textarea('description')->id('description_inp' . $current->language)
        ->class('form-control text-editor')->rows(5) }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Content:</label>
    <div class="col-sm-10">
        {{ html()->textarea('content')->id('content_inp' . $current->language)
        ->class('form-control text-editor')->rows(10) }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Title:</label>
    <div class="col-sm-10">
        {{ html()->text('meta_title')->id('meta_title_inp' . $current->language)->class('form-control') }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Description:</label>
    <div class="col-sm-10">
        {{ html()->text('meta_desc')->id('meta_desc_inp' . $current->language)->class('form-control') }}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label class="col-sm-6 control-label">Visible:</label>
            <div class="col-sm-6">
                {{ html()->checkbox('visible')->id('visible_inp')->class('iswitch iswitch-secondary')->data('lang', 1) }}
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
        <a href="{{ cms_route('products.index', [$current->collection_id]) }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

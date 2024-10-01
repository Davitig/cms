<div class="form-group{{($error = $errors->first('title')) ? ' validate-has-error' : '' }}">
    <label class="col-sm-2 control-label required">Title:</label>
    <div class="col-sm-10">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-header"></i></span>
            {!! Form::text('title', null, [
                'id' => 'title' . $current->language,
                'class' => 'form-control',
            ]) !!}
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
            {!! Form::text('slug', null, [
                'id' => 'slug' . $current->language,
                'class' => 'form-control',
                'data-lang' => 1
            ]) !!}
        </div>
        @if ($error)
        <span class="text-danger">{{$error}}</span>
        @endif
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
                        {!! Form::text('image', null, [
                            'id' => 'image' . $current->language,
                            'class' => 'form-control',
                            'data-lang' => 1
                        ]) !!}
                    </div>
                    <div class="input-group-btn popup" data-browse="image{{$current->language}}">
                        <span class="btn btn-info">Browse</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label class="col-lg-4 col-sm-2 control-label">Created at:</label>
            <div class="col-lg-8 col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!! Form::text('created_at', null, [
                        'id' => 'created_at' . $current->language,
                        'class' => 'form-control datetimepicker',
                        'data-format' => 'yyyy-mm-dd',
                    ]) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Description:</label>
    <div class="col-sm-10">
        {!! Form::textarea('description', null, [
            'id' => 'description' . $current->language,
            'class' => 'form-control text-editor',
            'rows' => '5'
        ]) !!}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Content:</label>
    <div class="col-sm-10">
        {!! Form::textarea('content', null, [
            'id' => 'content' . $current->language,
            'class' => 'form-control text-editor',
            'rows' => '10'
        ]) !!}
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Title:</label>
    <div class="col-sm-10">
        {!! Form::text('meta_title', null, [
            'id' => 'meta_title' . $current->language,
            'class' => 'form-control',
        ]) !!}
        <div class="desc">Title for search engines. It is best to keep meta title less then 150 or 160 characters.</div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="form-group">
    <label class="col-sm-2 control-label">Meta Description:</label>
    <div class="col-sm-10">
        {!! Form::text('meta_desc', null, [
            'id' => 'meta_desc' . $current->language,
            'class' => 'form-control',
        ]) !!}
        <div class="desc">Description for search engines. It is best to keep meta descriptions less then 150 or 160 characters.</div>
    </div>
</div>

<div class="form-group-separator"></div>

<div class="row">
    <div class="col-xs-4">
        <div class="form-group">
            <label class="col-sm-6 control-label">Visible:</label>
            <div class="col-sm-6">
                {!! Form::checkbox('visible', null, null, [
                    'id' => 'visible' . $current->language,
                    'class' => 'iswitch iswitch-secondary',
                    'data-lang' => 1
                ]) !!}
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
        <a href="{{ cms_route('articles.index', [$current->collection_id]) }}" class="btn btn-blue btn-icon-standalone" title="{{ trans('general.back') }}">
            <i class="fa fa-arrow-left"></i>
            <span>{{ trans('general.back') }}</span>
        </a>
    </div>
</div>

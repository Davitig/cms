@extends('admin.app')
@section('content')
<div class="page-title">
    <div class="title-env">
        <h1 class="title">
            <i class="{{$icon = icon_type('pages')}}"></i>
            Pages
        </h1>
        <p class="description">Management of the pages</p>
    </div>
    <div class="breadcrumb-env">
        <ol class="breadcrumb bc-1">
            <li>
                <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
            </li>
            <li>
                <a href="{{ cms_route('menus.index') }}"><i class="{{icon_type('menus')}}"></i>Menus</a>
            </li>
            <li class="active">
                <i class="{{$icon}}"></i>
                <strong>Pages</strong>
            </li>
        </ol>
    </div>
</div>
<div class="clearfix">
    @include('admin._partials.items.lang')
    <ul id="listable-types" class="nav nav-tabs col-xs-6 right-aligned">
        @if ($current->collection_type)
            <li class="listable">
                <a href="{{cms_route($current->collection_type.'.index', [$current->type_id])}}">
                    <span class="visible-xs"><i class="{{$iconType = icon_type($current->collection_type)}}"></i></span>
                    <div class="hidden-xs">
                        <i class="{{$iconType}}"></i> {{ucfirst($current->collection_type)}}
                    </div>
                </a>
            </li>
        @endif
        @if (array_key_exists($current->type, cms_pages('explicit')))
            <li class="modules">
                <a href="{{cms_route($current->type.'.index')}}">
                    <span class="visible-xs"><i class="{{$iconType = icon_type($current->type)}}"></i></span>
                    <div class="hidden-xs">
                        <i class="{{$iconType}}"></i> {{ucfirst($current->type)}}
                    </div>
                </a>
            </li>
        @endif
        <li>
            <a href="{{cms_route('files.index', ['pages', $current->id])}}">
                <span class="visible-xs"><i class="{{$iconFiles = icon_type('files')}}"></i></span>
                <div class="hidden-xs">
                    <i class="{{$iconFiles}}"></i> {{trans('general.files')}}
                </div>
            </a>
        </li>
    </ul>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">Edit page</h2>
        <div class="panel-options">
            <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
            </a>
        </div>
        <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->id])}}" class="pull-right padr">Add sub page</a>
        <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->parent_id])}}" class="pull-right padr">Add more page</a>
    </div>
    <div class="panel-body">
        <div class="tab-content">
        @foreach ($items as $current)
            <div class="tab-pane{{language() != $current->language ? '' : ' active'}}" id="item-{{$current->language}}">
                {{ html()->modelForm($current,
                    'put', cms_route('pages.update', [
                        $current->menu_id, $current->id
                    ], is_multilanguage() ? $current->language : null)
                )->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                ->data('lang', $current->language)->open() }}
                    @include('admin.pages.form', [
                        'submit'        => trans('general.update'),
                        'submitAndBack' => trans('general.update_n_back'),
                        'icon'          => 'save'
                    ])
                {{ html()->form()->close() }}
            </div>
        @endforeach
        </div>
    </div>
</div>
@push('body.bottom')
<script type="text/javascript">
    $('form.ajax-form').on('ajaxFormSuccess', function (form, data) {
        var listableTypes = $('#listable-types');
        $('.listable', listableTypes).remove();
        $('.modules', listableTypes).remove();

        if (data.input.typeHtml !== undefined) {
            listableTypes.prepend(data.input.typeHtml);
        }
    });
</script>
@endpush
@include('admin.pages.scripts')
@endsection

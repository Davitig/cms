@extends('admin.app')
@section('content')
<div class="page-title">
    <div class="title-env">
        <h1 class="title">
            <i class="{{$icon = icon_type('pages')}}"></i>
            {{$menu->title}}
        </h1>
        <p class="description">{{ $menu->description }}</p>
    </div>
    <div class="breadcrumb-env">
        <ol class="breadcrumb bc-1">
            <li>
                <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
            </li>
            <li>
                <a href="{{ cms_route('menus.index') }}"><i class="{{$iconMenus = icon_type('menus')}}"></i>Menus</a>
            </li>
            <li class="active">
                <i class="{{$icon}}"></i>
                <strong>{{$menu->title}}</strong>
            </li>
        </ol>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title">List of all pages</h2>
        <div class="panel-options">
            <a href="{{cms_route('menus.edit', [$menu->id])}}">
                <i class="fa fa-gear"></i>
            </a>
            <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
            </a>
        </div>
    </div>
    <div class="panel-body">
        <a href="{{ cms_route('pages.create', [$menu->id]) }}" class="btn btn-secondary btn-icon-standalone">
            <i class="{{$icon}}"></i>
            <span>{{ trans('general.create') }}</span>
        </a>
        <button id="save-tree" data-token="{{csrf_token()}}" class="btn btn-secondary btn-icon-standalone dn" disabled>
            <i><b class="icon-var fa-save"></b></i>
            <span>{{ trans('general.save') }}</span>
        </button>
        <div id="items">
            <ul id="nestable-list" class="uk-nestable" data-uk-nestable>
            @foreach ($items as $item)
                <li id="item{{ $item->id }}" class="item{{$item->collapse ? ' uk-collapsed' : ''}}" data-id="{{ $item->id }}" data-pos="{{ $item->position }}">
                    <div class="uk-nestable-item clearfix">
                        <div class="row">
                            <div class="col-sm-7 col-xs-10">
                                <div class="uk-nestable-handle pull-left"></div>
                                <div data-nestable-action="toggle"></div>
                                <div class="list-label"><a href="{{ $editUrl = cms_route('pages.edit', [$menu->id, $item->id]) }}">{{ $item->short_title }}</a></div>
                            </div>
                            <div class="col-sm-5 col-xs-2">
                                <div class="btn-action togglable pull-right">
                                    <div class="btn btn-gray item-id disabled">#{{$item->id}}</div>
                                    <a href="{{web_url($item->slug)}}" class="link btn btn-white" title="Go to page" data-slug="{{$item->slug}}" target="_blank">
                                        <span class="fa fa-link"></span>
                                    </a>
                                    <a href="#" class="transfer btn btn-white" title="Transfer to another menu" data-id="{{$item->id}}">
                                        <span class="{{$iconMenus}}"></span>
                                    </a>
                                    {{ html()->form('post', cms_route('pages.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                        <button type="submit" class="btn btn-{{$item->visible ? 'white' : 'gray'}}" title="{{trans('general.visibility')}}">
                                            <span class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></span>
                                        </button>
                                    {{ html()->form()->close() }}
                                    <a href="{{ cms_route('pages.files.index', [$item->id]) }}" class="btn btn-{{$item->has_file ? 'turquoise' : 'white'}}" title="{{trans('general.files')}}">
                                        <span class="{{icon_type('files')}}"></span>
                                    </a>
                                    <span title="{{ucfirst($type = $item->collection_type ?: $item->type)}}">
                                        <a href="{{$typeUrl = (array_key_exists($item->type, cms_config('pages.explicit')) ?
                                        cms_route($item->type . '.index') : (array_key_exists($item->type, cms_config('pages.implicit'))
                                        ? cms_route($item->collection_type . '.index', [$item->type_id]) : '#'))}}" class="btn btn-{{$typeUrl == '#' ? 'white disabled' : 'info'}}">
                                            <span class="{{icon_type($type, 'fa fa-file-text-o')}}"></span>
                                        </a>
                                    </span>
                                    <a href="{{ cms_route('pages.create', [$menu->id, 'parent_id' => $item->id]) }}" class="btn btn-secondary" title="{{trans('general.create')}}">
                                        <span class="fa fa-plus"></span>
                                    </a>
                                    <a href="{{ $editUrl }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                        <span class="fa fa-edit"></span>
                                    </a>

                                    {{ html()->form('delete', cms_route('pages.destroy', [$menu->id, $item->id]))->class('form-delete')->open() }}
                                        <button type="submit" class="btn btn-danger" title="{{trans('general.delete')}}"{{has_model_sub_items($item) ? ' disabled' : ''}}>
                                            <span class="fa fa-trash"></span>
                                        </button>
                                    {{ html()->form()->close() }}
                                </div>
                                <a href="#" class="btn btn-primary btn-toggle pull-right">
                                    <span class="fa fa-toggle-left"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @include('admin.pages.sub_items')
                </li>
            @endforeach
            </ul>
        </div>
    </div>
</div>
@push('body.bottom')
@include('admin._scripts.transfer', ['route' => cms_route('pages.transfer', [$menu->id]), 'column' => 'menu_id', 'list' => $menus, 'id' => $menu->id, 'recursive' => true])
<script type="text/javascript">
$(function() {
    positionable('{{ cms_route('pages.updatePosition') }}');

    // Update pages URL recursively, after position update
    $('#save-tree').on('positionSaved', function() {
        updateUrl($('#nestable-list').find('> li'), '{{web_url()}}');
    });

    // Collapse parent pages
    $('#items').on('click', '[data-nestable-action]', function() {
        var id = $(this).closest('li').data('id');
        var input = {'id':id, '_method':'put', '_token':"{{csrf_token()}}"};
        $.post("{{cms_route('pages.collapse')}}", input, function() {}, 'json')
        .fail(function(xhr) {
            alert(xhr.responseText);
        });
    });
});
</script>
<script src="{{ asset('assets/libs/js/uikit/js/uikit.min.js') }}"></script>
<script src="{{ asset('assets/libs/js/uikit/js/addons/nestable.min.js') }}"></script>
@endpush
@endsection

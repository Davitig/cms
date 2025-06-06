@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('events')}}"></i>
                {{ $parent->type }}
            </h1>
            <p class="description">{{ $parent->description }}</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{ cms_route('collections.index') }}"><i class="{{$iconParent = icon_type('collections')}}"></i>Collections</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>{{ $parent->title }}</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9 pull-right has-sidebar">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h2 class="panel-title">{{ $parent->title }}</h2>
                    <div class="panel-options">
                        <a href="{{cms_route('collections.edit', [$parent->id])}}">
                            <i class="fa fa-gear"></i>
                        </a>
                        <a href="#" data-toggle="panel">
                            <span class="collapse-icon">&ndash;</span>
                            <span class="expand-icon">+</span>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <a href="{{ cms_route('events.create', [$parent->id]) }}" class="btn btn-secondary btn-icon-standalone">
                        <i class="{{$icon}}"></i>
                        <span>{{ trans('general.create') }}</span>
                    </a>
                    <button id="save-tree" data-token="{{csrf_token()}}" class="btn btn-secondary btn-icon-standalone dn" disabled>
                        <i><b class="fa fa-save"></b></i>
                        <span>{{ trans('general.update_position') }}</span>
                    </button>
                    <div id="items">
                        <ul id="nestable-list" class="uk-nestable" data-uk-nestable="{maxDepth:1}">
                            @foreach ($items as $item)
                                <li id="item{{ $item->id }}" class="item" data-id="{{ $item->id }}" data-pos="{{$item->position}}">
                                    <div class="uk-nestable-item clearfix">
                                        <div class="row">
                                            <div class="col-sm-7 col-xs-10">
                                                @if ($parent->admin_order_by == 'position')
                                                    <div class="uk-nestable-handle pull-left"></div>
                                                @endif
                                                <div class="list-label"><a href="{{ $editUrl = cms_route('events.edit', [$parent->id, $item->id]) }}">{{ $item->title }}</a></div>
                                            </div>
                                            <div class="col-sm-5 col-xs-2">
                                                <div class="btn-action toggleable pull-right">
                                                    <div class="btn btn-gray item-id">#{{$item->id}}</div>
                                                    <a href="#" class="transfer btn btn-white" title="Transfer to another collection" data-id="{{$item->id}}">
                                                        <span class="{{$iconParent}}"></span>
                                                    </a>
                                                    {{ html()->form('put', cms_route('events.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                                    <button type="submit" class="btn btn-{{$item->visible ? 'white' : 'gray'}}" title="{{trans('general.visibility')}}">
                                                        <span class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></span>
                                                    </button>
                                                    {{ html()->form()->close() }}
                                                    <a href="{{ cms_route('events.files.index', [$item->id]) }}" class="btn btn-{{$item->files_exists ? 'turquoise' : 'white'}}" title="{{trans('general.files')}}">
                                                        <span class="{{icon_type('files')}}"></span>
                                                    </a>
                                                    <a href="{{ $editUrl }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                                        <span class="fa fa-edit"></span>
                                                    </a>
                                                    {{ html()->form('delete', cms_route('events.destroy', [$parent->id, $item->id]))->class('form-delete')->open() }}
                                                    <button type="submit" class="btn btn-danger" title="{{trans('general.delete')}}">
                                                        <span class="fa fa-trash"></span>
                                                    </button>
                                                    {{ html()->form()->close() }}
                                                </div>
                                                <a href="#" class="btn btn-primary btn-toggle pull-right">
                                                    <span class="fa fa-arrow-left"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        {!! $items->links() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 content-sidebar pull-left">
            <a href="{{cms_route('collections.create', ['type' => $parent->type])}}" class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right">
                <i class="{{$iconParent}}"></i>
                <span>Add Collection</span>
            </a>
            <ul class="list-unstyled bg">
                @foreach ($parentSimilar as $item)
                    <li{!!$item->id != $parent->id ? '' : ' class="active"'!!}>
                        <a href="{{ cms_route($item->type . '.index', [$item->id]) }}">
                            <i class="fa fa-folder{{$item->id != $parent->id ? '' : '-open'}}"></i>
                            <span>{{$item->title}}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @push('body.bottom')
        @include('admin._scripts.transfer', ['route' => cms_route('events.transfer', [$parent->id]), 'column' => 'collection_id', 'list' => $parentSimilar, 'parentId' => $parent->id])
        <script type="text/javascript">
            $(function() {
                @if ($parent->admin_order_by == 'position')
                positionable('{{ cms_route('events.updatePosition') }}', '{{$parent->admin_sort}}', '{{request('page', 1)}}', '{{$items->hasMorePages()}}');
                @endif
            });
        </script>
        <script src="{{ asset('assets/libs/js/uikit/js/uikit.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/addons/nestable.min.js') }}"></script>
    @endpush
@endsection

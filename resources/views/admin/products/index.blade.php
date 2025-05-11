@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('products')}}"></i>
                Products
            </h1>
            <p class="description">Management of the products</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Products</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">Products</h2>
            <div class="panel-options">
                <a href="#">
                    <i class="fa fa-gear"></i>
                </a>
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <a href="{{ cms_route('products.create') }}" class="btn btn-secondary btn-icon-standalone">
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
                                        <div class="uk-nestable-handle pull-left"></div>
                                        @if ($item->image)
                                            <img src="{{glide($item->image, 'products_tmb')}}" width="45" height="45" class="pull-left" alt="{{$item->title}}">
                                        @endif
                                        <div class="list-label"><a href="{{ $editUrl = cms_route('products.edit', [$item->id]) }}">{{ $item->title }}</a></div>
                                    </div>
                                    <div class="col-sm-5 col-xs-2">
                                        <div class="btn-action toggleable pull-right">
                                            <div class="btn btn-gray item-id">#{{$item->id}}</div>
                                            {{ html()->form('put', cms_route('products.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                            <button type="submit" class="btn btn-{{$item->visible ? 'white' : 'gray'}}" data-id="{{ $item->id }}" title="{{trans('general.visibility')}}">
                                                <span class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></span>
                                            </button>
                                            {{ html()->form()->close() }}
                                            <a href="{{ cms_route('products.files.index', [$item->id]) }}" class="btn btn-{{$item->files_exists ? 'turquoise' : 'white'}}" title="{{trans('general.files')}}">
                                                <span class="{{icon_type('files')}}"></span>
                                            </a>
                                            <a href="{{ $editUrl }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            {{ html()->form('delete', cms_route('products.destroy', [$item->id]))->class('form-delete')->open() }}
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
    @push('body.bottom')
        <script type="text/javascript">
            $(function() {
                positionable('{{ cms_route('products.updatePosition') }}', 'desc', '{{request('page', 1)}}', '{{$items->hasMorePages()}}');
            });
        </script>
        <script src="{{ asset('assets/libs/js/uikit/js/uikit.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/addons/nestable.min.js') }}"></script>
    @endpush
@endsection

@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('videos')}}"></i>
                <span data-substr-limit>{{ $parent->title }}</span>
            </h1>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{ cms_route('collections.index') }}"><i class="{{icon_type('collections')}}"></i>Collections</a>
                </li>
                <li>
                    <a href="{{ cms_route('galleries.index', [$parent->collection_id]) }}"><i class="{{icon_type('galleries')}}"></i>Galleries</a>
                </li>
                <li class="active">
                    <strong>{{ $parent->title }}</strong>
                </li>
            </ol>
        </div>
    </div>
    <section class="gallery-env">
        <div class="row">
            <div class="col-sm-9 gallery-right">
                <div class="album-header">
                    <h2>Videos</h2>
                    <ul class="album-options list-unstyled list-inline">
                        <li>
                            <a href="{{cms_route('galleries.edit', [$parent->collection_id, $parent->id])}}">
                                <i class="fa fa-gear"></i>
                            </a>
                        </li>
                        <li>
                            <input type="checkbox" class="cbr" id="select-all">
                            <label>Select all</label>
                        </li>
                        <li>
                            <a href="#" data-modal="add">
                                <i class="{{$icon}}"></i>
                                Add Video
                            </a>
                        </li>
                        @if ($parent->admin_order_by == 'position')
                            <li>
                                <a href="#" data-action="sort">
                                    <i class="fa fa-arrows"></i>
                                    Re-order
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="#" data-modal="multiselect">
                                <i class="fa fa-edit"></i>
                                Edit
                            </a>
                        </li>
                        <li>
                            <a href="#" data-delete="multiselect">
                                <i class="fa fa-trash"></i>
                                Trash
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="album-sorting-info">
                    <div class="album-sorting-info-inner clearfix">
                        <a href="#" id="save-tree" data-token="{{csrf_token()}}" class="btn btn-secondary btn-xs btn-single btn-icon btn-icon-standalone pull-right" data-action="sort">
                            <i class="fa fa-save"></i>
                            <span>Save Current Order</span>
                        </a>
                        <i class="fa fa-arrows-alt"></i>
                        Drag images to sort them
                    </div>
                </div>
                <ul id="nestable-list" class="album-images list-unstyled row" data-insert="{{$parent->admin_sort == 'desc' ? 'prepend' : 'append'}}" data-uk-nestable="{maxDepth:1}">
                    @foreach($items as $item)
                        <li id="item{{$item->id}}" data-id="{{$item->id}}" data-pos="{{$item->position}}"
                            data-url="{{cms_route('videos.edit', [$item->gallery_id, $item->id])}}" class="item col-lg-4 col-md-6 col-sm-6 col-xs-12">
                            <div class="album-image">
                                <a href="#" class="thumb embed-responsive embed-responsive-16by9" data-modal="edit">
                                    <iframe src="{{get_youtube_embed($item->file)}}" frameborder="0" allowfullscreen class="embed-responsive-item"></iframe>
                                </a>
                                <a href="#" class="name">
                                    <span class="title">{{$item->title}}</span>
                                    <em>{{$item->created_at->format('d F Y')}}</em>
                                </a>
                                <div class="image-options">
                                    <div class="select-item dib">
                                        <input type="checkbox" data-id="{{$item->id}}" class="cbr">
                                    </div>
                                    <a href="#" data-url="{{cms_route('videos.visibility', [$item->id])}}" class="visibility" title="{{trans('general.visibility')}}">
                                        <i class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></i>
                                    </a>
                                    <a href="#" data-modal="edit" title="{{trans('general.edit')}}"><i class="fa fa-pencil"></i></a>
                                    <a href="#" data-delete="{{cms_route('videos.destroy', [$item->gallery_id, $item->id])}}" data-id="{{$item->id}}" title="{{trans('general.delete')}}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                                <div class="btn-action"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                {!! $items->links() !!}
            </div>
            <div class="col-sm-3 gallery-left">
                <div class="gallery-sidebar">
                    <a href="{{cms_route('galleries.create', [$parent->collection_id, 'type' => $parent->type])}}" class="btn btn-block btn-secondary btn-icon btn-icon-standalone btn-icon-standalone-right">
                        <i class="{{$icon}}"></i>
                        <span>Add gallery</span>
                    </a>
                    <ul class="list-unstyled">
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
        </div>
    </section>
    @push('body.bottom')
        @include('admin._scripts.album', [
            'routeCreate' => cms_route('videos.create', [$parent->id]),
            'routePosition' => cms_route('videos.updatePosition'),
            'sort' => $parent->admin_sort,
            'currentPage' => $items->currentPage(),
            'lastPage' => $items->lastPage()
        ])
        <script src="{{ asset('assets/libs/js/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/uikit.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/addons/nestable.min.js') }}"></script>
    @endpush
@endsection

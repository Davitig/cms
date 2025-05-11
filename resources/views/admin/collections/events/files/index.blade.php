@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('files')}}"></i>
                <span data-substr-limit>{{ $foreignModel->title }}</span>
            </h1>
            <p class="description">Management of the files</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li>
                    <a href="{{ cms_route('events.index', [$foreignModel->collection_id]) }}">
                        <i class="{{icon_type('events')}}"></i> Events
                    </a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Files</strong>
                </li>
            </ol>
        </div>
    </div>
    <ul class="nav nav-tabs nav-tabs-justified">
        <li>
            <a href="{{cms_route('events.edit', [$foreignModel->collection_id, $foreignModel->id])}}">
                <img src="{{ asset('assets/libs/images/flags/'.language()->active().'.png') }}" width="23" height="13" alt="{{language()->active()}}">
                {{-- <span class="visible-xs">{{$language->language}}</span> --}}
                <span class="hidden-xs">Event</span>
            </a>
        </li>
        <li class="active">
            <a href="#" data-toggle="tab">
                <span class="visible-xs"><i class="{{$icon}}"></i></span>
                <div class="hidden-xs">
                    <i class="{{$icon}}"></i> {{trans('general.files')}}
                </div>
            </a>
        </li>
    </ul>
    <section class="gallery-env files">
        <div class="row">
            <div class="col-sm-12 gallery-right">
                <div class="album-header">
                    <h2>Files</h2>
                    <ul class="album-options list-unstyled list-inline">
                        <li>
                            <input type="checkbox" class="cbr" id="select-all">
                            <label>Select all</label>
                        </li>
                        <li>
                            <a href="#" data-modal="add">
                                <i class="{{$icon}}"></i>
                                Add File
                            </a>
                        </li>
                        <li>
                            <a href="#" data-action="sort">
                                <i class="fa fa-arrows"></i>
                                Re-order
                            </a>
                        </li>
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
                        <a href="#" id="save-tree" data-token="{{csrf_token()}}"
                           class="btn btn-secondary btn-xs btn-single btn-icon btn-icon-standalone pull-right"
                           data-action="sort">
                            <i class="fa fa-save"></i>
                            <span>Save Current Order</span>
                        </a>
                        <i class="fa fa-arrows-alt"></i>
                        Drag images to sort them
                    </div>
                </div>
                <ul id="nestable-list" class="album-images list-unstyled row" data-insert="prepend"
                    data-uk-nestable="{maxDepth:1}" id="items">
                    @foreach($items as $item)
                        <li id="item{{$item->id}}" data-id="{{$item->id}}" data-pos="{{$item->position}}"
                            data-url="{{cms_route('events.files.edit', [$item->event_id, $item->id])}}" class="item col-md-2 col-sm-4 col-xs-6">
                            <div class="album-image">
                                <a href="#" class="thumb" data-modal="edit">
                                    @if (in_array($ext = pathinfo($item->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                        <img src="{{$item->file}}" class="img-responsive" alt="{{$item->title}}">
                                    @elseif(! empty($ext))
                                        <img src="{{asset('assets/libs/images/file-ext-icons/'.$ext.'.png')}}"
                                             class="img-responsive" alt="{{$item->title}}">
                                    @else
                                        <img src="{{asset('assets/libs/images/file-ext-icons/www.png')}}"
                                             class="img-responsive" alt="{{$item->title}}">
                                    @endif
                                </a>
                                <a href="#" class="name">
                                    <span class="title">{{$item->title}}</span>
                                    <em>{{$item->created_at->format('d F Y')}}</em>
                                </a>
                                <div class="image-options">
                                    <div class="select-item dib">
                                        <input type="checkbox" data-id="{{$item->id}}" class="cbr">
                                    </div>
                                    <a href="#" data-url="{{cms_route('events.files.visibility', [$item->id])}}" class="visibility" title="{{trans('general.visibility')}}">
                                        <i class="fa fa-eye{{$item->visible ? '' : '-slash'}}"></i>
                                    </a>
                                    <a href="#" data-modal="edit" title="{{trans('general.edit')}}"><i class="fa fa-pencil"></i></a>
                                    <a href="#" data-delete="{{cms_route('events.files.destroy', [$item->event_id, $item->id])}}" data-id="{{$item->id}}" title="{{trans('general.delete')}}">
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
        </div>
    </section>
    @push('body.bottom')
        @include('admin._scripts.album', [
            'routeCreate' => cms_route('events.files.create', [$foreignModel->id]),
            'routePosition' => cms_route('events.files.updatePosition'),
            'sort' => 'desc',
            'currentPage' => $items->currentPage(),
            'lastPage' => $items->lastPage()
        ])
        <script src="{{ asset('assets/libs/js/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/uikit.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/uikit/js/addons/nestable.min.js') }}"></script>
    @endpush
@endsection

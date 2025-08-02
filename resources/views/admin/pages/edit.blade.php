@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard.index') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ cms_route('menus.index') }}">Menus</a>
            </li>
            <li class="breadcrumb-item active">Pages</li>
        </ol>
    </nav>
    @include('admin.-partials.lang.tabs')
    <div class="card">
        <div class="card-header header-elements flex-column flex-md-row align-items-start row-gap-4">
            <div class="fs-5">Pages</div>
            <div class="card-header-elements ms-md-auto flex-row-reverse flex-md-row gap-4">
                <a href="{{ cms_route('pages.create', [$current->menu_id]) }}">
                    <i class="icon-base fa fa-plus icon-xs"></i>
                    <span>Add New Record</span>
                </a>
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    Go to
                </button>
                <ul class="menu-list dropdown-menu">
                    <li>
                        <a href="{{ cms_route('pages.files.index', [$current->id]) }}" class="dropdown-item">Files</a>
                    </li>
                    @if (array_key_exists($current->type, cms_pages('listable.collections')) ||
                         array_key_exists($current->type, cms_pages('extended')))
                        <li class="extended">
                            <a href="{{cms_route($current->type.'.index', [$current->type_id])}}" class="dropdown-item">
                                {{ucfirst($current->type)}}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                @php($activeLang = language()->queryStringOrActive())
                @includeWhen(! $activeLang, 'admin.-alerts.resource-requires-lang')
                @foreach($items as $current)
                    <div id="item-{{ $current->language }}" @class(['tab-pane', 'show active' => $current->language == $activeLang || ! $activeLang])>
                        @includeWhen(! $current->language_id, 'admin.-alerts.resource-without-lang')
                        {{ html()->modelForm($current, 'put', cms_route('pages.update', [$current->menu_id, $current->id], $current->language))
                        ->id('pages-form')->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                        @include('admin.pages.form')
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@push('body.bottom')
    <script type="text/javascript">
        $('form#pages-form').on('ajaxFormDone', function (e, res) {
            let menuList = $('.menu-list');
            $('.extended', menuList).remove();

            if (res?.data?.typeHtml !== undefined) {
                menuList.append(res?.data?.typeHtml);
            }
        });
    </script>
@endpush
@include('admin.pages.scripts')

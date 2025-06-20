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
    <div class="tabs-vertical-env custom">
        <ul class="nav nav-tabs nav-tabs-justified">
            @include('admin._partials.lang.tabs')
        </ul>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">Edit page</h2>
                <div class="panel-options">
                    <a href="#" data-toggle="panel">
                        <span class="collapse-icon">&ndash;</span>
                        <span class="expand-icon">+</span>
                    </a>
                </div>
                <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->id])}}"
                   class="pull-right padr">Add sub page</a>
                <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->parent_id])}}"
                   class="pull-right padr">Add more</a>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    @php($activeLang = request('lang', language()->active()))
                    @foreach ($items as $current)
                        <div class="tab-pane{{$activeLang == $current->language ? ' active' : ''}}"
                             id="item-{{$current->language}}">
                            {{ html()->modelForm($current, 'put', cms_route('pages.update', [
                                $current->menu_id, $current->id
                            ], language()->containsMany() ? ($current->language ?: $activeLang) : null))
                            ->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                            ->data('lang', $current->language)->open() }}
                            @include('admin.pages.form', [
                                'submit' => trans('general.update'),
                                'icon' => 'save'
                            ])
                            {{ html()->form()->close() }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <ul id="form-tabs" class="nav tabs-vertical custom">
            <li>
                <a href="{{cms_route('pages.files.index', $current->id)}}">
                    <i class="{{icon_type('files')}}"></i> {{trans('general.files')}}
                </a>
            </li>
            @if (array_key_exists($current->type, cms_pages('listable.collections')) ||
                 array_key_exists($current->type, cms_pages('extended')))
                <li class="extended">
                    <a href="{{cms_route($current->type.'.index', [$current->type_id])}}">
                        <i class="{{icon_type($current->type)}}"></i> {{ucfirst($current->type)}}
                    </a>
                </li>
            @endif
        </ul>
    </div>
@endsection
@push('body.bottom')
    <script type="text/javascript">
        $('form.ajax-form').on('ajaxFormSuccess', function (e, res) {
            let extendedTypes = $('#form-tabs');
            $('.extended', extendedTypes).remove();

            if (res?.data?.typeHtml !== undefined) {
                extendedTypes.append(res?.data?.typeHtml);
            }
        });
    </script>
@endpush
@include('admin.pages.scripts')

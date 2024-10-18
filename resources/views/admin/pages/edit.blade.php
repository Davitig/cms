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
    <ul id="form-tabs" class="nav nav-tabs nav-tabs-justified">
        @include('admin._partials.items.lang')
        <li>
            <a href="{{cms_route('pages.files.index', $current->id)}}">
                <span class="visible-xs"><i class="{{$iconFiles = icon_type('files')}}"></i></span>
                <div class="hidden-xs">
                    <i class="{{$iconFiles}}"></i> {{trans('general.files')}}
                </div>
            </a>
        </li>
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
            <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->id])}}" class="pull-right padr">Add sub page</a>
            <a href="{{cms_route('pages.create', [$current->menu_id, 'parent_id' => $current->parent_id])}}" class="pull-right padr">Add more</a>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                @php($languages = languages())
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
                    @unset($languages[$current->language])
                @endforeach
                @foreach ($languages as $value)
                    <div class="tab-pane{{language() != $value['language'] ? '' : ' active'}}" id="item-{{$value['language']}}">
                        {{ html()->form('post', cms_route('pages.cloneLanguage', [$current->id], $value['language']))
                            ->class('form-horizontal')->data('lang', $value['language'])->open() }}
                        <button class="btn btn-info btn-icon btn-icon-standalone btn-lg">
                            <i class="{{$icon}}"></i>
                            <span>Create {{$value['full_name']}}</span>
                        </button>
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @push('body.bottom')
        <script type="text/javascript">
            $('form.ajax-form').on('ajaxFormSuccess', function (form, data) {
                var listableTypes = $('#form-tabs');
                $('.listable', listableTypes).remove();
                $('.modules', listableTypes).remove();

                if (data.input.typeHtml !== undefined) {
                    listableTypes.append(data.input.typeHtml);
                }
            });
        </script>
    @endpush
    @include('admin.pages.scripts')
@endsection

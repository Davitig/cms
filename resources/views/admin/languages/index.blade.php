@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('languages')}}"></i>
                Languages
            </h1>
            <p class="description">Management of the languages</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Languages</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">List of all languages</h2>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                    <span class="collapse-icon">&ndash;</span>
                    <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <a href="{{ cms_route('languages.create') }}" class="btn btn-secondary btn-icon-standalone">
                <i class="{{$icon}}"></i>
                <span>{{ trans('general.create') }}</span>
            </a>
            <strong class="text-black pull-right">Drag and Drop to sort the languages order</strong>
            <table id="items" class="table table-striped">
                <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Short Name</th>
                    <th>Language Code</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="sortable">
                @foreach ($items as $item)
                    <tr id="item{{$item->id}}" class="item" data-id="{{$item->id}}">
                        <td class="full-name pointer">
                            <img src="{{ asset('assets/libs/images/flags/'.$item->language.'.png') }}" width="30" height="20" alt="{{$item->language}} Flag">
                            <span>{{ $item->full_name }}</span>
                        </td>
                        <td>{{ $item->short_name }}</td>
                        <td>{{ $item->language }}</td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="btn-action">
                                <a href="{{ cms_route('languages.edit', [$item->id]) }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                    <span class="fa fa-edit"></span>
                                </a>
                                {{ html()->form('delete', cms_route('languages.destroy', [$item->id]))->class('form-delete')->open() }}
                                <button type="submit" class="btn btn-danger" title="{{trans('general.delete')}}">
                                    <span class="fa fa-trash"></span>
                                </button>
                                {{ html()->form()->close() }}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @push('body.bottom')
        <script type="text/javascript">
            $(function() {
                let langInUrl = {{(int) language_in_url()}};
                let activeLangSelector = $('.language-switcher > a img');
                let langMenuSelector = $('.dropdown-menu.languages');
                let sortableSelector = $('#sortable');
                sortableSelector.sortable();
                sortableSelector.on('sortupdate', function () {
                    let ids = [];
                    let input = {data: []};
                    $.each(sortableSelector.sortable('toArray', {attribute: 'data-id'}), function (i, id) {
                        ids[id] = i;
                        input.data.push({id: id});
                    });
                    input['_method'] = 'put';
                    input['_token'] = '{{csrf_token()}}';
                    $.post('{{cms_route('languages.updatePosition')}}', input, function () {
                        toastr['success']('Positions has been updated successfully');
                        if (! langInUrl) {
                            let flag = sortableSelector.children(':first').find('.full-name img').attr('src');
                            activeLangSelector.attr('src', flag);
                        }
                        let langItems = langMenuSelector.children('li').each(function (i, e) {
                            $(e).data('pos', ids[parseInt($(e).data('id'))]);
                        }).sort(function (a, b) {
                            return parseInt($(a).data('pos')) - parseInt($(b).data('pos'));
                        });
                        langMenuSelector.html('');
                        langItems.each(function (i, e) {
                            langMenuSelector.append(e);
                        });
                    }, 'json').fail(function (xhr) {
                        alert(xhr.responseText);
                    });
                });
            });
        </script>
        <script src="{{ asset('assets/libs/js/jquery-ui/jquery-ui.min.js') }}"></script>
        <script src="{{ asset('assets/libs/js/jquery-ui/jquery.ui.touch-punch.min.js') }}"></script>
    @endpush
@endsection

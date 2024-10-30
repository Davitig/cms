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
            <table id="items" class="table table-striped">
                <thead>
                <tr>
                    <th>Main</th>
                    <th>Full Name</th>
                    <th>Short Name</th>
                    <th>Language Code</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr id="item{{$item->id}}" class="item">
                        <td>
                            <input type="radio" name="main" data-id="{{$item->id}}" class="cbr cbr-success"{{$item->main ? ' checked' : ''}}>
                        </td>
                        <td class="full-name{{ $item->language == language() ? ' text-bold text-primary' : '' }}">
                            <img src="{{ asset('assets/libs/images/flags/'.$item->language.'.png') }}" width="30" height="20" alt="{{$item->full_name}}">
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
            $(function () {
                var items = $('#items');
                items.on('click', '.cbr-radio', function() {
                    var id = $(this).find('input').data('id');
                    var data = {'id':id, '_token':"{{csrf_token()}}"};
                    $(this).closest('table').find('.full-name').removeClass('text-bold text-primary');
                    $(this).closest('td').siblings('.full-name').addClass('text-bold text-primary');
                    $.post('{{cms_route('languages.setMain')}}', data, function() {
                    }, 'json').fail(function(xhr) {
                        alert(xhr.responseText);
                    });
                });
                items.on('deleteFormSuccess', function (e) {
                    if ($(e.target).closest('td').siblings('.text-bold').length) {
                        $(e.target).closest('tr.item').siblings().first()
                            .find('.full-name').addClass('text-bold text-primary');
                    }
                });
            });
        </script>
    @endpush
@endsection

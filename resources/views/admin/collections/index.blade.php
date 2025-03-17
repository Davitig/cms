@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{$icon = icon_type('collections')}}"></i>
                Collections
            </h1>
            <p class="description">Management of the collections</p>
        </div>
        <div class="breadcrumb-env">
            <ol class="breadcrumb bc-1">
                <li>
                    <a href="{{ cms_url('/') }}"><i class="fa fa-dashboard"></i>Dashboard</a>
                </li>
                <li class="active">
                    <i class="{{$icon}}"></i>
                    <strong>Collections</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title">List of all collections</h2>
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
            <a href="{{ cms_route('collections.create') }}" class="btn btn-secondary btn-icon-standalone">
                <i class="{{$icon}}"></i>
                <span>{{ trans('general.create') }}</span>
            </a>
            <table id="items" class="table stacktable table-striped table-bordered">
                <thead>
                <tr class="replace-inputs">
                    <th>Name</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>ID</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr id="item{{$item->id}}" class="item">
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->type }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->id }}</td>
                        <td>
                            <div class="btn-action">
                                <a href="{{ cms_route($item->type . '.index', [$item->id]) }}" class="btn btn-info" title="{{trans('general.'.$item->type)}}">
                                    <span class="{{icon_type($item->type)}}"></span>
                                </a>
                                <a href="{{ cms_route('collections.edit', [$item->id]) }}" class="btn btn-orange" title="{{trans('general.edit')}}">
                                    <span class="fa fa-edit"></span>
                                </a>
                                {{ html()->form('delete', cms_route('collections.destroy', $item->id))->class('form-delete')->open() }}
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
            {!! $items->links() !!}
        </div>
    </div>
@endsection

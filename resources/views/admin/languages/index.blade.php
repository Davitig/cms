@extends('admin.app')
@section('content')
    <nav class="mb-6 ps-1" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ cms_route('dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Languages</li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-header header-elements">
            <div class="fs-5 me-2">
                Languages
                <span class="badge badge-center rounded-pill bg-label-primary ms-2">{{ $items->total() }}</span>
            </div>
            <div class="card-header-elements ms-auto">
                <span class="badge badge-outline-secondary rounded-pill me-4">Drag and Drop to sort the language order</span>
                <a href="{{ cms_route('languages.create') }}" class="btn btn-primary">
                    <i class="icon-base fa fa-plus icon-xs me-sm-1"></i>
                    <span>Add New Record</span>
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($routesAreCached)
                <div class="alert alert-outline-info" role="alert">
                    Routes are cached. Any language changes will not take effect until the route cache is cleared or refreshed
                </div>
            @endif
            <div class="visibility-alert alert alert-outline-danger{{ $visibleLangCount ? ' d-none' : '' }}" role="alert">
                Website is in maintenance mode when there is no visible language
            </div>
            <div id="items" class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Main</th>
                        <th>Full Name</th>
                        <th>Short Name</th>
                        <th>Language Code</th>
                        <th>Visibility</th>
                        <th>ID</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse ($items as $item)
                        <tr id="item{{$item->id}}" class="item" data-id="{{$item->id}}">
                            <td>
                                <input type="radio" name="main" class="form-check-input" data-id="{{$item->id}}"{{$item->main ? ' checked' : ''}}>
                            </td>
                            <td>
                                <img src="{{ asset('assets/default/img/flags/' . $item->language . '.png') }}" width="25" height="18" class="flag-img me-2" alt="{{$item->language}}">
                                <span>{{ $item->full_name }}</span>
                            </td>
                            <td>{{ $item->short_name }}</td>
                            <td>{{ $item->language }}</td>
                            <td>
                                {{ html()->form('put', cms_route('languages.visibility', [$item->id]))->id('visibility' . $item->id)->class('visibility')->open() }}
                                <button type="submit" class="dropdown-item" title="{{trans('general.visibility')}}">
                                    <i class="icon-base fa fa-eye{{$item->visible ? '' : '-slash'}} icon-sm text-primary me-1_5"></i>
                                </button>
                                {{ html()->form()->close() }}
                            </td>
                            <td>{{ $item->id }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="icon-base fa fa-ellipsis-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a href="{{ cms_route('languages.edit', [$item->id]) }}" class="dropdown-item">
                                            <i class="icon-base fa fa-edit me-1"></i>
                                            Edit
                                        </a>
                                        {{ html()->form('delete', cms_route('languages.destroy', [$item->id]))->class('form-delete')->open() }}
                                        <button type="submit" class="dropdown-item">
                                            <i class="icon-base fa fa-trash me-1"></i>
                                            Delete
                                        </button>
                                        {{ html()->form()->close() }}
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No Result</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $items->links() }}
@endsection
@push('body.bottom')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            let langSelected = {{(int) language()->mainIsActive()}};
            let activeLangSelector = $('.language-switcher > a img');
            let langMenuSelector = $('.dropdown-languages');
            new Sortable(document.getElementById('sortable'), {
                animation: 150,
                store: {
                    // Called onEnd (when the item is dropped).
                    set: function (sortable) {
                        let ids = [];
                        let input = {data: []};
                        $.each(sortable.toArray(), function (i, id) {
                            ids[i] = id;
                            input.data.push({id: id});
                        });
                        input['_method'] = 'put';
                        input['_token'] = '{{csrf_token()}}';
                        $.post('{{cms_route('languages.updatePosition')}}', input, function () {
                            notyf('Positions has been updated successfully');
                            // set the first language in navbar if there is no main language selected
                            if (! langSelected) {
                                activeLangSelector.attr('src', $(sortable.el.children[0]).find('.flag-img').attr('src'));
                            }
                            // sort languages in navbar
                            let langItems = langMenuSelector.children('li').sort(function (a, b) {
                                return ids.indexOf(a.dataset.id) - ids.indexOf(b.dataset.id);
                            });
                            langMenuSelector.html('');
                            langItems.each(function (i, e) {
                                langMenuSelector.append(e);
                            });
                        }, 'json').fail(function (xhr) {
                            notyf(xhr.statusText, 'error');
                        });
                    }
                }
            });
            // toggle message when there is no visible language
            let visibleLangCount = {{ $visibleLangCount }};
            let langVisibleSelector = $('.visibility-alert');
            $('form.visibility').on('visibilityResponse', function (e, res) {
                visibleLangCount += res?.data ? res.data : -1;
                if (visibleLangCount > 0) {
                    langVisibleSelector.addClass('d-none');
                } else {
                    langVisibleSelector.removeClass('d-none');
                }
            })
            // update the main language in navbar
            $('#items').on('formCheckSuccess', function (res, target) {
                activeLangSelector.attr('src', target.closest('.item').find('.flag-img').attr('src'));
                langSelected = 1;
            });
        });
    </script>
@endpush
@include('admin._scripts.checkbox_xhr', ['url' => cms_route('languages.updateMain')])

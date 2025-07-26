<div class="card mt-6">
    <div class="card-header header-elements">
        <div class="fs-5">CMS User Roles</div>
        <span class="count badge bg-label-primary ms-4">{{ number_format($items->total()) }}</span>
        <div class="card-header-elements ms-auto">
            <a href="{{ cms_route('cms_user_roles.create') }}" class="btn btn-primary">
                <i class="icon-base fa fa-plus icon-xs me-1"></i>
                <span>Add New Record</span>
            </a>
        </div>
    </div>
    <div id="items" class="table-responsive text-nowrap">
        <table class="table table-hover">
            <thead class="table-light">
            <tr>
                <th>Role</th>
                <th>CMS Users</th>
                <th>Access</th>
                <th>Total</th>
                <th>ID</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($items as $item)
                <tr class="item">
                    <td>
                        @if ($item->full_access)
                            <i class="icon-base fa fa-user-check icon-sm me-4"></i>
                        @else
                            <i class="icon-base fa fa-user-{{ $item->permissions_count ? 'clock' : 'lock' }} icon-sm me-4"></i>
                        @endif
                        {{ ucfirst($item->role) }}
                    </td>
                    <td>
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            @foreach($item->cmsUsers as $user)
                                <li title="{{ $user->first_name }} {{ $user->last_name }}" class="avatar pull-up">
                                    <img class="rounded-circle bg-white" src="{{ cms_route('cms_users.photo', [$user->id]) }}" width="40" height="40" alt="Photo">
                                </li>
                            @endforeach
                            @if ($item->cms_users_count - $cmsUserItemsCount = $item->cmsUsers->count())
                                <li class="avatar">
                                    <span
                                        class="avatar-initial rounded-circle pull-up"
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="bottom"
                                        title="{{ $item->cms_users_count - $cmsUserItemsCount }} more">+{{ $item->cms_users_count - $cmsUserItemsCount }}</span>
                                </li>
                            @endif
                        </ul>
                    </td>
                    <td>
                        @if ($item->full_access)
                            <span class="badge bg-label-success">Full Access</span>
                        @else
                            <a href="{{ cms_route('permissions.index', ['role' => $item->id]) }}" class="badge bg-label-{{ $item->permissions_count ? 'warning' : 'danger' }}">
                                Permissions
                            </a>
                        @endif
                    </td>
                    <td>
                        @if ($item->full_access)
                            <i class="icon-base fa-regular fa-circle-check icon-lg text-success"></i>
                        @else
                            <span class="badge rounded-pill badge-outline-{{ $item->permissions_count ? 'warning' : 'danger' }} p-1">
                                {{ $item->permissions_count }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $item->id }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base fa fa-ellipsis-vertical"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ cms_route('cms_user_roles.edit', [$item->id]) }}" class="dropdown-item">
                                    <i class="icon-base fa fa-edit me-1"></i>
                                    Edit
                                </a>
                                {{ html()->form('delete', cms_route('cms_user_roles.destroy', [$item->id]))->class('form-delete')->open() }}
                                <button type="submit" class="dropdown-item">
                                    <i class="icon-base fa fa-trash me-1"></i>
                                    Delete
                                </button>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

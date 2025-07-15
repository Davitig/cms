<div class="card mb-6">
    <div class="card-header header-elements">
        <div class="fs-5">CMS User Roles</div>
        <span class="count badge bg-label-primary ms-4">{{ number_format($items->total()) }}</span>
        <div class="card-header-elements ms-auto">
            <a href="{{ cms_route('cmsUserRoles.create') }}" class="btn btn-primary">
                <i class="icon-base fa fa-plus icon-xs me-1"></i>
                <span>Add New Record</span>
            </a>
        </div>
    </div>
</div>
<div class="row g-6">
    @foreach ($items as $item)
        <div class="item col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-{{ $item->cms_users_count ? '4' : 'auto' }}">
                        <div>Total {{ $item->cms_users_count }} CMS Users</div>
                        @if ($item->cms_users_count)
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                @foreach($item->cms_users as $user)
                                    <li title="{{ $user->first_name }} {{ $user->last_name }}" class="avatar pull-up">
                                        <img class="rounded-circle bg-white" src="{{ cms_route('cmsUsers.photo', [$user->id]) }}" alt="Photo" />
                                    </li>
                                @endforeach
                                @if ($item->cms_users_count - $item->cms_users->count())
                                    <li class="avatar">
                                        <span
                                            class="avatar-initial rounded-circle pull-up"
                                            title="{{ $item->cms_users_count - $item->cms_users->count() }} more">
                                            +{{ $item->cms_users_count - $item->cms_users->count() }}
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="role-heading">
                            <h5 class="mb-1">{{ $item->role }}</h5>
                            <div class="d-flex align-items-center gap-4">
                                <a href="{{ cms_route('cmsUserRoles.edit', [$item->id]) }}">
                                    <span>Edit Role</span>
                                </a>
                                {{ html()->form('delete', cms_route('cmsUserRoles.destroy', [$item->id]))->class('form-delete')->open() }}
                                <button type="submit" class="dropdown-item text-light">
                                    <i class="icon-base fa fa-trash icon-sm"></i>
                                </button>
                                {{ html()->form()->close() }}
                            </div>
                        </div>
                        <div>
                            @if ($item->full_access)
                                <span class="badge bg-label-success">Full Access</span>
                            @else
                                <span class="badge rounded-pill badge-outline-{{ $item->permissions_count ? 'warning' : 'danger' }} p-1">
                                        {{ $item->permissions_count }}
                                    </span>
                                <a href="{{ cms_route('permissions.index', ['role' => $item->id]) }}" class="badge bg-label-{{ $item->permissions_count ? 'warning' : 'danger' }}">
                                    Permissions
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

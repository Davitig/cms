<!-- User -->
<li class="nav-item navbar-dropdown dropdown-user dropdown ms-3">
    <a
        href="javascript:void(0);"
        class="nav-link dropdown-toggle hide-arrow p-0"
        data-bs-toggle="dropdown">
        <div class="avatar avatar-online">
            <img src="{{ $photoUrl = cms_route('cmsUsers.photo', [$userId = auth('cms')->id()]) }}" alt class="rounded-circle">
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
            <a class="dropdown-item mt-0" href="{{ $userUrl = cms_route('cmsUsers.show', [$userId]) }}">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-2">
                        <div class="avatar avatar-online">
                            <img src="{{ $photoUrl }}" alt class="rounded-circle" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ auth('cms')->user()->first_name }} {{ auth('cms')->user()->last_name }}</h6>
                        <small class="text-body-secondary">Admin</small>
                    </div>
                </div>
            </a>
        </li>
        <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
            <a class="dropdown-item" href="{{ $userUrl }}">
                <i class="icon-base fa fa-user-check me-3 icon-md"></i>
                <span class="align-middle">My Profile</span>
            </a>
        </li>
        <li>
            <a href="{{ cms_route('cmsUsers.edit', [$userId]) }}" class="dropdown-item">
                <i class="icon-base fa fa-user-pen me-3 icon-md"></i>
                <span class="align-middle">Account</span>
            </a>
        </li>
        <li>
            <a href="{{ cms_route('cmsUsers.preferences.index', [$userId]) }}" class="dropdown-item">
                <i class="icon-base fa fa-sliders me-3 icon-md"></i>
                <span class="align-middle">Preferences</span>
            </a>
        </li>
        <li>
            <div class="dropdown-divider my-1 mx-n2"></div>
        </li>
        <li>
            <div class="d-grid px-2 pt-2 pb-1">
                <form action="{{cms_route('logout')}}" method="post" class="d-grid">
                    @csrf
                    <button class="btn btn-sm btn-danger d-flex">
                        <small class="align-middle">Logout</small>
                        <i class="icon-base fa fa-sign-out ms-2 icon-14px"></i>
                    </button>
                </form>
            </div>
        </li>
    </ul>
</li>
<!--/ User -->

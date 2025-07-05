@if (isset($input['type']))
    <li class="extended">
        <a href="{{cms_route($input['type'].'.index', [$input['type_id'] ?? null])}}" class="dropdown-item">
            {{ucfirst($input['type'])}}
        </a>
    </li>
@endif

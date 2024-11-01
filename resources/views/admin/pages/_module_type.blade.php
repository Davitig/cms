@if (isset($input['type']))
    <li class="modules">
        <a href="{{cms_route($input['type'].'.index')}}">
            <i class="{{icon_type($input['type'])}}"></i> {{ucfirst($input['type'])}}
        </a>
    </li>
@endif

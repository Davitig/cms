@if (isset($input['type']) && isset($input['type_id']))
<li class="extended">
    <a href="{{cms_route($input['type'].'.index', [$input['type_id']])}}">
        <i class="{{icon_type($input['type'])}}"></i> {{ucfirst($input['type'])}}
    </a>
</li>
@endif

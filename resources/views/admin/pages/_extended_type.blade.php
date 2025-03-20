@if (isset($input['type']))
<li class="extended">
    <a href="{{cms_route($input['type'].'.index', [$input['type_id'] ?? null])}}">
        <i class="{{icon_type($input['type'])}}"></i> {{ucfirst($input['type'])}}
    </a>
</li>
@endif

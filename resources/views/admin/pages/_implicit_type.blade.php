@if (isset($input['page_type']) && isset($input['type_id']))
<li class="listable">
    <a href="{{cms_route($input['page_type'].'.index', [$input['type_id']])}}">
        <i class="{{icon_type($input['page_type'])}}"></i> {{ucfirst($input['page_type'])}}
    </a>
</li>
@endif

@if (isset($input['page_type']) && isset($input['type_id']))
<li class="listable">
    <a href="{{cms_route($input['page_type'].'.index', [$input['type_id']])}}">
        <span class="visible-xs"><i class="{{$iconType = icon_type($input['page_type'])}}"></i></span>
        <div class="hidden-xs">
            <i class="{{$iconType}}"></i> {{ucfirst($input['page_type'])}}
        </div>
    </a>
</li>
@endif

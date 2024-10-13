@if (isset($input['type']))
    <li class="modules">
        <a href="{{cms_route($input['type'].'.index')}}">
            <span class="visible-xs"><i class="{{$iconType = icon_type($input['type'])}}"></i></span>
            <div class="hidden-xs">
                <i class="{{$iconType}}"></i> {{ucfirst($input['type'])}}
            </div>
        </a>
    </li>
@endif

@if ($isMultilang = is_multilanguage())
    @php($languages = languages())
    @foreach ($items as $current)
        <li>
            <a href="{{cms_route($routeName, $params, $current->language)}}">
                <span class="visible-xs">{{$current->language}}</span>
                <span class="hidden-xs">{{language($current->language, 'full_name')}}</span>
            </a>
        </li>
        @unset($languages[$current->language])
    @endforeach
    @foreach ($languages as $value)
        <li>
            <a href="{{cms_route($routeName, $params, $value['language'])}}" class="text-red">
                <span class="visible-xs">{{$value['language']}}</span>
                <span class="hidden-xs">{{$value['full_name']}}</span>
            </a>
        </li>
    @endforeach
@else
    @if ($items->count() <= 1)
        <li>
            <a href="{{cms_route($routeName, $params, $items->first()->language)}}">
                <span class="visible-xs"><i class="fa fa-home"></i></span>
                <span class="hidden-xs">
                    <i class="fa fa-home"></i> General
                </span>
            </a>
        </li>
    @endif
@endif

<ul class="nav nav-tabs col-xs-6">
    @if ($isMultilang = is_multilanguage())
        @foreach ($items as $current)
            <li{!!language() != $current->language ? '' : ' class="active"'!!}>
                <a href="#item-{{$current->language}}" data-toggle="tab">
                    <span class="visible-xs">{{$current->language}}</span>
                    <span class="hidden-xs">{{language($current->language)}}</span>
                </a>
            </li>
        @endforeach
    @else
        @foreach ($items as $current)
            <li class="active">
                <a href="#item-{{$current->language}}" data-toggle="tab">
                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                    <span class="hidden-xs">
                            <i class="fa fa-home"></i> General
                        </span>
                </a>
            </li>
            @break(! $isMultilang && $loop->count > 1)
        @endforeach
    @endif
</ul>

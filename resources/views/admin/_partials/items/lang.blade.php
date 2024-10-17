@if (is_multilanguage())
    @php($languages = languages())
    @foreach ($items as $current)
        <li{!!language() != $current->language ? '' : ' class="active"'!!}>
            <a href="#item-{{$current->language}}" data-toggle="tab">
                <img src="{{ asset('assets/libs/images/flags/'.$current->language.'.png') }}" width="30" height="20" alt="flag">
                {{-- <span class="visible-xs">{{$current->language}}</span> --}}
                <span class="hidden-xs">{{language($current->language, 'full_name')}}</span>
            </a>
        </li>
        @unset($languages[$current->language])
    @endforeach
    @foreach ($languages as $value)
        <li>
            <a href="#item-{{$value['language']}}" data-toggle="tab" class="text-red">
                <img src="{{ asset('assets/libs/images/flags/'.$value['language'].'.png') }}" width="30" height="20" alt="flag">
                {{-- <span class="visible-xs">{{$value['language']}}</span> --}}
                <span class="hidden-xs">{{$value['full_name']}}</span>
            </a>
        </li>
    @endforeach
@else
    @if ($items->count() <= 1)
        <li class="active">
            <a href="#item-{{$items->first()->language}}" data-toggle="tab">
                <span class="visible-xs"><i class="fa fa-home"></i></span>
                <span class="hidden-xs">
                    <i class="fa fa-home"></i> General
                </span>
            </a>
        </li>
    @endif
@endif

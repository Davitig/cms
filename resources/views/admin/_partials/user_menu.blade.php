@if ($userRouteAccess('sitemap.xml.store'))
    <li class="dropdown hover-line">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Sitemap XML Generator">
            <i class="fa fa-sitemap"></i>
        </a>
        <ul class="dropdown-menu notifications">
            <li class="top">
                <p class="small">
                    Update the XML sitemap when you change the URLs.
                </p>
            </li>
            <li>
                <ul class="dropdown-menu-list list-unstyled ps-scrollbar">
                    <li class="active">
                        <a href="{{asset('sitemap.xml')}}" target="_blank">
                            <i class="fa fa-sitemap icon-color-green"></i>
                            <span class="line">
                                  <strong>Last update</strong>
                                </span>
                            <span class="line small time">
                                    Date: <span class="sm-date">{{$sitemapXmlTime ? date('d F Y', $sitemapXmlTime) : 'N/A'}}</span>
                                </span>
                            <span class="line small time">
                                    Time: <span class="sm-time">{{$sitemapXmlTime ? date('H:i', $sitemapXmlTime) : 'N/A'}}</span>
                                </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="external">
                <form action="{{cms_route('sitemap.xml.store')}}" method="POST">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-link w-100">
                        <a class="external-btn">
                            <span class="fa fa-sitemap padr"></span>
                            <span class="sm-status">{{$sitemapXmlTime ? 'Update' : 'Create'}} now!</span>
                        </a>
                    </button>
                </form>
            </li>
        </ul>
    </li>
@endif
@if ($userRouteAccess('calendar.index'))
    <li class="dropdown hover-line">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-calendar"></i>
            @if ($countCalendarEvents = count($calendarEvents))
                <span class="badge badge-orange">{{$countCalendarEvents}}</span>
            @endif
        </a>
        @if ($countCalendarEvents)
            <ul class="dropdown-menu notifications">
                <li class="top">
                    <p class="small">
                        You have <strong>{{$countCalendarEvents}}</strong> upcoming event{{$countCalendarEvents > 1 ? 's' : ''}}.
                    </p>
                </li>
                <li>
                    <ul class="dropdown-menu-list list-unstyled ps-scrollbar">
                        @foreach ($calendarEvents as $item)
                            <li {!!($date = date('d F Y', strtotime($item->start))) == date('d F Y') ? ' class="active"' : ''!!}>
                                <a href="{{cms_route('calendar.index', ['gotoDate' => $item->start])}}">
                                    <i class="fa fa-calendar-o icon-color-{{$item->color}}"></i>
                                    <span class="line">
                                            <strong>{{$item->title}}</strong>
                                        </span>
                                    <span class="line small time">
                                            Date: {{$date}}
                                        </span>
                                    @if ($item->time_start)
                                        <span class="line small time">
                                                Time: {{date('H:i', strtotime($date))}}
                                            </span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="external">
                    <a href="{{cms_route('calendar.index')}}">
                        <span>View calendar</span>
                        <i class="fa fa-external-link"></i>
                    </a>
                </li>
            </ul>
        @endif
    </li>
@endif

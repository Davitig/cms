@extends('admin.app')
@section('content')
    <div class="page-title">
        <div class="title-env">
            <h1 class="title">
                <i class="{{icon_type('dashboard')}}"></i>
                Dashboard
            </h1>
            <p class="description">The main page of the cms</p>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="xe-widget xe-counter xe-counter-red"  data-count=".num" data-from="0" data-to="{{$menusTotal}}" data-duration="2" data-easing="true" data-delay="1">
                <a href="{{$userRouteAccess('menus.index') ? cms_route('menus.index') : '#'}}" class="xe-icon">
                    <i class="{{icon_type('menus')}}"></i>
                </a>
                <div class="xe-label">
                    <strong class="num">{{$menusTotal}}</strong>
                    <span>Total menus</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="xe-widget xe-counter" data-count=".num" data-from="0" data-to="{{$pagesTotal}}" data-duration="3">
                <a href="{{is_null($mainPage) ? ($userRouteAccess('menus.index') ? cms_route('menus.index') : '#') : ($userRouteAccess('pages.index') ? cms_route('pages.index', [$mainPage->id]) : '#')}}" class="xe-icon">
                    <i class="{{icon_type('pages')}}"></i>
                </a>
                <div class="xe-label">
                    <strong class="num">{{$pagesTotal}}</strong>
                    <span>Total pages</span>
                </div>
                <div class="xe-label">
                    <strong class="num">{{$mainPagesTotal}}</strong>
                    <span>Main pages</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="xe-widget xe-counter xe-counter-info" data-count=".num" data-from="0" data-to="{{$collectionsTotal}}" data-duration="3" data-easing="true">
                <a href="{{$userRouteAccess('collections.index') ? cms_route('collections.index') : '#'}}" class="xe-icon">
                    <i class="{{icon_type('collections')}}"></i>
                </a>
                <div class="xe-label">
                    <strong class="num">{{$collectionsTotal}}</strong>
                    <span>Total collections</span>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="xe-widget xe-counter xe-counter-blue" data-count=".num" data-from="0" data-to="{{$usersTotal}}" data-duration="2" data-easing="true">
                <a href="{{cms_route('cmsUsers.index')}}" class="xe-icon">
                    <i class="{{icon_type('cmsUsers')}}"></i>
                </a>
                <div class="xe-label">
                    <strong class="num">{{$usersTotal}}</strong>
                    <span>Total cms users</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="xe-widget xe-counter-block"  data-count=".num" data-from="0" data-to="{{$eventsTotal}}" data-duration="3">
                <div class="xe-upper">
                    <a href="{{$userRouteAccess('collections.index') ? cms_route('collections.index', ['type' => 'events']): '#'}}" class="xe-icon">
                        <i class="{{icon_type('events')}}"></i>
                    </a>
                    <div class="xe-label">
                        <strong class="num">{{$eventsTotal}}</strong>
                        <span>Total events</span>
                    </div>
                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <span>Details</span>
                    <strong>{{$eventsTotalDistinct}} Events by category</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="xe-widget xe-counter-block xe-counter-block-blue"  data-count=".num" data-from="0" data-to="{{$articlesTotal}}" data-duration="3" data-easing="true">
                <div class="xe-upper">
                    <a href="{{$userRouteAccess('collections.index') ? cms_route('collections.index', ['type' => 'articles']) : '#'}}" class="xe-icon">
                        <i class="{{icon_type('articles')}}"></i>
                    </a>
                    <div class="xe-label">
                        <strong class="num">{{$articlesTotal}}</strong>
                        <span>Total article</span>
                    </div>
                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <span>Details</span>
                    <strong>{{$articlesTotalDistinct}} Articles by category</strong>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="xe-widget xe-counter-block xe-counter-block-orange"  data-count=".num" data-from="0" data-to="{{$calendarTotal}}" data-duration="3">
                <div class="xe-upper">
                    <a href="{{$userRouteAccess('calendar.index') ? cms_route('calendar.index') : '#'}}" class="xe-icon">
                        <i class="fa fa-calendar"></i>
                    </a>
                    <div class="xe-label">
                        <strong class="num">{{$calendarTotal}}</strong>
                        <span>Total calendar events</span>
                    </div>
                </div>
                <div class="xe-lower">
                    <div class="border"></div>
                    <span>Details</span>
                    <strong>{{count($calendarEvents)}} Events between 1 week</strong>
                </div>
            </div>
        </div>
    </div>
    @push('body.bottom')
        <script src="{{asset('assets/libs/js/xenon-widgets.js')}}"></script>
    @endpush
@endsection

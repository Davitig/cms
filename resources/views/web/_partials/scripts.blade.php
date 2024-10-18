<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/js/custom.js')}}"></script>

@if (auth('cms')->check())
    <script src="{{ asset('assets/libs/js/trans.js') }}"></script>
    <div id="translations" data-trans-url="{{cms_route('translations.form')}}" data-token="{{csrf_token()}}"></div>
@endif

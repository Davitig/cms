@if (auth('cms')->check())
    <script src="{{ asset('assets/libs/js/trans.js') }}"></script>
    <div id="trans-modal-form" data-trans-url="{{cms_route('translations.form')}}" data-token="{{csrf_token()}}"></div>
@endif

@push('body.bottom')
    <script type="text/javascript">
        $(function (){
            @if (isset($alert) || $errors->hasAny())
            $('html, body').animate({
                scrollTop: $('#{{ $scrollTop ?? 'feedback' }}').offset().top
            }, 0);
            @endif
            let i = 0;
            $('.captcha-reload').on('click', function (e) {
                e.preventDefault();
                i++;
                let captcha = '{{captcha_src('flat')}}' + i;
                $('.captcha-img').attr('src', captcha);
            });
        });
    </script>
@endpush

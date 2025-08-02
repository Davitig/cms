@push('body.bottom')
    <script type="text/javascript">
        let selector = $('{{ $selector ?? '#items' }}');
        selector.on('click', '.form-check-input', function () {
            let target = $(this);
            let id = target.data('id');
            let data = {'id':id, '_token':'{{csrf_token()}}', '_method':'{{ $method ?? 'put' }}'};
            $.post('{{ $url }}', data, function (res) {
                selector.trigger('xhrCheckDone', [target]);
                if (res?.message) {
                    notyf(res.message);
                }
            }, 'json').fail(function (xhr) {
                notyf(xhr.statusText, 'error');
            });
        });
    </script>
@endpush

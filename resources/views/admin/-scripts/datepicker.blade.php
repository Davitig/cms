@push('head')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}">
@endpush
@push('body.bottom')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            flatpickr('.datepicker', {
                dateFormat: '{{ $format ?? 'Y-m-d H:i:S' }}',
                enableTime: '{{ $enableTime ?? 'true' }}',
                enableSeconds: '{{ $enableSeconds ?? 'true' }}'
            });
        });
    </script>
@endpush

@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            let removePhotoChecked = false;
            $('#remove_photo_inp').on('change', function () {
                removePhotoChecked = $(this).prop('checked');
                $('.user-photo').attr('src', '{{ asset('assets/default/img/avatar.png') }}');
            });
            $('form[data-ajax-form="1"]').on('ajaxFormSuccess', function () {
                if (removePhotoChecked) {
                    $('#photo_inp').val('');
                    $('#remove_photo_inp').prop('checked', false);
                    removePhotoChecked = false;
                }
            });
            $('#photo_inp').on('change', function () {
                if (! this.files || ! this.files[0]) {
                    $('.photo-msg').text('Error selecting a file');

                    return;
                }
                let allowedExtension = [
                    'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp'
                ];
                if (allowedExtension.indexOf(this.files[0].type) < 0) {
                    $('.photo-msg').text('Extension type ' + this.files[0].type + ' not allowed');

                    return;
                }

                let reader = new FileReader();

                reader.onload = function (e) {
                    $('.user-photo').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush

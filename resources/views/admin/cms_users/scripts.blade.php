@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            $('#photo-upload-btn').on('click', function () {
                $('#photo_inp').trigger('click');
            });
            let removePhotoChecked = false;
            $('#remove_photo_inp').on('change', function () {
                removePhotoChecked = $(this).prop('checked');
            });
            $('form.ajax-form').on('ajaxFormSuccess', function () {
                if (removePhotoChecked) {
                    $('#photo_inp').val('');
                    $('#user-photo').attr('src', '#').addClass('hidden');
                    $('.photo-upload-text').removeClass('hidden');
                    $('#remove_photo_inp').prop('checked', false);
                    removePhotoChecked = false;
                }
            });
            $('#photo_inp').on('change', function() {
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
                    $('.photo-upload-text').addClass('hidden');
                    $('#user-photo').attr('src', e.target.result).removeClass('hidden');
                    $('.user-photo').attr('src', e.target.result);
                }

                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush

<div class="card-body d-flex align-items-start align-items-sm-center gap-6">
    <img src="{{ $current->id ? cms_route('cms_users.photo', [$current->id]) : asset('assets/default/img/avatar.png') }}"
         alt="Photo" class="d-block w-px-100 h-px-100 rounded bg-white user-photo">
    <div class="button-wrapper" data-error-append="1">
        <label for="photo_inp" class="btn btn-primary me-3 mb-4" tabindex="0">
            <span class="d-none d-sm-block">Select photo</span>
            <i class="icon-base fa fa-upload d-block d-sm-none"></i>
            <input type="file" name="photo" id="photo_inp" class="account-file-input" hidden>
        </label>
        <label for="remove_photo_inp" class="btn btn-label-secondary account-image-reset mb-4">
            <i class="icon-base fa fa-trash-arrow-up d-block d-sm-none"></i>
            <span class="d-none d-sm-block">Reset</span>
            <input type="checkbox" name="remove_photo" id="remove_photo_inp" hidden>
        </label>
        <div>Max size of 1MB</div>
    </div>
</div>
@error('photo')
<div class="text-danger">{{ $message }}</div>
@enderror
@push('body.bottom')
    <script type="text/javascript">
        $(function () {
            let removeImageChecked = false;
            $('#remove_photo_inp').on('change', function () {
                removeImageChecked = $(this).prop('checked');
                $('.user-photo').attr('src', '{{ asset('assets/default/img/avatar.png') }}');
            });
            $('form[data-ajax-form="1"]').on('ajaxFormSuccess', function () {
                if (removeImageChecked) {
                    $('#photo_inp').val('');
                    $('#remove_photo_inp').prop('checked', false);
                    removeImageChecked = false;
                }
            });
            let reader = new FileReader();

            reader.onload = function (e) {
                $('.user-photo').attr('src', e.target.result);
            }

            $('#photo_inp').on('change', function () {
                if (! this.files || ! this.files[0]) {
                    alert('Error selecting a file');

                    return;
                }

                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush

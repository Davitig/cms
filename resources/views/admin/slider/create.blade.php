@if (! empty($current))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-gallery-image">
                    <img src="{{$current->file ?: $current->file_default}}" class="img-responsive" alt="File">
                </div>
                {{ html()->modelForm($current,
                    'post', cms_route('slider.store')
                )->class('form-create form-horizontal ' . $cmsSettings->get('ajax_form'))->open() }}
                <div class="modal-body">
                    <div class="row">
                        @include('admin.slider.form')
                    </div>
                </div>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
    @include('admin._scripts.files_create')
@endif

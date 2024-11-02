@if (! empty($current))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-gallery-image embed-responsive embed-responsive-16by9">
                    <iframe width="600" height="315" src="" frameborder="0" allowfullscreen class="embed-responsive-item"></iframe>
                </div>
                {{ html()->modelForm($current,
                    'post', cms_route('videos.store', [$current->gallery_id])
                )->class('form-create form-horizontal ' . $cmsSettings->get('ajax_form'))->open() }}
                <div class="modal-body">
                    <div class="row">
                        @include('admin.galleries.videos.form')
                    </div>
                </div>
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
    @include('admin._scripts.files_create')
@endif

<div class="modal fade" id="translations-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Translation</h4>
            </div>
            {{ html()->modelForm($current, 'post', cms_route('translations.form.post'))->class('form-horizontal')->open() }}
                @include('admin.translations.modal.form')
            {{ html()->form()->close() }}
        </div>
    </div>
    @include('admin.translations.modal.scripts')
</div>

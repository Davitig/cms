<div class="trans-modal-bg trans-closable" style="opacity:.2"></div>
<div class="trans-closable trans-modal" tabindex="-1">
    <div class="trans-dialog">
        <div class="trans-header clearfix">
            <button type="button" class="trans-close-btn" data-dismiss="trans-modal">
                <span>&times;</span>
            </button>
            <div class="trans-title">Translation{{language()->count() > 1 ? ' - ' . language()->getActive('full_name') : ''}}</div>
        </div>
        {{ html()->modelForm($current, 'post', cms_route('translations.form.post'))->id('trans-form')->open() }}
        @include('admin.translations.modal.form')
        {{ html()->form()->close() }}
    </div>
    @include('admin.translations.modal.scripts')
</div>

<div class="modal fade" id="file-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" name="current_page" value="{{ request('currentPage', 1) }}">
            <input type="hidden" name="last_page" value="{{ request('lastPage', 1) }}">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium text-black">Create a new file</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ html()->modelForm($current, 'post', cms_route('pages.files.store', [$current->page_id]))
                ->data('ajax-form', $preferences->get('ajax_form'))->attribute('novalidate')->open() }}
                @include('admin.pages.files.form')
                {{ html()->form()->close() }}
            </div>
        </div>
    </div>
    {{-- keep script inside modal --}}
    @include('admin.-scripts.files-create')
</div>

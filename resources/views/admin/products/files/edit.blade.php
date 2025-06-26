<div class="modal fade" id="file-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {{ html()->modelForm($current, 'put', cms_route('products.files.update', [
                $current->product_id, $current->id
            ], language()->containsMany() ? $current->language : null))
            ->class('ajax-form')->data('lang', $current->language)->open() }}
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium text-black">Edit file</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @include('admin.products.files.form')
            {{ html()->form()->close() }}
        </div>
    </div>
    {{-- keep script inside modal --}}
    @include('admin._scripts.files_edit')
</div>

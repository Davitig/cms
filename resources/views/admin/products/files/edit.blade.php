<div class="modal fade" id="file-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-customs" role="document">
        <div class="modal-content">
            <div class="modal-header mb-4">
                <div class="modal-title fs-5 fw-medium text-black">Edit file</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @include('admin.-partials.lang.tabs')
            <div class="tab-content pt-2">
                @php($activeLang = language()->active())
                @foreach($items as $current)
                    <div id="item-{{ $current->language }}" class="tab-pane{{ $current->language == $activeLang ? ' show active' : '' }}">
                        {{ html()->modelForm($current, 'put', cms_route('products.files.update', [$current->product_id, $current->id], $current->language))
                        ->data('ajax-form', $preferences->get('ajax_form'))->data('lang', $current->language)->attribute('novalidate')->open() }}
                        @include('admin.products.files.form')
                        {{ html()->form()->close() }}
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- keep script inside modal --}}
    @include('admin.-scripts.files-edit')
</div>

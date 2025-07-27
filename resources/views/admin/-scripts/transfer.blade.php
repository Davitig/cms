<div class="modal fade" id="transfer-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title fs-5 fw-medium text-dark">Transfer to</div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{$route}}" method="post" id="transfer-form" data-ajax-form="{{$preferences->get('ajax_form')}}">
                    <input type="hidden" name="_method" value="put">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="column" value="{{$column}}">
                    <input type="hidden" name="id" value="0">
                    @if (isset($recursive) && $recursive)
                        <input type="hidden" name="recursive" value="1">
                    @endif
                    <div class="row">
                        <div class="mb-6">
                            {{ html()->text('column_value')->id('column_value')->class('form-control')->placeholder('Enter ' . str($column)->replace('_', ' ')) }}
                        </div>
                        <div class="d-flex gap-4">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">{{trans('general.close')}}</button>
                            <button type="submit" class="btn btn-primary">{{trans('general.submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        // Call modal
        let id = 0;
        let transferModal = $('#transfer-modal');
        $('#items').on('click', '.transfer', function (e) {
            e.preventDefault();

            id = $(this).data('id');
            transferModal.find('input[name="id"]').val(id);
            transferModal.modal('show');
        });

        // Remove transferred item
        $('#transfer-form').on('ajaxFormDone', function () {
            let targetId = $(this).find('#column_value').val();

            transferModal.modal('hide');

            if (targetId !== '{{$parentId ??= 0}}') {
                let target = $('#item'+id);
                if (! target.siblings().length) {
                    target.parent().fadeOut(500, function () {
                        $(this).remove();
                    });
                } else {
                    target.fadeOut(500, function () {
                        $(this).remove();
                    });
                }
                textDecrement(1, '.count, .count-items-{{ $parentId }}');
                textIncrement(1, '.count-items-' + targetId);
            }
        });
    });
</script>

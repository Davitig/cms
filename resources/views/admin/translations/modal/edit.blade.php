<div class="modal fade" id="translations-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul class="nav nav-tabs">
                @foreach ($items as $current)
                    <li{!!language() != $current->language ? '' : ' class="active"'!!}>
                        <a href="#item-{{$current->language}}" data-toggle="tab">{{language($current->language, 'full_name')}}</a>
                    </li>
                @endforeach
                </ul>
            </div>
            <div class="tab-content">
            @foreach ($items as $current)
                <div class="tab-pane{{language() != $current->language ? '' : ' active'}}" id="item-{{$current->language}}">
                    {{ html()->modelForm($current,
                        'post', cms_route('translations.form.post', [], count(languages()) > 1 ? $current->language : null)
                    )->class('form-horizontal')->data('lang', $current->language)->open() }}
                        <input type="hidden" name="id" value="{{$current->id}}">
                        @include('admin.translations.modal.form')
                    {{ html()->form()->close() }}
                </div>
            @endforeach
            </div>
        </div>
    </div>
    @include('admin.translations.modal.scripts')
</div>

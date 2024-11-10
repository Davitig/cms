<div class="trans-modal-bg trans-closable" style="opacity:.2"></div>
<div class="trans-modal trans-closable" tabindex="-1">
    <div class="trans-dialog">
        <div class="trans-header clearfix">
            <button type="button" class="trans-close-btn" data-dismiss="trans-modal">
                <span>&times;</span>
            </button>
            @if (is_multilanguage())
                <div class="trans-nav clearfix">
                    @foreach ($items as $current)
                        <div class="trans-nav-item{{language() == $current->language ? ' active' : ''}}">
                            <a href="#item-{{$current->language}}">{{language($current->language, 'full_name')}}</a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="trans-title">Translation</div>
            @endif
        </div>
        <div class="trans-tab-content">
            @foreach ($items as $current)
                <div class="trans-tab-pane{{language() == $current->language ? ' active' : ''}}" id="item-{{$current->language}}">
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
    @include('admin.translations.modal.scripts')
</div>

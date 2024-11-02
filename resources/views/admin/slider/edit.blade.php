@if (! empty($items))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="tab-content">
                    @foreach ($items as $current)
                        <div class="tab-pane{{language() == $current->language ? ' active' : ''}}" id="modal-item-{{$current->language}}">
                            <div class="modal-gallery-image">
                                @if (in_array($ext = pathinfo($current->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{$current->file}}" class="file{{$current->language}} img-responsive" alt="{{$current->title}}">
                                @elseif(! empty($ext))
                                    <img src="{{asset('assets/libs/images/file-ext-icons/'.$ext.'.png')}}"
                                         class="file{{$current->language}} not-photo img-responsive"
                                         alt="{{$current->title}}">
                                @else
                                    <img src="{{asset('assets/libs/images/file-ext-icons/www.png')}}"
                                         class="file{{$current->language}} not-photo img-responsive"
                                         alt="{{$current->title}}">
                                @endif
                            </div>
                            {{ html()->modelForm($current,
                                'put', cms_route('slider.update', [
                                    $current->id
                                ], is_multilanguage() ? $current->language : null)
                            )->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                            ->data('lang', $current->language)->open() }}
                            <div class="modal-body">
                                <div class="row">
                                    @include('admin.slider.form')
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    @endforeach
                </div>
                @if (is_multilanguage())
                    <ul class="modal-footer modal-gallery-top-controls nav nav-tabs">
                        @foreach ($items as $current)
                            <li{!!language() == $current->language ? ' class="active"' : ''!!}>
                                <a href="#modal-item-{{$current->language}}" data-toggle="tab">
                                    <span class="visible-xs">{{$current->language}}</span>
                                    <span class="hidden-xs">{{language($current->language, 'full_name')}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
    @include('admin._scripts.files_edit')
@endif

@if (! empty($items))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="tab-content">
                    @foreach ($items as $current)
                        <div class="tab-pane{{language() != $current->language ? '' : ' active'}}"
                             id="modal-item-{{$current->language}}">
                            <div class="modal-gallery-image">
                                @if (in_array($ext = pathinfo($current->file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{$current->file}}" class="file{{$current->language}} img-responsive"/>
                                @elseif( ! empty($ext))
                                    <img src="{{asset('assets/libs/images/file-ext-icons/'.$ext.'.png')}}"
                                         class="file{{$current->language}} not-photo img-responsive"
                                         alt="{{$current->title}}"/>
                                @else
                                    <img src="{{asset('assets/libs/images/file-ext-icons/www.png')}}"
                                         class="file{{$current->language}} not-photo img-responsive"
                                         alt="{{$current->title}}"/>
                                @endif
                            </div>
                            {{ html()->modelForm($current,
                                'put', cms_route('files.update', [
                                    $current->table_name, $current->table_id, $current->id
                                ], is_multilanguage() ? $current->language : null)
                            )->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                            ->data('lang', $current->language)->open() }}
                            <div class="modal-body">
                                <div class="row">
                                    @include('admin.files.form')
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    @endforeach
                </div>
                @if (is_multilanguage())
                    <ul class="modal-footer modal-gallery-top-controls nav nav-tabs">
                        @foreach ($items as $current)
                            <li{!!language() != $current->language ? '' : ' class="active"'!!}>
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
        <script type="text/javascript">
            var currentLang = '{{language()}}';
            var formSelector = $('#form-modal').find('.{{$cmsSettings->get('ajax_form')}}');

            formSelector.on('ajaxFormSuccess', function () {
                var lang = $(this).data('lang');
                if (lang === currentLang) {
                    var title = $('[name="title"]', this).val();
                    var file = $('[name="file"]', this).val();
                    var visible = $('[name="visible"]', this).prop('checked');

                    var item = $('.gallery-env #item{{$current->id}}');
                    $('.title', item).text(title);
                    $('.thumb img', item).attr('src', getFileImage(file).file);

                    var icon = (visible ? 'fa fa-eye' : 'fa fa-eye-slash');
                    $('.visibility i', item).attr('class', icon);
                }
            });

            formSelector.find('[name="file"]').on('fileSet', function () {
                var fileId = $(this).attr('id');
                var fileValue = $(this).val();
                var result = getFileImage(fileValue);

                var photoSelector = $('#form-modal .' + fileId);
                photoSelector.removeClass('not-photo');
                if (!result.isPhoto) {
                    photoSelector.addClass('not-photo');
                }
                photoSelector.attr('src', result.file);
            });
        </script>
        @include('admin._scripts.get_file_func')
    </div>
@endif

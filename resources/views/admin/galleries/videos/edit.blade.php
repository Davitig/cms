@if (! empty($items))
    <div class="modal fade" id="form-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="tab-content">
                    @foreach ($items as $current)
                        <div class="tab-pane{{language()->active() == $current->language ? ' active' : ''}}" id="modal-item-{{$current->language}}">
                            <div class="modal-gallery-image embed-responsive embed-responsive-16by9">
                                <iframe src="{{get_youtube_embed($current->file)}}" frameborder="0" allowfullscreen class="embed-responsive-item"></iframe>
                            </div>
                            {{ html()->modelForm($current,
                                'put', cms_route('videos.update', [
                                    $current->gallery_id, $current->id
                                ], language()->containsMany() ? $current->language : null)
                            )->class('form-horizontal ' . $cmsSettings->get('ajax_form'))
                            ->data('lang', $current->language)->open() }}
                            <div class="modal-body">
                                <div class="row">
                                    @include('admin.galleries.videos.form')
                                </div>
                            </div>
                            {{ html()->form()->close() }}
                        </div>
                    @endforeach
                </div>
                @if (language()->containsMany())
                    <ul class="modal-footer modal-gallery-top-controls nav nav-tabs">
                        @foreach ($items as $current)
                            <li{!!language()->active() == $current->language ? ' class="active"' : ''!!}>
                                <a href="#modal-item-{{$current->language}}" data-toggle="tab">
                                    <span class="visible-xs">{{$current->language}}</span>
                                    <span class="hidden-xs">{{language()->get($current->language, 'full_name')}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
        <script type="text/javascript">
            let formSelector = $('#form-modal .{{$cmsSettings->get('ajax_form')}}');
            formSelector.on('ajaxFormSuccess', function(e, res) {
                $('#item{{$current->id}} .thumb iframe').attr('src', res?.data?.youtube);
                $('#form-modal').find('iframe').attr('src', res?.data?.youtube);
            });
        </script>
    </div>
    @include('admin._scripts.files_edit')
@endif

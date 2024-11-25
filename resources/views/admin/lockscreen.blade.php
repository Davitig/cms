@if (auth('cms')->user()->hasLockScreen())
    <div id="lockscreen">
        <div class="login-container">
            <div class="row">
                <div class="col-sm-7">
                    <form role="form" action="{{cms_route('lockscreen.put')}}" method="post" class="lockcreen-form fade-in-effect">
                        <input type="hidden" name="_method" value="put">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="user-thumb">
                            <img src="{{auth('cms')->user()->photo}}" width="128" class="img-circle" alt="User Photo">
                        </div>
                        <div class="form-group">
                            <h3>Welcome back, {{auth('cms')->user()->first_name}}!</h3>
                            <p>Enter your password to access the admin.</p>
                            <div class="input-group">
                                <input type="password" class="form-control input-dark{{! $errors->isEmpty() ? ' error' : ''}}" name="password" id="password" placeholder="Password">
                                @if (! $errors->isEmpty())
                                    <label class="error">{{$errors->first()}}</label>
                                @endif
                                <span class="input-group-btn">
                                <button type="submit" class="btn btn-secondary">{{trans('auth.login')}}</button>
                            </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(function() {
                // Reveal a Login form
                setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);

                // Set Form focus
                $(".lockcreen-form .form-group:has(.form-control):first .form-control").focus();

                @if (! session()->has('includeLockscreen'))
                // Form validation and AJAX request
                $(".lockcreen-form").on('submit', function(e) {
                    e.preventDefault();

                    let input = $(this).serializeArray();
                    let password = $('#password', this);
                    password.val('').siblings('label').remove();

                    $.ajax({
                        url: '{{cms_route('lockscreen.put')}}',
                        method: 'POST',
                        dataType: 'json',
                        data: input,
                        success: function(data) {
                            if (data.result) {
                                @if ($cmsSettings->get('lockscreen'))
                                lockscreen('{{$cmsSettings->get('lockscreen')}}', '{{cms_route('lockscreen.put')}}', true);
                                @endif

                                $('body > #lockscreen').fadeOut(400, function() {
                                    $(this).remove();
                                    $('body').removeClass('lockscreen-page');
                                });
                            } else {
                                password.addClass('error').after('<label class="error">'+data.message+'</label>');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status !== 429) {
                                alert(xhr.responseText);
                            } else {
                                alert(xhr.responseText + ' Retry after ' + xhr.getResponseHeader('Retry-after') + ' seconds.');
                            }
                        }
                    });
                });
                @endif
            });
        </script>
    </div>
@endif

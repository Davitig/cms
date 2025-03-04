<div class="member-form-inputs">
    <div class="form-group{{($error = $errors->first('email')) ? ' validate-has-error' : '' }}">
        <label class="col-sm-2 control-label text-left required">Email:</label>
        <div class="col-sm-10">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-at"></i></span>
                {{ html()->text('email')->id('email')->class('form-control') }}
            </div>
            @if ($error)
                <span class="text-danger">{{$error}}</span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group{{($error = $errors->first('first_name')) ? ' validate-has-error' : '' }}">
                <label class="col-sm-4 control-label text-left required">First name:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-header"></i></span>
                        {{ html()->text('first_name')->id('first_name')->class('form-control') }}
                    </div>
                    @if ($error)
                        <span class="text-danger">{{$error}}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group{{($error = $errors->first('last_name')) ? ' validate-has-error' : '' }}">
                <label class="col-sm-4 control-label required">Last name:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-header"></i></span>
                        {{ html()->text('last_name')->id('last_name')->class('form-control') }}
                    </div>
                    @if ($error)
                        <span class="text-danger">{{$error}}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (auth('cms')->user()->hasFullAccess() && auth('cms')->id() != $current->id)
        <div class="form-group-separator"></div>

        <div class="form-group{{($error = $errors->first('cms_user_role_id')) ? ' validate-has-error' : '' }}">
            <label class="col-sm-2 control-label text-left required">Role:</label>
            <div class="col-sm-10">
                <div class="input-group">
                    <span class="input-group-addon"><i class="{{ icon_type('roles') }}"></i></span>
                    {{ html()->select('cms_user_role_id', $roles)->id('cms_user_role_id')->class('form-control') }}
                </div>
                @if ($error)
                    <span class="text-danger">{{$error}}</span>
                @endif
            </div>
        </div>
    @else
        {{ html()->hidden('cms_user_role_id') }}
        @if ($error = $errors->first('cms_user_role_id'))
            <span class="text-danger">{{$error}}</span>
        @endif
    @endif

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group{{($error = $errors->first('phone')) ? ' validate-has-error' : '' }}">
                <label class="col-sm-4 control-label text-left">Phone:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        {{ html()->text('phone')->id('phone')->class('form-control') }}
                    </div>
                    @if ($error)
                        <span class="text-danger">{{$error}}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group{{($error = $errors->first('address')) ? ' validate-has-error' : '' }}">
                <label class="col-sm-4 control-label">Address:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-building"></i></span>
                        {{ html()->text('address')->id('address')->class('form-control') }}
                    </div>
                    @if ($error)
                        <span class="text-danger">{{$error}}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group-separator"></div>

    @if (auth('cms')->id() != $current->id)
        <div class="form-group">
            <label class="col-sm-2 control-label text-left">Block:</label>
            <div class="col-sm-10">
                {{ html()->checkbox('blocked')->id('blocked')->class('iswitch iswitch-secondary') }}
            </div>
        </div>

        <div class="form-group-separator"></div>
    @endif

    <div id="change-password" class="form-group{{ ! $current->id ? '' : ' collapse' . ($errors->has('password') ? ' in' : '')}}">
        <div class="col-sm-6">
            <div class="form-group{{($error = $errors->first('password')) ? ' validate-has-error' : '' }}">
                <label class="col-sm-4 control-label text-left{{$current->id ? '' : ' required'}}">Password:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    @if ($error)
                        <span class="text-danger">{{$error}}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label class="col-sm-4 control-label{{$current->id ? '' : ' required'}}">Repeat Password:</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-key"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-secondary">{{$submit}}</button>
    <a href="{{ cms_route('cmsUsers.index') }}" class="btn btn-blue">{{ trans('general.back') }}</a>
    @if ($current->id)
        <div class="btn btn-info pull-right" data-toggle="collapse" data-target="#change-password">Change Password</div>
    @endif
</div>

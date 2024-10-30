<div class="settings-pane">
  <a href="#" data-toggle="settings-pane" data-animate="true">
    &times;
  </a>
  <div class="settings-pane-inner">
    <div class="row">
      <div class="col-md-4">
        <div class="user-info">
          <div class="user-image">
            <a href="{{cms_route('cmsUsers.show', [auth('cms')->id()])}}">
              <img src="{{auth('cms')->user()->photo}}" width="128" class="img-circle">
            </a>
          </div>
          <div class="user-details">
            <h3>
              <a href="{{cms_route('cmsUsers.show', [auth('cms')->id()])}}">{{auth('cms')->user()->first_name}} {{auth('cms')->user()->last_name}}</a>
              <!-- Available statuses: is-online, is-idle, is-busy and is-offline -->
              <span class="user-status is-online"></span>
            </h3>
            <p class="user-title">{{user_roles(auth('cms')->user()->role)}}</p>
            <div class="user-links">
              <a href="{{cms_route('cmsUsers.edit', [auth('cms')->id()])}}" class="btn btn-primary">Edit Profile</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8 link-blocks-env">
        <div class="links-block left-sep">
          <h4>
            <span>Notifications</span>
          </h4>
        </div>
        <div class="links-block left-sep">
          <h4>
            <span>Help Desk</span>
          </h4>
        </div>
      </div>
    </div>
  </div>
</div>

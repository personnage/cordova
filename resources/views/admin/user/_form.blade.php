@include('admin.common._alert')

<form method="POST" action="{{ url('admin/user', $user->id) }}" role="form" class="form-horizontal" accept-charset="utf-8" autocomplete="off">
  {{ csrf_field() }}

  @if($user->exists)
  {{ method_field('PATCH') }}
  @endif

  <fieldset>
    <h3>Account</h3>
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
      <label for="name" class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input type="text" name="name" value="{{ old('name', $user->name) }}" id="name" class="form-control" placeholder="Name" autocomplete="off" required>
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
      <label for="email" class="col-sm-2 control-label">Email</label>
      <div class="col-sm-10">
        <input type="email" name="email" value="{{ old('email', $user->email) }}" id="email" class="form-control" placeholder="Email" autocomplete="off" required>
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
      </div>
    </div>
  </fieldset>

  <fieldset>
    <h3>Password</h3>

    @if($user->exists)
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label for="password" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
          @if ($errors->has('password'))
              <span class="help-block">
                  <strong>{{ $errors->first('password') }}</strong>
              </span>
          @endif
        </div>
      </div>

      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        <label for="password-confirm" class="col-sm-2 control-label">Confirm</label>
        <div class="col-sm-10">
          <input type="password" name="password_confirmation" id="password-confirm" class="form-control" placeholder="Confirm Password" autocomplete="off">
          @if ($errors->has('password_confirmation'))
              <span class="help-block">
                  <strong>{{ $errors->first('password_confirmation') }}</strong>
              </span>
          @endif
        </div>
      </div>
    @else
      <div class="form-group">
        <label for="user-name" class="col-sm-2 control-label">Password</label>
        <div class="col-sm-10">
          <p class="lead">
            Reset link will be generated and sent to the user. <br>
            User will be forced to set the password on first sign in.
          </p>
        </div>
      </div>
    @endif
  </fieldset>

  <fieldset>
    <h3>Access</h3>

    <div class="form-group">
      <label for="admin" class="col-sm-2 control-label">Admin</label>
      <div class="col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="admin" id="admin"
              @if(old('admin', $user->admin)) checked @endif
              @if(Auth::id()===$user->id) disabled @endif
            >

            @if(Auth::id()===$user->id)
              You cannot remove your own admin rights.
            @else
              Ok
            @endif
          </label>
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Roles</label>
      <div class="col-sm-10">
        <select id="select-roles-to-users" name="roles[]" class="form-control" multiple placeholder="Select a roles...">
          <option value="">Select a roles...</option>
          @foreach($roles as $role)
          <option
            @if(false !== array_search($role->id, (array) old('roles')) or $user->hasRole($role->name))
              selected
            @endif value="{{ $role->id }}">{{ $role->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </fieldset>

  <div class="form-group">
    @if($user->exists)
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Save changes</button>
      </div>
    @else
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Create user</button>
      </div>
    @endif

    <div class="col-sm-offset-2 col-sm-4">
      <a href="{{ url('admin/user') }}" class="btn btn-default">Cancel</a>
    </div>
  </div>
</form>

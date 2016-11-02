@include('admin.common._alert')

<form method="POST" action="{{ url('admin/role', $role->id) }}" role="form" class="form-horizontal" accept-charset="utf-8" autocomplete="off">
  {{ csrf_field() }}

  @if($role->exists)
  {{ method_field('PATCH') }}
  @endif

  <fieldset>
    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
      <label for="name" class="col-sm-2 control-label">Name</label>
      <div class="col-sm-10">
        <input type="text" name="name" value="{{ old('name', $role->name) }}" id="name" class="form-control" placeholder="Role name in lowercase" autocomplete="off" required>
        @if ($errors->has('name'))
            <span class="help-block">
                <strong>{{ $errors->first('name') }}</strong>
            </span>
        @endif
      </div>
    </div>

    <div class="form-group{{ $errors->has('label') ? ' has-error' : '' }}">
      <label for="label" class="col-sm-2 control-label">Label</label>
      <div class="col-sm-10">
        <input type="text" name="label" value="{{ old('label', $role->label) }}" id="label" class="form-control" placeholder="A small description" autocomplete="off" required>
        @if ($errors->has('label'))
            <span class="help-block">
                <strong>{{ $errors->first('label') }}</strong>
            </span>
        @endif
      </div>
    </div>

    <div class="form-group">
      <label class="col-sm-2 control-label">Permissions</label>
      <div class="col-sm-10">
        <select id="select-permissions-to-roles" name="permissions[]" class="form-control" multiple placeholder="Select a permissions...">
          <option value="">Select a permissions...</option>
          @foreach($permissions as $permission)
          <option
            @if(false !== array_search($permission->id, (array) old('permissions')) or $role->hasPermission($permission->name))
              selected
            @endif value="{{ $permission->id }}">{{ $permission->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

  </fieldset>

  <div class="form-group">
    @if($role->exists)
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Save changes</button>
      </div>
    @else
      <div class="col-sm-offset-2 col-sm-4">
        <button type="submit" class="btn btn-default">Create role</button>
      </div>
    @endif

    <div class="col-sm-offset-2 col-sm-4">
      <a href="{{ url('admin/role') }}" class="btn btn-default">Cancel</a>
    </div>
  </div>
</form>

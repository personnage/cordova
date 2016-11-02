<div class="row placeholders">
  <div class="col-xs-12 col-sm-4 placeholder">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Users</strong></h3>
      </div>
      <div class="panel-body">
        <a href="{{ url('admin/user') }}" class="card-number">{{ $stats->countUsers() }}</a>
        <hr>
        @can('create-user')
          <a class="btn btn-success btn-raised pull-left" href="{{ url('admin/user/create') }}" role="button">New User</a>
        @endcan
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-4 placeholder">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Roles</strong></h3>
      </div>
      <div class="panel-body">
        <a href="{{ url('admin/role') }}" class="card-number">{{ $stats->countRoles() }}</a>
        <hr>
        @can('create-role')
          <a class="btn btn-success btn-raised pull-left" href="{{ url('admin/role/create') }}" role="button">New Role</a>
        @endcan
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-4 placeholder">
    <div class="panel panel-info">
      <div class="panel-heading">
        <h3 class="panel-title"><strong>Permissions</strong></h3>
      </div>
      <div class="panel-body">
        <a href="{{ url('admin/permission') }}" class="card-number">{{ $stats->countPermissions() }}</a>
        <hr>
        @can('create-permission')
          <a class="btn btn-success btn-raised pull-left" href="{{ url('admin/permission/create') }}" role="button">New Permission</a>
        @endcan
      </div>
    </div>
  </div>
</div>

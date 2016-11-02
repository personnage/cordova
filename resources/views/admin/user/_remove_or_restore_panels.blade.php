@unless($user->isConfirmed())
{{--confirm user--}}
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Confirm user</h3>
  </div>
  <div class="panel-body">
    <p>This user has an unconfirmed email address. You may force a confirmation.</p>

    <form method="POST" action="{{ url('admin/user', [$user->id, 'confirm']) }}" role="form" id="confirm-user-{{ $user->id }}" class="hidden">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
    </form>

    <button type="submit" form="confirm-user-{{ $user->id }}" class="btn btn-raised btn-info" data-confirm="Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">confirm user</button>
  </div>
</div>
@endunless

@if($user->trashed())
  {{--restore user--}}
  <div class="panel panel-success">
    <div class="panel-heading">
      <h3 class="panel-title">Restore this user</h3>
    </div>
    <div class="panel-body">
      <p>Restoring user has the following effects:</p>
      <ul class="list-group">
        <li class="list-group-item">User will be able to login</li>
      </ul>

      <form method="POST" action="{{ url('admin/user', [$user->id, 'restore']) }}" role="form" id="restore-user-{{ $user->id }}" class="hidden">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
      </form>

      <button type="submit" form="restore-user-{{ $user->id }}" class="btn btn-raised btn-success">restore</button>
    </div>
  </div>
@else

  {{--delete user (move to trash)--}}
  <div class="panel panel-warning">
    <div class="panel-heading">
      <h3 class="panel-title">Delete this user</h3>
    </div>
    <div class="panel-body">
      <p>Deleting user has the following effects:</p>
      <ul class="list-group">
        <li class="list-group-item">User will not be able to login</li>
      </ul>

      <form method="POST" action="{{ url('admin/user', [$user->id, 'delete']) }}" role="form" id="delete-user-{{ $user->id }}" class="hidden">
        {{ csrf_field() }}
        {{ method_field('PATCH') }}
      </form>

      <button type="submit" form="delete-user-{{ $user->id }}" class="btn btn-raised btn-warning" data-confirm="USER WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))" @if(Auth::id()===$user->id) disabled @endif>delete</button>
    </div>
  </div>
@endif

<div class="panel panel-danger">
  <div class="panel-heading">
    <h3 class="panel-title">Destroy this user</h3>
  </div>
  <div class="panel-body">
    <p>Destroying a user has the following effects:</p>
    <ul class="list-group">
      <li class="list-group-item">All user content like authored posts, news, comments will be removed</li>
    </ul>

    {{--delete user from table--}}
    <form method="POST" action="{{ url('admin/user', $user->id) }}" role="form" id="destroy-user-{{ $user->id }}" class="hidden">
      {{ csrf_field() }}
      {{ method_field('DELETE') }}
    </form>

    <button type="submit" form="destroy-user-{{ $user->id }}" class="btn btn-raised btn-danger" data-confirm="USER {{ $user->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

  </div>
</div>

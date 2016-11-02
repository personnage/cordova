@include('admin.common._alert')
@include('admin.user._filter')

<hr>

@include('admin.user._well')

<div class="table-responsive">
  <table class="table table-middle table-hover">
    <tbody>
    @foreach ($users as $user)
      <tr>
        <td>
          <span class="text-middle text-nowrap">
            <i class="fa fa-user @if($user->trashed())text-danger @endif" aria-hidden="true"></i>
            <a href="{{ url('admin/user', $user->id) }}" class="text-muted">{{ $user->name }}</a>
          </span>

          @if ($user->admin)
            <span class="text-danger">(Admin)</span>
            @if (Auth::id()===$user->id)
              <span class="text-danger">(It's you!)</span>
            @endif
          @endif
        </td>

        <td>
          <span class="text-middle">
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <a href="mailto:{{ $user->email }}" class="text-muted">{{ $user->email }}</a>
          </span>
        </td>

        <td>
          <div role="group" class="btn-group pull-right">
            {{--Not editable if it deleted.--}}
            @unless($user->trashed())
              <a href="{{ url('admin/user', [$user->id, 'edit']) }}" class="btn btn-raised">edit</a>
            @endunless

            @if($user->trashed())
            {{--restore user--}}

            <form method="POST" action="{{ url('admin/user', [$user->id, 'restore']) }}" role="form" id="restore-user-{{ $user->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="restore-user-{{ $user->id }}" class="btn btn-raised btn-success">restore</button>

            {{--delete user from table--}}
            <form method="POST" action="{{ url('admin/user', $user->id) }}" role="form" id="destroy-user-{{ $user->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>

            <button type="submit" form="destroy-user-{{ $user->id }}" class="btn btn-raised btn-danger" data-confirm="USER {{ $user->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

            @else

            {{--delete user (move to trash)--}}
            @unless(Auth::id()===$user->id)
            <form method="POST" action="{{ url('admin/user', [$user->id, 'delete']) }}" role="form" id="delete-user-{{ $user->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>
            @endunless

            <button type="submit" form="delete-user-{{ $user->id }}" class="btn btn-raised btn-warning" data-confirm="USER WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))" @if(Auth::id()===$user->id) disabled @endif>delete</button>
            @endif
          </div>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $users->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

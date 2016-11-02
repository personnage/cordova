@include('admin.common._alert')
@include('admin.role._filter')

<hr>

@include('admin.role._well')

<div class="table-responsive">
  <table class="table table-middle table-list table-hover">
    <tbody>
    @foreach ($roles as $role)
      <tr>
        <td>
          <span class="text-middle text-nowrap">
            <i class="fa fa-users @if($role->trashed())text-danger @endif" aria-hidden="true"></i>
            <a href="{{ url('admin/role', $role->id) }}" class="text-muted">{{ $role->name }}</a>
          </span>
        </td>

        <td>
          <span class="text-middle">
            <span>{{ $role->label }}</span>
          </span>
        </td>

        <td>
          <div role="group" class="btn-group pull-right">
            {{--Not editable if it deleted.--}}
            @unless($role->trashed())
              <a href="{{ url('admin/role', [$role->id, 'edit']) }}" class="btn btn-raised">edit</a>
            @endunless

            @if($role->trashed())
            {{--restore role--}}

            <form method="POST" action="{{ url('admin/role', [$role->id, 'restore']) }}" role="form" id="restore-role-{{ $role->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="restore-role-{{ $role->id }}" class="btn btn-raised btn-success">restore</button>

            {{--destroy role from table--}}
            <form method="POST" action="{{ url('admin/role', $role->id) }}" role="form" id="destroy-role-{{ $role->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>

            <button type="submit" form="destroy-role-{{ $role->id }}" class="btn btn-raised btn-danger" data-confirm="ROLE {{ $role->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

            @else

            {{--delete role (move to trash)--}}
            <form method="POST" action="{{ url('admin/role', [$role->id, 'delete']) }}" role="form" id="delete-role-{{ $role->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="delete-role-{{ $role->id }}" class="btn btn-raised btn-warning" data-confirm="ROLE WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">delete</button>
            @endif
          </div>
        </td>

      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $roles->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

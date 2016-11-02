@include('admin.common._alert')
@include('admin.permission._filter')

<hr>

@include('admin.permission._well')

<div class="table-responsive">
  <table class="table table-middle table-list table-hover">
    <tbody>
    @foreach ($permissions as $permission)
      <tr>
        <td>
          <span class="text-middle text-nowrap">
            <i class="fa fa-unlock-alt @if($permission->trashed())text-danger @endif" aria-hidden="true"></i>
            <a href="{{ url('admin/permission', $permission->id) }}" class="text-muted">{{ $permission->name }}</a>
          </span>
        </td>

        <td>
          <span class="text-middle">
            <span>{{ $permission->label }}</span>
          </span>
        </td>

        <td>
          <div role="group" class="btn-group pull-right">
            {{--Not editable if it deleted.--}}
            @unless($permission->trashed())
              <a href="{{ url('admin/permission', [$permission->id, 'edit']) }}" class="btn btn-raised">edit</a>
            @endunless

            @if($permission->trashed())
            {{--restore permission--}}

            <form method="POST" action="{{ url('admin/permission', [$permission->id, 'restore']) }}" role="form" id="restore-permission-{{ $permission->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="restore-permission-{{ $permission->id }}" class="btn btn-raised btn-success">restore</button>

            {{--destroy permission from table--}}
            <form method="POST" action="{{ url('admin/permission', $permission->id) }}" role="form" id="destroy-permission-{{ $permission->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>

            <button type="submit" form="destroy-permission-{{ $permission->id }}" class="btn btn-raised btn-danger" data-confirm="ROLE {{ $permission->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

            @else

            {{--delete permission (move to trash)--}}
            <form method="POST" action="{{ url('admin/permission', [$permission->id, 'delete']) }}" role="form" id="delete-permission-{{ $permission->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="delete-permission-{{ $permission->id }}" class="btn btn-raised btn-warning" data-confirm="ROLE WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">delete</button>
            @endif
          </div>
        </td>

      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $permissions->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

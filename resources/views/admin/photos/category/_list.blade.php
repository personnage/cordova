@include('admin.common._alert')
@include('admin.photos.category._filter')

<hr>

@include('admin.photos.category._well')

<div class="table-responsive">
  <table class="table table-middle table-list table-hover">
    <tbody>
    @foreach ($categories as $category)
      <tr>
        <td>
          <span class="text-middle text-nowrap">
            <i class="fa fa-object-group @if($category->trashed())text-danger @endif" aria-hidden="true"></i>
            <a href="{{ url('admin/photos/category', $category->id) }}" class="text-muted">{{ $category->name }}</a>
          </span>
        </td>

        <td>
          <span class="text-middle">
            <span>{{ $category->description }}</span>
          </span>
        </td>

        <td>
          <div role="group" class="btn-group pull-right">
            {{--Not editable if it deleted.--}}
            @unless($category->trashed())
              <a href="{{ url('admin/photos/category', [$category->id, 'edit']) }}" class="btn btn-raised">edit</a>
            @endunless

            @if($category->trashed())
            {{--restore role--}}

            <form method="POST" action="{{ url('admin/photos/category', [$category->id, 'restore']) }}" role="form" id="restore-role-{{ $category->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="restore-role-{{ $category->id }}" class="btn btn-raised btn-success">restore</button>

            {{--destroy role from table--}}
            <form method="POST" action="{{ url('admin/photos/category', $category->id) }}" role="form" id="destroy-role-{{ $category->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('DELETE') }}
            </form>

            <button type="submit" form="destroy-role-{{ $category->id }}" class="btn btn-raised btn-danger" data-confirm="ROLE {{ $category->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

            @else

            {{--delete role (move to trash)--}}
            <form method="POST" action="{{ url('admin/photos/category', [$category->id, 'delete']) }}" role="form" id="delete-role-{{ $category->id }}" class="hidden">
              {{ csrf_field() }}
              {{ method_field('PATCH') }}
            </form>

            <button type="submit" form="delete-role-{{ $category->id }}" class="btn btn-raised btn-warning" data-confirm="ROLE WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">delete</button>
            @endif
          </div>
        </td>

      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $categories->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

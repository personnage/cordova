@include('admin.news._navbar')
<hr>

@include('admin.common._alert')
@include('admin.news.category._filter')
<hr>

@include('admin.news.category._well')

<div class="table-responsive">
  <table class="table table-middle table-list table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th colspan="2">Name</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($categories as $category)
        {{--set inner vars--}}
        @if($category->trashed())
          {{--*/ $css_class = 'text-danger fa fa-trash-o' /*--}}
        @else
          {{--*/ $css_class = 'text-success fa fa-check-circle-o' /*--}}
        @endif
        {{--//set inner vars--}}

        <tr>
          <td>
            <span>
              <i class="{{ $css_class }}" aria-hidden="true"></i>
              <span class="text-muted">{{ $category->title }}</span>
            </span>
          </td>

          <td>
            <span class="text-muted">{{ $category->name }}</span>
          </td>

          <td>
            <div role="group" class="btn-group pull-right">
              {{--Not editable if it deleted.--}}
              @unless($category->trashed())
                <a href="{{ url('admin/news/category', [$category->id, 'edit']) }}" class="btn btn-raised">edit</a>
              @endunless

              @if($category->trashed())
              {{--restore category--}}

              <form method="POST" action="{{ url('admin/news/category', [$category->id, 'restore']) }}" role="form" id="restore-category-{{ $category->id }}" class="hidden">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
              </form>

              <button type="submit" form="restore-category-{{ $category->id }}" class="btn btn-raised btn-success">restore</button>

              {{--destroy category from table--}}
              <form method="POST" action="{{ url('admin/news/category', $category->id) }}" role="form" id="destroy-category-{{ $category->id }}" class="hidden">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
              </form>

              <button type="submit" form="destroy-category-{{ $category->id }}" class="btn btn-raised btn-danger" data-confirm="CATEGORY {{ $category->name }} WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">destroy</button>

              @else

              {{--delete category (move to trash)--}}
              <form method="POST" action="{{ url('admin/news/category', [$category->id, 'delete']) }}" role="form" id="delete-category-{{ $category->id }}" class="hidden">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
              </form>

              <button type="submit" form="delete-category-{{ $category->id }}" class="btn btn-raised btn-warning" data-confirm="CATEGORY WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">delete</button>
              @endif
            </div>
          </td>

        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $categories->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

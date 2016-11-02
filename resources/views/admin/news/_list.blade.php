@include('admin.news._navbar')
<hr>

@include('admin.common._alert')
@include('admin.news._filter')
<hr>

@include('admin.news._well')

<div class="table-responsive">
  <table class="table table-middle table-list table-hover">
    <thead>
      <tr>
        <th>Title</th>
        <th>Author</th>
        <th>Category</th>
        <th>Published</th>
      </tr>
    </thead>
    <tbody>
      @foreach($news as $item)
        {{--set inner vars--}}
        @if($item->trashed())
          {{--*/ $css_class = 'text-danger fa fa-trash-o' /*--}}
        @elseif($item->isNotPublished()) {{--high priority--}}
          {{--*/ $css_class = 'text-warning fa fa-times-circle-o' /*--}}
        @elseif($item->isPending()) {{--low priority--}}
          {{--*/ $css_class = 'text-muted fa fa-circle' /*--}}
        @else
          {{--*/ $css_class = 'text-success fa fa-check-circle-o' /*--}}
        @endif
        {{--//set inner vars--}}

        <tr>
          <td>
            <div>
              <i class="{{ $css_class }}" aria-hidden="true"></i>
              @if($item->trashed())
                <span class="text-muted">{{ $item->title }}</span>
              @else
                <a href="{{ url('admin/news', $item->id) }}" class="text-muted">{{ $item->title }}</a>
              @endif
            </div>

            <div class="btn-group invisible" role="group" aria-label="ctrl-news-item">
              @include('admin.news._ctrl', ['news' => $item])
            </div>
          </td>

          <td>
            <a href="{{ url('admin/user', $item->author->id) }}">
              {{ $item->author->name }}
            </a>
          </td>

          <td>
            <a href="{{ url('admin/news/category', [$item->category->id, 'edit']) }}">
              {{ $item->category->name }}
            </a>
          </td>

          <td>
            <span>{{ $item->published_since->toDayDateTimeString() }}</span>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $news->appends(['filter' => request('filter'), 'sort' => request('sort')])->links() }}
</div>

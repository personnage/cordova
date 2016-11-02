@unless($news->trashed())
  <a href="{{ url('admin/news', [$news->id, 'edit']) }}" class="btn btn-primary btn-xs" role="button">
    <i class="fa fa-pencil" aria-hidden="true"></i> Edit
  </a>

  {{--Up/Down--}}
  @if($news->isNotPublished())
  <form method="POST" action="{{ url('admin/news', [$news->id, 'up']) }}" role="form" id="up-news-item-{{ $news->id }}" class="hidden">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
  </form>
  <button type="submit" form="up-news-item-{{ $news->id }}" class="btn btn-default btn-xs">
    <i class="fa fa-refresh" aria-hidden="true"></i> Up
  </button>
  @else
  <form method="POST" action="{{ url('admin/news', [$news->id, 'down']) }}" role="form" id="down-news-item-{{ $news->id }}" class="hidden">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
  </form>
  <button type="submit" form="down-news-item-{{ $news->id }}" class="btn btn-default btn-xs">
    <i class="fa fa-ban" aria-hidden="true"></i> Down
  </button>
  @endif

  {{--delete item (move to trash)--}}
  <form method="POST" action="{{ url('admin/news', [$news->id, 'delete']) }}" role="form" id="delete-news-item-{{ $news->id }}" class="hidden">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
  </form>
  <button type="submit" form="delete-news-item-{{ $news->id }}" class="btn btn-danger btn-xs" data-confirm="ITEM WILL BE DELETED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">
    <i class="fa fa-trash" aria-hidden="true"></i> Delete
  </button>
@endunless



@if($news->trashed())
  {{--resore news item--}}
  <form method="POST" action="{{ url('admin/news', [$news->id, 'restore']) }}" role="form" id="restore-news-item-{{ $news->id }}" class="hidden">
    {{ csrf_field() }}
    {{ method_field('PATCH') }}
  </form>
  <button type="submit" form="restore-news-item-{{ $news->id }}" class="btn btn-success btn-xs">
    <i class="fa fa-undo" aria-hidden="true"></i> Restore
  </button>

  {{--destroy item (forever)--}}
  <form method="POST" action="{{ url('admin/news', $news->id) }}" role="form" id="destroy-news-item-{{ $news->id }}" class="hidden">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
  </form>
  <button type="submit" form="destroy-news-item-{{ $news->id }}" class="btn btn-danger btn-xs" data-confirm="Item #{{$news->id}} ({{ $news->title }}) WILL BE DESTROYED! Are you sure?" onclick="return confirm(this.getAttribute('data-confirm'))">
    <i class="fa fa-times" aria-hidden="true"></i> Destroy
  </button>
@endif

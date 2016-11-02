<ul class="nav nav-tabs">
  @foreach([
    'admin/news'          => ['List', 'fa fa-list-ol'],
    'admin/news/category' => ['Category', 'fa fa-th-list']] as $path => list($name, $class))
    <li @if(url($path) === url()->current()) class="active" @endif>
      <a href="{{ url($path) }}"><i class="{{ $class }}" aria-hidden="true"></i> {{ $name }}</a>
    </li>
  @endforeach
</ul>

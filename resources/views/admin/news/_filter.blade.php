{{-- When apply filter, exclude the sort, pagination and search. --}}
<ul class="nav nav-pills">
  <li role="presentation" @unless(Request::has('filter')) class="active" @endunless>
    <a href="{{ url('admin/news') }}">Published <span class="badge">{{ $active }}</span></a>
  </li>

  <li role="presentation" @if(request('filter')=='pending') class="active" @endif>
    <a href="{{ url('admin/news').'?filter=pending' }}">Pending <span class="badge">{{ $pending }}</span></a>
  </li>

  <li role="presentation" @if(request('filter')=='inactive') class="active" @endif>
    <a href="{{ url('admin/news').'?filter=inactive' }}">Inactive <span class="badge">{{ $inactive }}</span></a>
  </li>

  <li role="presentation" @if(request('filter')=='deleted') class="active" @endif>
    <a href="{{ url('admin/news').'?filter=deleted' }}">Deleted <span class="badge">{{ $deleted }}</span></a>
  </li>
</ul>

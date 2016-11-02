<ul class="dropdown-menu">
  <li @if(request('sort')=='name_asc') class="active" @endif>
    <a href="{{ url('admin/role').sprintf('?filter=%s&sort=name_asc&page=%d', request('filter'), request('page')) }}">Name</a>
  </li>

  <li @if(request('sort')=='id_desc') class="active" @endif>
    <a href="{{ url('admin/role').sprintf('?filter=%s&sort=id_desc&page=%d', request('filter'), request('page')) }}">Last created</a>
  </li>

  <li @if(request('sort')=='id_asc') class="active" @endif>
    <a href="{{ url('admin/role').sprintf('?filter=%s&sort=id_asc&page=%d', request('filter'), request('page')) }}">Oldset created</a>
  </li>

  <li @if(request('sort')=='updated_desc') class="active" @endif>
    <a href="{{ url('admin/role').sprintf('?filter=%s&sort=updated_desc&page=%d', request('filter'), request('page')) }}">Last updated</a>
  </li>

  <li @if(request('sort')=='updated_asc') class="active" @endif>
    <a href="{{ url('admin/role').sprintf('?filter=%s&sort=updated_asc&page=%d', request('filter'), request('page')) }}">Oldest updated</a>
  </li>
</ul>

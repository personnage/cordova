{{-- When apply filter, exclude the sort, pagination and search. --}}
<ul class="nav nav-pills">
    <li role="presentation" @unless(Request::has('filter')) class="active" @endunless>
        <a href="{{ url('admin/role') }}">Active <span class="badge">{{ $active }}</span></a>
    </li>

    <li role="presentation" @if(request('filter')=='deleted') class="active" @endif>
        <a href="{{ url('admin/role').'?filter=deleted' }}">Deleted <span class="badge">{{ $deleted }}</span></a>
    </li>
</ul>

